<?php

require 'inc/lib/IP/Lifo/IP/IP.php';
require 'inc/lib/IP/Lifo/IP/BC.php';
require 'inc/lib/IP/Lifo/IP/CIDR.php';

use Lifo\IP\CIDR;

class Bans {
  public static function parse_time($str) {
    if (empty($str))
      return false;

    if (($time = @strtotime($str)) !== false)
      return $time;

    if (!preg_match('/^((\d+)\s?ye?a?r?s?)?\s?+((\d+)\s?mon?t?h?s?)?\s?+((\d+)\s?we?e?k?s?)?\s?+((\d+)\s?da?y?s?)?((\d+)\s?ho?u?r?s?)?\s?+((\d+)\s?mi?n?u?t?e?s?)?\s?+((\d+)\s?se?c?o?n?d?s?)?$/', $str, $matches))
      return false;

    $expire = 0;

    if (isset($matches[2])) {
      // Years
      $expire += $matches[2]*60*60*24*365;
    }
    if (isset($matches[4])) {
      // Months
      $expire += $matches[4]*60*60*24*30;
    }
    if (isset($matches[6])) {
      // Weeks
      $expire += $matches[6]*60*60*24*7;
    }
    if (isset($matches[8])) {
      // Days
      $expire += $matches[8]*60*60*24;
    }
    if (isset($matches[10])) {
      // Hours
      $expire += $matches[10]*60*60;
    }
    if (isset($matches[12])) {
      // Minutes
      $expire += $matches[12]*60;
    }
    if (isset($matches[14])) {
      // Seconds
      $expire += $matches[14];
    }

    return time() + $expire;
  }

