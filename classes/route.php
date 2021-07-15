<?php

require_once 'db.php';
/**
 * 
 */
class route
{

	public static function parsePath()
	{
		$config = route::getConfig();
		if(array_key_first($_GET) == NULL)
			header('Location: index.php?' . array_key_first($config['main']) . '=' . $config['main'][array_key_first($config['main'])]);
		else
			route::getController(array_key_first($_GET));
	}

	public static function getController($controllerName)
	{
		$controllerName = str_replace('\\', DIRECTORY_SEPARATOR, $controllerName);
		$file = dirname(__DIR__).DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.$controllerName.'.php';
		$db     = db::getInstance();
		$config = db::getConfig();
		$db->connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database'], $config['db']['type']);
		
		if(file_exists($file))
		{
			require_once $file;
		}
		else
			throw new Exception('Error 404');
	}

	public static function getConfig()
	{
		$config = file_get_contents('./config.json');
		return json_decode($config, true);
	}
}