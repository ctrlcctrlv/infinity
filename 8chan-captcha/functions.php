<?php
if (strpos(getcwd(), '8chan-captcha') === false) chdir('8chan-captcha');
require_once("config.php");
require_once("cool-php-captcha-0.3.1/captcha.php");

function generate_captcha($extra = '1234567890') {
  global $length, $pdo;

  $text = rand_string($length, $extra);

  $captcha = new SimpleCaptcha();

  $cookie = rand_string(20, "abcdefghijklmnopqrstuvwxyz");

  ob_start();
  $captcha->CreateImage($text);
  $image = ob_get_contents();
  ob_end_clean();
  $html = '<image src="data:image/png;base64,'.base64_encode($image).'">';

  $query = $pdo->prepare("INSERT INTO `captchas` (`cookie`, `extra`, `text`, `created_at`) VALUES (?, ?, ?, ?)");
  $query->execute(                               [$cookie,  $extra,  $text,  time()]);

  return array("cookie" => $cookie, "html" => $html);
}

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