  static public function find($criteria, $board = false, $get_mod_info = false, $id = false, $criteriarange = false) {
    global $config;

    $query = prepare('SELECT ``bans``.*' . ($get_mod_info ? ', `username`' : '') . ' FROM ``bans``
    ' . ($get_mod_info ? 'LEFT JOIN ``mods`` ON ``mods``.`id` = `creator`' : '') . '
    WHERE ' . ($id ? 'id = :id' : '
      (' . ($board !== false ? '(`board` IS NULL OR `board` = :board) AND' : '') . '
      (`iphash` = :ip ) OR (`iphash` = :iprange ))') . '
    ORDER BY `expires` IS NULL, `expires` DESC');
    
    if ($board !== false){
      $query->bindValue(':board', $board, PDO::PARAM_STR);
    }

    // pretty sure bindValue(':id',$criteria); is a bug
    if (!$id) {
      $query->bindValue(':ip', $criteria);
      $query->bindValue(':iprange', $criteriarange);
    } else {
      $query->bindValue(':id', $criteria);
    }

    $query->execute() or error(db_error($query));

    $ban_list = array();

    while ($ban = $query->fetch(PDO::FETCH_ASSOC)) {
      if (!isset($ban['expires'])) {
        $banPost = "";
        if ($ban['post']) {
          $banPost = json_decode($ban['post'], true);
        }
        $ban['post'] = $banPost;
        array_push($ban_list, $ban);
        continue;
      }
      if ($ban['expires'] >= time()) {
        continue;
      }
      if ($ban['seen'] && $config['require_ban_view']) {
        self::delete($ban['id']);
      }
      continue;
    }
    return $ban_list;
  }

  static public function stream_json($out = false, $filter_ips = false, $filter_staff = false, $board_access = false) {
    global $config, $pdo;

    if ($board_access && $board_access[0] == '*') $board_access = false;

    $query_addition = "";
    if ($board_access) {
      $boards = implode(", ", array_map(array($pdo, "quote"), $board_access));
      $query_addition .= "WHERE `board` IN (".$boards.")";
    }
    if ($board_access !== FALSE) {
      if (!$query_addition) {
        $query_addition .= " WHERE (`public_bans` IS TRUE) OR ``bans``.`board` IS NULL";
      }
    }

    $query = prepare("SELECT ``bans``.*, `username`, `type` FROM ``bans``
      LEFT JOIN ``mods`` ON ``mods``.`id` = `creator`
      LEFT JOIN ``boards`` ON ``boards``.`uri` = ``bans``.`board`
       :queryaddition 
       ORDER BY `created` DESC") ;
    $query->bindValue(':queryaddition', $query_addition);
    $query->execute() or error(db_error($query));
    $bans = $query->fetchAll(PDO::FETCH_ASSOC);

    $out ? fputs($out, "[") : print("[");

    $end = end($bans);
    foreach ($bans as &$ban) {
      if (isset($ban['post'])) {
        $post = json_decode($ban['post']);
        $ban['message'] = $post->body;
      }
      unset($ban['post'], $ban['creator']);

      if ($board_access === false || in_array ($ban['board'], $board_access)) {
        $ban['access'] = true;
      }

      if ($filter_staff || ($board_access !== false && !in_array($ban['board'], $board_access))) {
        switch ($ban['type']) {
          case ADMIN:
            $ban['username'] = 'Admin';
            break;
          case GLOBALVOLUNTEER:
            $ban['username'] = 'Global Volunteer';
            break;
          case MOD:
            $ban['username'] = 'Board Owner';
            break;
          case BOARDVOLUNTEER:
            $ban['username'] = 'Board Volunteer';
            break;
          default:
            $ban['username'] = '?';
        }
        $ban['vstaff'] = true;
      }
      unset($ban['type']);

      $json = json_encode($ban);
      $out ? fputs($out, $json) : print($json);

      if ($ban['id'] != $end['id']) {
        $out ? fputs($out, ",") : print(",");
      }
    }
    $out ? fputs($out, "]") : print("]");
  }

  static public function seen($ban_id) {
    global $config;
    $query = prepare("UPDATE ``bans`` SET `seen` = 1 WHERE `id` = :id"); 
    $query -> bindValue(':id', (int)$ban_id);
    $query->execute() or error(db_error($query));
    if (!$config['cron_bans']) {
      rebuildThemes('bans');
    }
  }

  static public function purge() {
    global $config;
    $query = prepare("DELETE FROM ``bans`` WHERE `expires` IS NOT NULL AND `expires` < :expiretime AND `seen` = 1");
    $query -> bindValue (':expireTime', time());
    $query->execute() or error(db_error($query));
    if (!$config['cron_bans']) rebuildThemes('bans');
  }

  static public function delete($ban_id, $modlog = false, $boards = false, $dont_rebuild = false) {
    global $config;

    if ($boards && $boards[0] == '*') $boards = false;

    if ($modlog) {
      $query = prepare("SELECT `iphash`, `board` FROM ``bans`` WHERE `id` = :uid"); 
      $query -> bindValue(':uid', (int)$ban_id);
      $query->execute() or error(db_error($query));
      
      // Ban doesn't exist
      if (!$ban = $query->fetch(PDO::FETCH_ASSOC)) {
        return false;
      }

      if ($boards !== false && !in_array($ban['board'], $boards)) {
        error($config['error']['noaccess']);
      }

      if ($ban['board']) {
        openBoard($ban['board']);
      }

      modLog("Removed ban #{$ban_id} for {$ban['iphash']}</a>");
    }

    $query = prepare("DELETE FROM ``bans`` WHERE `id` = :uid");
    $query -> bindValue(':uid', (int)$ban_id) ;
    $query->execute() or error(db_error($query));

    if (!$dont_rebuild || !$config['cron_bans']) rebuildThemes('bans');

    return true;
  }

  static public function new_ban($iphash, $reason, $length = false, $ban_board = false, $mod_id = false, $post = false) {
    global $config, $mod, $pdo, $board;

    if (!isset($iphash)) {
      error ("need an ip hash");
    }

    if ($mod_id === false) {
      $mod_id = isset($mod['id']) ? $mod['id'] : -1;
    }

    if (!in_array($ban_board, $mod['boards']) && $mod['boards'][0] != '*'){
      error($config['error']['noaccess']);
    }

    $query = prepare("INSERT INTO ``bans`` VALUES (NULL, :iphash, :time, :expires, :board, :mod, :reason, 0, :post, 0)");

    $query->bindValue(':iphash', $iphash);

    $query->bindValue(':mod', $mod_id);
    $query->bindValue(':time', time());

    if ($reason !== '') {
      $reason = escape_markup_modifiers($reason);
      markup($reason);
      $query->bindValue(':reason', $reason);
    } else {
      $query->bindValue(':reason', null, PDO::PARAM_NULL);
    }

    if ($length) {
      if (is_int($length) || ctype_digit($length)) {
        $length = time() + $length;
      } else {
        $length = self::parse_time($length);
      }
      $query->bindValue(':expires', $length);
    } else {
      $query->bindValue(':expires', null, PDO::PARAM_NULL);
    }

    if ($ban_board)
      $query->bindValue(':board', $ban_board);
    else
      $query->bindValue(':board', null, PDO::PARAM_NULL);

    if ($post) {
      $post['board'] = $board['uri'];
      $match_urls = '(?xi)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))';

      $matched = array();

      preg_match_all("#$match_urls#im", $post['body_nomarkup'], $matched);

      if (isset($matched[0]) && $matched[0]) {
        $post['body'] = str_replace($matched[0], '###Link-Removed###', $post['body']);
        $post['body_nomarkup'] = str_replace($matched[0], '###Link-Removed###', $post['body_nomarkup']);
      }

      $query->bindValue(':post', json_encode($post));
    } else {
      $query->bindValue(':post', null, PDO::PARAM_NULL);
    }

    $query->execute() or error(db_error($query));

    if (isset($mod['id']) && $mod['id'] == $mod_id) {
      modLog('Created a new ' .
        ($length > 0 ? preg_replace('/^(\d+) (\w+?)s?$/', '$1-$2', until($length)) : 'permanent') .
        ' ban on ' .
        ($ban_board ? '/' . $ban_board . '/' : 'all boards') .
        ' for ' .
        "{$iphash}" .
        ' (<small>#' . $pdo->lastInsertId() . '</small>)' .
        ' with ' . ($reason ? 'reason: ' . utf8tohtml($reason) . '' : 'no reason'));
    }

    if (!$config['cron_bans']) rebuildThemes('bans');

    return $pdo->lastInsertId();
  }
}
