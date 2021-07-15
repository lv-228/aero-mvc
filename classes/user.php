<?php
require_once 'singleton.php';
/**
 * 
 */
class user_class extends singleton
{
	private static $hash_func;
	private static $salt;
	private static $table;
	private static $login_field;
	private static $pass_field;
	private static $role;
	private static $secret;

	public function set_hash($hash)
	{
		self::$hash_func = $hash;
	}

	public function set_salt($salt)
	{
		self::$salt = $salt;
	}

	public function set_table($table)
	{
		self::$table = $table;
	}

	public function set_secret($secret)
	{
		self::$secret = $secret;
	}

	public function set_login_field($field)
	{
		self::$login_field = $field;
	}

	public function set_pass_field($field)
	{
		self::$pass_field = $field;
	}

	public function set_role_field($field)
	{
		self::$role = $field;
	}

	public function get_config()
	{
		$config = file_get_contents('./config.json');
		return json_decode($config, true);
	}

	public function authentication(string $login, string $password, array $set_params)
	{
		$password = hash(self::$hash_func, $password);
		$query    = 'SELECT * FROM ' . self::$table . ' WHERE ' . self::$login_field . ' = ' . '\'' . $login . '\' AND ' . self::$pass_field . ' = '  . '\'' . $password . '\'';
		$db       = db::getInstance();
		$user     = $db->get_connector()->query($query);
		$user     = $db->parse_pdo_object($user);
		if(empty($user))
		{
			$_SESSION['message'] = ['msg' => 'Error! Wrong password or login', 'type' => 'danger'];
			header('Location:index.php?ticket=main');
			return;
		}
		$tickets  = $db->parse_pdo_object($db->get_connector()->query('SELECT id, sit FROM ticket WHERE owner = ' . $user[0]['id']));
		for($j=0; $j<count($tickets); $j++)
        {
        	$tickets[$j]['transfers'] = $db->parse_pdo_object($db->get_connector()->query('SELECT * FROM transfer_ticket WHERE ticket_id = ' . $tickets[$j]['id']));
        }

		if(!empty($user))
		{
			$_SESSION              = $set_params;
			$_SESSION['role']      = $user[0][self::$role];
			$_SESSION['uid']       = $user[0]['id'];
			$_SESSION['u_tickets'] = $tickets;
			return true;
		}
		return false;
	}

	public function check_hash_role(string $cookie_role, string $cookie_key, int $role)
	{
		$config = user::get_config();
		$check = substr(crypt($role, $config['salt_type'] . substr($cookie_key, strlen($cookie_key) / 2) . '$'), strlen($config['salt_type'] . substr($cookie_key, strlen($cookie_key) / 2) . '$'));
		if($check == $cookie_role)
			return true;
		return false;
	}

	public function hash_cookie(array $user, string $salt)
	{
		$config = user::get_config();
		$cookie['login'] = substr(crypt($user[0]['login'], $config['salt_type'] . $salt . '$'), strlen($config['salt_type'] . $salt . '$'));
		$cookie['role']  = substr(crypt($user[0]['role'], $config['salt_type'] . substr($cookie['login'], strlen($cookie['login']) / 2) . '$'), strlen($config['salt_type'] . substr($cookie['login'], strlen($cookie['login']) / 2) . '$'));
		return $cookie;
	}

	 public static function generate_query($table_name,$fields_values)
 	{
 		$query   = 'INSERT INTO ' . $table_name . ' (';
 		$counter = 0;
 		$count   = count($fields_values);
 		foreach ($fields_values as $key => $value)
 		{
 			$query .=  $key;
 			$counter++;
 			if($counter != $count)
 			{
 				$query .= ',';
 			}
 		}
 		$counter = 0;
 		$query .= ') VALUES (';
 		foreach ($fields_values as $key => $value)
 		{
 			$query .= '\'' . $value . '\'';
 			$counter++;
 			if($counter != $count)
 			{
 				$query .= ',';
 			}
 		}
 		$query .= ');';
 		return $query;
 	}
}
