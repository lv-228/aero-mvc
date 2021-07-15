<?php

require_once 'classes/db.php';

$db     = db::getInstance();
$config = db::getConfig();

$db->connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database'], $config['db']['type']);
