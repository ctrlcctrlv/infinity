<?php
/*
 * Secrets for configuration
 *
 * Included from instance-config.php.
 *
 * Copy this file to secrets.php and edit.
 */

$config['db']['server'] = 'localhost';
$config['db']['database'] = '8chan';
//$config['db']['prefix'] = '';
$config['db']['user'] = 'eightchan-user';
$config['db']['password'] = 'mysecretpassword';

// Consider generating these from the following command.
// $ cat /proc/sys/kernel/random/uuid
$config['secure_trip_salt'] = 'generate-a-uuid';
$config['cookies']['salt'] = 'generate-a-uuid';
