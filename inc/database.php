<?php

/*
 *  Copyright (c) 2010-2013 Tinyboard Development Group
 */

defined('TINYBOARD') or exit;

function sql_open() {
	global $pdo, $config;
	if ($pdo)
		return true;
	
	if (isset($config['db']['server'][0]) && $config['db']['server'][0] == ':')
		$unix_socket = substr($config['db']['server'], 1);
	else
		$unix_socket = false;
	
	$dsn = $config['db']['type'] . ':' .
		($unix_socket ? 'unix_socket=' . $unix_socket : 'host=' . $config['db']['server']) .
		';dbname=' . $config['db']['database'];
	if (!empty($config['db']['dsn']))
		$dsn .= ';' . $config['db']['dsn'];
	try {
		$options = array(
			PDO::ATTR_TIMEOUT => $config['db']['timeout'],
			PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
		);
		if ($config['db']['persistent'])
			$options[PDO::ATTR_PERSISTENT] = true;
		$pdo = new PDO($dsn, $config['db']['user'], $config['db']['password'], $options);
		
		if (mysql_version() >= 50503)
			query('SET NAMES utf8mb4') or error(db_error());
		else
			query('SET NAMES utf8') or error(db_error());
		return $pdo;
	} catch(PDOException $e) {
		$message = $e->getMessage();
		
		// Remove any sensitive information
		$message = str_replace($config['db']['user'], '<em>hidden</em>', $message);
		$message = str_replace($config['db']['password'], '<em>hidden</em>', $message);
		
		// Print error
		if ($config['mask_db_error']) {
			error(_('Could not connect to the database. Please try again later.'));
		} else { 
			error(_('Database error: ') . $message);
		}
	}
}

// 5.6.10 becomes 50610
function mysql_version() {
	global $pdo;
	
	$version = $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
	$v = explode('.', $version);
	if (count($v) != 3)
		return false;
	return (int) sprintf("%02d%02d%02d", $v[0], $v[1], $v[2]);
}

function prepare($query) {
	global $pdo, $config;
	
	$query = preg_replace('/``('.$config['board_regex'].')``/u', '`' . $config['db']['prefix'] . '$1`', $query);
	
	sql_open();
	
	return $pdo->prepare($query);
}

function query($query) {
	global $pdo, $config;
	
	$query = preg_replace('/``('.$config['board_regex'].')``/u', '`' . $config['db']['prefix'] . '$1`', $query);
	
	sql_open();
	
	return $pdo->query($query);
}

function db_error($PDOStatement = null) {
	global $pdo, $db_error, $config;

	if ($config['mask_db_error']) {
		return _('The database returned an error while processing your request. Please try again later.');
	}

	if (isset($PDOStatement)) {
		$db_error = $PDOStatement->errorInfo();
		return $db_error[2];
	}

	$db_error = $pdo->errorInfo();
	return $db_error[2];
}
