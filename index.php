<?php
include 'inc/functions.php';

echo Element($config['instance_id'] . "/index.html", array("config" => $config)); // TODO: do a sanity check here?
