<?php
	session_start(['cookie_lifetime' => 86400]);
/**
 * 
 */
class controller
{
	//Подключяет представление
	public static function getView($viewName, $data=false, $message=false)
	{
		$viewName = str_replace('\\', DIRECTORY_SEPARATOR, $viewName);
		$file = dirname(__DIR__).DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.$viewName.'.php';
		if(file_exists($file))
		{
			require_once $file;
		}
		else
			throw new Exception('Error 404');
	}

	//Запуск контроллера
	public static function do()
	{
		$class = static::class;

		$action = $_GET[array_key_first($_GET)];

		$vars = controller::check_vars_array($class, $action);

		$class_methods = get_class_methods($class);

		if(is_string($action) && $action != '')
		{
			$controller = new $class();
			if(in_array($action, $class_methods))
			{
				if(controller::checkResol(array_key_first($_GET), $action))
					$controller->$action($vars);
				else
					throw new Exception('Error 403');
			}
			else
				throw new Exception('Error 404');
		}
		else
			throw new Exception('Error 404');
	}

	//Проверка доступа к действию
	public static function checkResol(string $controller, string $method)
	{
		$config = file_get_contents('./config.json');
		$config = json_decode($config, true);
		if(session_status() == 2 && isset($_SESSION['role']))
		{
			if(isset($config['resol'][$_SESSION['role']][$controller]))
				if(is_array($config['resol'][$_SESSION['role']][$controller]))
				{
					if(in_array($method, $config['resol'][$_SESSION['role']][$controller]))
					{
						return true;
					}
				}
				if(isset($config['resol'][$_SESSION['role']][$controller]) && is_string($config['resol'][$_SESSION['role']][$controller]))
				{
					if($config['resol'][$_SESSION['role']][$controller] == $method)
						return true;
				}
		}
		if(count($_SESSION) == 0 || (isset($_SESSION['message']) && count($_SESSION) - 1 == 0) && isset($config['resol']['0'][$controller]))
		{
			if(is_array($config['resol']['0'][$controller]))
			{
				if(in_array($method, $config['resol']['0'][$controller]))
					return true;
			}
			if(is_string($config['resol']['0'][$controller]))
			{
				if($config['resol']['0'][$controller] == $method)
					return true;
			}
		}
		throw new Exception('Error 401', 1);
	}

	public static function getConfig()
	{
		$config = file_get_contents('./config.json');
		return json_decode($config, true);
	}

	public static function check_vars_array($class_name, $action)
	{
		$secure_get = controller::secure_user_input($_GET);
		$secure_post = controller::secure_user_input($_POST);
		$return = array();
		$class_vars = get_class_vars($class_name);
		unset($secure_get[array_key_first($secure_get)]);
		$return = ['get' => controller::check_array($class_vars, $secure_get, $action, 'get'), 'post' => controller::check_array($class_vars, $secure_post, $action, 'post')];
		return $return;
	}

	public static function secure_user_input($array)
	{
		if (! function_exists('is_countable'))
		{
    		function is_countable($value): bool
    		{
        		return is_array($value) || (is_object($value) && $value instanceof Countable);
    		}
		}
		$return = [];
        $answer = [];
		foreach($array as $key => $value)
		{
    		if(!is_countable($array[$key]) || count($array[$key]) == 1)
    		{
    			if(is_array($value))
    			{
    				foreach ($value as $arr_key => $arr_value)
    				{
    					$return[$key][$arr_key] = $arr_value;
    				}
    			}
    			else
        			$return[$key] = htmlspecialchars($value);
    		}
    		else
    		{
        		foreach($array[$key] as $z => $other_value)
        		{
            		if(!is_countable($array[$key][$z]))
            		{
                		$answer[$z] = htmlspecialchars($other_value);
            		}
            		else
            		{
                		foreach ($array[$key][$z] as $id => $ticket)
                		{
                    		$answer[$z][$id] = htmlspecialchars($ticket);
                		}
            		}

        		}
        		$return[$key] = $answer;
    		}
		}
		return $return;
	}

	public static function count_req_params($class_vars)
	{
		$result = 0;
		foreach ($class_vars as $key => $value)
		{
			if($value != false)
				$result++;
		}
		return $result;
	}

	public static function check_array($class_vars, $data_array, $action, $data_type)
	{
		//var_dump($data_array);
		if(isset($class_vars[$data_type][$action]))
		{
			$count_req = controller::count_req_params($class_vars[$data_type]);
			$return = [];
			foreach ($class_vars[$data_type][$action] as $key => $value)
			{
				if($value != false)
				{
					if(isset($data_array[$value]))
						$return[$value] = $data_array[$value];
					else
						throw new Exception('Error 400', 1);		
				}
				else
				{
					if(isset($data_array[$key]))
						$return[$key] = $data_array[$key];
				}
			}
			return $return;
		}
	}

	public static function unset_array(&$array)
	{
		foreach ($array as $key => $value)
		{
			unset($array[$key]);
		}
	}
}
