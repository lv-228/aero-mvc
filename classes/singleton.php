<?php

/**
 * 
 */
class singleton
{
	private static $instances = [];
	
	protected function __construct()
	{
		# code...
	}

	protected function __clone()
	{
		# code...
	}

	public function __wakeup()
	{
		throw new Exception('Cannot unserialize a singleton.');
	}

	public static function getInstance()
	{
		$cls = static::class;
		if(!isset(self::$instances[$cls]))
		{
			self::$instances[$cls] = new static();
		}
		return self::$instances[$cls];
	}

	
}