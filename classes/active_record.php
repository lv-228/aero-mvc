<?php

require_once 'db.php';
/**
 * 
 */
class active_record
{

	public function find_all(int $limit = 0): PDOStatement
	{
		$query = 'SELECT * FROM ' . static::class;
		$query .= $limit == 0 ? '' : ' limit ' . $limit;
		return db::getInstance()->get_connector()->query($query);
	}

	public function find_by_id(int $id)
	{
		$query = 'SELECT * FROM ' . static::class . ' WHERE id = ' . $id;
		return static::parse_query_result(db::getInstance()->get_connector()->query($query));
	}

	public function delete_by_id(int $id): PDOStatement
	{
		$query = 'DELETE * FROM ' . static::class . ' WHERE id = ' . $id;
		return static::parse_query_result(db::getInstance()->get_connector()->query($query));
	}

	public static function parse_query_result(PDOStatement $object): array
	{
		return db::getInstance()->parse_pdo_object($object);
	}

	public function save()
	{
		$class = static::class;
		$db = db::getInstance();
		$fields = $db->get_table_columns($class, true);
		$set_vars = get_object_vars($this);
		for($i = 0; $i < count($set_vars); $i++)
		{
			if($fields[$i][0] != 'id')
			{
				if($set_vars[$fields[$i][0]] != null)
					self::check_type($set_vars[$fields[$i][0]], $fields[$i][1]);
				else
					throw new Exception('Active record error! Not all objects of the class', 1);
			}
		}

		unset($i);

		$query = 'INSERT INTO ' . $class . ' (';
		for ($i=0; $i < count($fields) - 1; $i++)
		{ 
			$query .= $fields[$i][0] . ', ';
		}
		$query .= $fields[$i++][0] . ') VALUES(null, ';

		unset($i);

		for($i=0; $i < count($set_vars); $i++)
		{
			if($fields[$i][0] != 'id')
				$query .= '\'' . $set_vars[$fields[$i][0]] . '\' ,';	
		}
		$query[strlen($query) - 1] = ')';
		
		$result = $db->get_connector()->query($query);
		//var_dump($result);
	}

	private function check_type($elem, $type): bool
	{
		$type = stristr($type, '(', true);
		if($type == 'varchar' || $type == 'string')
			if(!is_string($elem))
			{
				throw new Exception('Active record error! Incorrect object fields type', 1);
			}
		elseif($type == 'int')
			if(!is_int($elem))
			{
				throw new Exception('Active record error! Incorrect object fields type', 1);
			}
		return true;
	}

	public function db_connect(string $host, string $user, string $password, string $db_name, string $dbtype)
	{
		$db     = db::getInstance();
		$db->connect($host, $user, $password, $db_name, $dbtype);
	}

	public function get_connector()
	{
		$db = db::getInstance();
		return $db->get_connector();
	}

	public static function getConfig()
	{
		$config = file_get_contents('./config.json');
		return json_decode($config, true);
	}

	public function db_query(string $query)
	{
		$connector = active_record::create_and_return_connector();
		$result = $connector->query($query);
		return $result != false ? $this->parse_query_result($result) : false;
	}

	public function db_prepare_query(string $query, array $values)
	{
		$connector = active_record::create_and_return_connector();
		$prepare = $connector->prepare($query);
		active_record::bind_param($prepare, $values);
		$prepare->execute();
		return $prepare->fetchAll();
	}

	public function create_and_return_connector()
	{
		$config = db::getConfig();
		active_record::db_connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database'], $config['db']['type']);
		return active_record::get_connector();
	}

	public static function bind_param(&$prepare_obj, &$vars)
	{
		//var_dump($vars);die;
		$reflection = new ReflectionClass('PDO');
		$constants = $reflection->getConstants();
		for($i = 0; $i < count($vars); $i++)
		{
			$param = 'PARAM_' . $vars[$i]['type'];
			$prepare_obj->bindParam($i + 1, $vars[$i]['var'], $constants[$param]);
		}
	}
}
