<?php
header('Access-Control-Allow-Origin: *');

$mode = @$_GET['mode'];

require_once("cool-php-captcha-0.3.1/captcha.php");

function rand_string($length, $charset) {
  $ret = "";
  while ($length--) {
    $ret .= mb_substr($charset, rand(0, mb_strlen($charset, 'utf-8')-1), 1, 'utf-8');
  }
  return $ret;
}

function cleanup ($pdo, $expires_in) {
  $pdo->prepare("DELETE FROM `captchas` WHERE `created_at` < ?")->execute([time() - $expires_in]);
}

switch ($mode) {
// Request: GET entrypoint.php?mode=get&extra=1234567890
// Response: JSON: cookie => "generatedcookie", captchahtml => "captchahtml", expires_in => 120
case "get":
  if (!isset ($_GET['extra'])) {
    die();
  }

  $extra = $_GET['extra'];
  $nojs = isset($_GET['nojs']);

  require_once("config.php");

  $text = rand_string($length, $extra);

  //$captcha = new SimpleCaptcha($text, $width, $height, $extra);
  $captcha = new SimpleCaptcha();

  $cookie = rand_string(20, "abcdefghijklmnopqrstuvwxyz");

  ob_start();
  $captcha->CreateImage($text);
  $image = ob_get_contents();
  ob_end_clean();
  $html = '<image src="data:image/png;base64,'.base64_encode($image).'">';

  $query = $pdo->prepare("INSERT INTO `captchas` (`cookie`, `extra`, `text`, `created_at`) VALUES (?, ?, ?, ?)");
  $query->execute(                               [$cookie,  $extra,  $text,  time()]);

  if ($nojs) {
	  header("Content-type: text/html");
	  echo "<html><body>You do not have JavaScript enabled. To fill out the CAPTCHA, please copy the ID to the post form in the ID field, and write the answer in the answer field.<br><br>CAPTCHA ID: $cookie<br>CAPTCHA image: $html</body></html>";
  } else {
	  header("Content-type: application/json");
	  echo json_encode(["cookie" => $cookie, "captchahtml" => $html, "expires_in" => $expires_in]);
  }
  
  break;

// Request: GET entrypoint.php?mode=check&cookie=generatedcookie&extra=1234567890&text=captcha
// Response: 0 OR 1
case "check":
  if (!isset ($_GET['mode'])
   || !isset ($_GET['cookie'])
   || !isset ($_GET['extra'])
   || !isset ($_GET['text'])) {
    die();
  }

  require_once("config.php");

  cleanup($pdo, $expires_in);

  $query = $pdo->prepare("SELECT * FROM `captchas` WHERE `cookie` = ? AND `extra` = ?");
  $query->execute([$_GET['cookie'], $_GET['extra']]);

  $ary = $query->fetchAll();

  if (!$ary) {
    echo "0";
  }
  else {
    $query = $pdo->prepare("DELETE FROM `captchas` WHERE `cookie` = ? AND `extra` = ?");
    $query->execute([$_GET['cookie'], $_GET['extra']]);

    if ($ary[0]['text'] !== $_GET['text']) {
      echo "0";
    }
    else {
      echo "1";
    }
  }

  break;
}
