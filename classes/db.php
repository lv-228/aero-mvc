<?php
require_once 'singleton.php';
/**
 * 
 */
class db extends singleton
{
	static public $connector;

	public function connect(string $host, string $user, string $password, string $db, string $dbtype)
	{
		$dsn = $dbtype . ':dbname=' . $db . ';host=' . $host;
		$PDO = false;

		try
		{
			$PDO = new PDO($dsn, $user, $password);
		}
		catch(PDOException $e)
		{
			die('Error! Connection failed: ' . $e->getMessage());
		}
		if($PDO != false)
			self::$connector = $PDO;
	}

	public function get_connector()
	{
		return self::$connector;
	}

	public static function parse_pdo_object(PDOStatement $object)
	{
		$return = [];
		foreach ($object as $key => $value)
		{
			foreach ($value as $key_2 => $value_2)
			{
				if(is_string($key_2))
					$return[$key][$key_2] = $value_2;
			}
		}
		return $return;
	}

	public function get_table_data($table)
	{
		return self::get_connector()->query('SHOW COLUMNS FROM ' . $table);
	}

	public function to_array_table_columns(array $db_columns): array
	{
		return array('field' => $db_columns['Field'], 'type' => $db_columns['Type'], 'null' => $db_columns['Null'], 'key' => $db_columns['Key'], 'default' => $db_columns['Default'], 'extra' => $db_columns['Extra']);
	}

	public function get_table_columns($table, $type = false)
	{
		$table_data = array();
		$fields = array();
		foreach (self::get_table_data($table) as $key => $value)
		{
			$table_data[] = db::getInstance()->to_array_table_columns($value);
		}
		for ($i=0; $i < count($table_data); $i++)
		{ 
			if($type == false)
				$fields[] = $table_data[$i]['field'];
			elseif($type == true)
			{
				$fields[] = [$table_data[$i]['field'], $table_data[$i]['type']];
			}
		}
		return $fields;
	}

	public static function getConfig()
	{
		$config = file_get_contents('./config.json');
		return json_decode($config, true);
	}
}