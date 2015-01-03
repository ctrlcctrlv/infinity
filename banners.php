<?php
header("Pragma-directive: no-cache");
header("Cache-directive: no-cache");
header("Cache-control: no-cache");
header("Pragma: no-cache");
header("Expires: 0");

function get_custom_banner(&$b) {
    # Validate the board name
    if (!(isset($b) && preg_match('/^[a-z0-9+]{1,30}$/', $b)))
        return null;

    # Check if directory exists
    $dir = "static/banners/$b/";
    if (!is_dir($dir))
        return null;

    # Return random file if directory is not empty
    $banners = array_diff(scandir($dir), array('..', '.'));
    if (!$banners)
        return null;
    $r = array_rand($banners);
    return $dir.$banners[$r];
}

$banner = get_custom_banner($_GET['board']);
if ($banner)
    header("Location: $banner");
else {
    include "inc/functions.php"; // if inc/instance-config.php was solo-includable this wouldn't need to load the entire DB
    header("Location: /templates/" . $config['instance_id'] . "/static/defaultbanner.png");
}
