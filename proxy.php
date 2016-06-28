<?php
// HTML to canvas proxy for screen capture *lance*
$url = filter_var(trim($_GET['url']),FILTER_SANITIZE_URL);
if (strpos($url, '8ch.net') == true || strpos($url, 'img.youtube.com') == true) {
  $ext = explode(".", $url);
  $ext = $ext[count($ext)-1];
  $img = "data:image/".$ext.";base64," . base64_encode(file_get_contents($url));
  $id  = filter_var(trim($_GET['id']), FILTER_SANITIZE_NUMBER_INT);
  header('Content-Type: application/json');
  echo json_encode(array('src' => $img, 'id' => $id));
} else {
  die("NG");
}
?>
