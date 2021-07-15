<?php

require_once 'getSendData.php';
require_once 'db.php';

class fillingDB
{
	public $host;
	public $user;
	public $password;
	public $db;
	public $iatas_json;
	public $connector;
	public $apiData;
	public $iatasInTable;
	public $airplains;
	public $carrierCodes;

	function __construct(string $host, string $user, string $password, string $db)
	{
		$this->host       = $host;
		$this->user       = $user;
		$this->password   = $password;
		$this->db         = $db;
		$database = db::getInstance();
		$config = $database->getConfig();
		$database->connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database'], $config['db']['type']);
		$this->connector  = $database->get_connector();
	}

	private function GetIATAs($file)
	{
		$IATAS    = file_get_contents($file);
		$response = json_decode($IATAS, true);

		return json_decode($response, true);
	}

	private function connectDB($host, $user, $password, $db)
	{
		$link = mysqli_connect($host, $user, $password, $db);

		if (!$link)
		{
    		echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
    		echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
    		echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
    		exit;
		}

		//echo "Соединение с MySQL установлено!" . PHP_EOL;
		//echo "Информация о сервере: " . mysqli_get_host_info($link) . PHP_EOL;

		return $link;
	}

	public function createDBAndTables($dbname=false, array $tables)
	{
		if($dbname)
			$query_tmp = 'CREATE DATABASE ' . $dbname . '; ';
		else
			$query_tmp = '';
		foreach ($tables['table'] as $key => $value)
		{
			$query_tmp .= 'CREATE TABLE IF NOT EXISTS ';
			$query_tmp .= $key;
			$query_tmp .= '(';
			for ($i = 0; $i < count($tables['table'][$key]); $i++)
			{
				$query_tmp .= $tables['table'][$key][$i];
				$query_tmp .= ($i != count($tables['table'][$key]) - 1) ? ', ' : '';
			}
			$query_tmp .= ')';
			$query_tmp .= ';';
		}
		return $query_tmp;
	}

	public function getAmadeusAPI($iata1, $iata2, $date, $key)
	{
		$array_data = json_decode(apiCurlData::getCurlData('https://test.api.amadeus.com/v2/shopping/flight-offers?originLocationCode=' . $iata1 . '&destinationLocationCode=' . $iata2 . '&departureDate=' . $date .  '&adults=1&nonStop=false&max=250', $key)['content'], true);
		$array_data[] = $iata1;
		$array_data[] = $iata2;
		return $array_data;
	}

	public function insertApiFlightsData($apiData)
	{
		$this->setIatasFromTable();
		$this->setAirplainsFromTable();
		$this->setCarriersCode();
		for($i = 0; $i < count($apiData['data']); $i++)
		{
			$query_flight    = 'INSERT INTO flight (id, IATA1, IATA2, price, planeid, free_sits, date_d, date_a, duration, carrier_id) values ';
			if(count($apiData['data'][$i]['itineraries'][0]['segments']) > 1)
			{
			 	$query_transfer  = 'INSERT INTO transfer (id, flight_id, IATA1, IATA2, date_dep, date_arrival, planeid, duration, carrier_id) values ';
			 	$this->query($query_flight . '(null, ' . $this->getIataIdInTable($apiData['data'][$i]['itineraries'][0]['segments'][0]['departure']['iataCode'], $apiData) . ', ' . $this->getIataIdInTable($apiData['data'][$i]['itineraries'][0]['segments'][count($apiData['data'][$i]['itineraries'][0]['segments']) - 1]['arrival']['iataCode'], $apiData) . ', ' . $apiData['data'][$i]['price']['total'] . ', ' . $this->getAirplainIdInTable($apiData['data'][$i]['itineraries'][0]['segments'][0]['aircraft']['code'], $apiData) . ', ' . '162' . ', ' . '"' . str_ireplace('T', ' ', $apiData['data'][$i]['itineraries'][0]['segments'][0]['departure']['at']) . '"' . ', ' . '"' . str_ireplace('T', ' ', $apiData['data'][$i]['itineraries'][0]['segments'][count($apiData['data'][$i]['itineraries'][0]['segments']) - 1]['arrival']['at']) . '"' . ', "' . $apiData['data'][$i]['itineraries'][0]['duration'] . '",' . $this->getCarrierCode($apiData['data'][$i]['itineraries'][0]['segments'][0]['carrierCode'], $apiData) . ');');
			 	$increment_id = $this->connector->lastInsertId();
			 	//var_dump($increment_id);
			 	for($j = 0; $j < count($apiData['data'][$i]['itineraries'][0]['segments']); $j++)
				{
			 		$query_transfer .= '(null, ' . $increment_id . ', ' . $this->getIataIdInTable($apiData['data'][$i]['itineraries'][0]['segments'][$j]['departure']['iataCode'], $apiData) . ', ' . $this->getIataIdInTable($apiData['data'][$i]['itineraries'][0]['segments'][$j]['arrival']['iataCode'], $apiData) . ', ' . '"' . str_ireplace('T', ' ', $apiData['data'][$i]['itineraries'][0]['segments'][$j]['departure']['at']) . '"' . ', ' . '"' . str_ireplace('T', ' ', $apiData['data'][$i]['itineraries'][0]['segments'][$j]['arrival']['at']) . '"' . ', ' . $this->getAirplainIdInTable($apiData['data'][$i]['itineraries'][0]['segments'][$j]['aircraft']['code'], $apiData) . ', "' . $apiData['data'][$i]['itineraries'][0]['segments'][$j]['duration'] . '", ' . $this->getCarrierCode($apiData['data'][$i]['itineraries'][0]['segments'][$j]['carrierCode'], $apiData) . '),';
				}
				$this->query(rtrim($query_transfer, ',') . ';');
			}
			else
			{
				$query_flight .= '(null, ' . $this->getIataIdInTable($apiData['data'][$i]['itineraries'][0]['segments'][0]['departure']['iataCode'], $apiData) . ', ' . $this->getIataIdInTable($apiData['data'][$i]['itineraries'][0]['segments'][0]['arrival']['iataCode'], $apiData) . ', ' . $apiData['data'][$i]['price']['total'] . ', ' . $this->getAirplainIdInTable($apiData['data'][$i]['itineraries'][0]['segments'][0]['aircraft']['code'], $apiData) . ', ' . '162' . ', ' . '"' . str_ireplace('T', ' ', $apiData['data'][$i]['itineraries'][0]['segments'][0]['departure']['at']) . '"' . ', ' . '"' . str_ireplace('T', ' ', $apiData['data'][$i]['itineraries'][0]['segments'][0]['arrival']['at']) . '", "' . $apiData['data'][$i]['itineraries'][0]['duration'] . '", ' . $this->getCarrierCode($apiData['data'][$i]['itineraries'][0]['segments'][0]['carrierCode'], $apiData) . '),';
				$this->query(rtrim($query_flight, ',') . ';');
			}
		}
	}

	public function insertIatas($file)
	{
		$iatas_array    = json_decode(json_decode(file_get_contents($file), true));
		$response_array = [];

		foreach ($iatas_array as $key => $value)
		{
			if(!in_array($key, $response_array))
				$response_array[] = $key;
			for($j = 0; $j < count($iatas_array->$key); $j++)
			{
				if(!in_array($iatas_array->$key[$j], $response_array))
					$response_array[] = $iatas_array->$key[$j];
			}
		}

		$insert_query = 'INSERT INTO city (id, IATA, name) values ';
		$count_response = count($response_array);

		for($i=0; $i < $count_response - 1; $i++)
		{
			$insert_query .= '(null, "' . $response_array[$i] . '", "' . $response_array[$i] . '"),';
		}

		$insert_query .= '(null, "' . $response_array[$count_response - 1] . '", "' . $response_array[$count_response - 1] . '")';

		return $insert_query;
	}

	public function query($query_string)
	{
		$db = db::getInstance();
		$result = $this->connector->query($query_string);
		return $result != false ? $db::parse_pdo_object($result) : false;
	}

	public function setIatasFromTable()
	{
		$this->iatasInTable = array_column($this->query('SELECT id, IATA from city'), 'IATA', 'id');
	}

	public function setAirplainsFromTable()
	{
		$this->airplains = array_column($this->query('SELECT id, planeid from plane'), 'planeid', 'id');
	}

	public function setCarriersCode()
	{
		$this->carrierCodes = array_column($this->query('SELECT id, carriers_id from carrier'), 'carriers_id', 'id');
	}

	public function getIataIdInTable($iata, &$apiData)
	{
		$result = array_search($iata, $this->iatasInTable);
		if(!$result)
		{
			$this->query('INSERT INTO city values (null, "' . $iata . '", ' . '"' . $apiData['dictionaries']['locations'][$iata]['countryCode'] . '"' . ')');
			$this->setIatasFromTable();
			return array_search($iata, $this->iatasInTable);
		}
		return $result;
	}

	public function getAirplainIdInTable($planeid, &$apiData)
	{
		$result = array_search($planeid, $this->airplains);
		if(!$result)
		{
			$this->query('INSERT INTO plane values (null, "' . $planeid . '", "' . $apiData['dictionaries']['aircraft'][$planeid] . '", 162)');
			$this->setAirplainsFromTable();
			return array_search($planeid, $this->airplains);
		}
		return $result;
	}

	public function getCarrierCode($carrierCode, &$apiData)
	{
		$result = array_search($carrierCode, $this->carrierCodes);
		if(!$result)
		{
			$this->query('INSERT INTO carrier values (null, "' . $carrierCode . '", "' . $apiData['dictionaries']['carriers'][$carrierCode] . '")');
			$this->setCarriersCode();
			return array_search($carrierCode, $this->carrierCodes);
		}
		return $result;
	}

	public function getAllAirWays()
	{
		$file = file_get_contents('IATA.json');
		$array = json_decode(json_decode($file), true);
		foreach ($array as $arrival_iata => $dep)
		{
			foreach ($dep as $key => $dep_iata)
			{
				try
				{
					$data = $this->getAmadeusAPI($arrival_iata, $dep_iata, '2020-11-06', '1');
					if(isset($data['errors']))
					{
						throw new Exception('Error Get Data: ' . $data['errors'][0]['title'] . ' on way: ' . $arrival_iata . '=>' . $dep_iata, 1);
					}
					$this->insertApiFlightsData($data);
				}
				catch(Exception $e)
				{
					echo 'Error ' . $e;
					return;
				}
			}
		}
	}

}

$tables_array = 
[
	'table' => 
	[
		'city'     => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'IATA VARCHAR(8) NOT NULL UNIQUE', 'name VARCHAR(20)'],

		'class'    => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'descri VARCHAR(20) NOT NULL'],

		'plane'    => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'planeid VARCHAR(10) NOT NULL UNIQUE' , 'name VARCHAR(50) NOT NULL', 'sits SMALLINT(3)'],

		'type'     => ['id TINYINT(1) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'descri VARCHAR(20) NOT NULL'],

		'flight'   => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'IATA1 INT(11) NOT NULL', 'IATA2 INT(11) NOT NULL', 'date_d DATETIME NOT NULL' ,'date_a DATETIME NOT NULL', 'price SMALLINT(5) NOT NULL', 'planeid INT(11) NOT NULL', 'free_sits SMALLINT(3) NOT NULL', 'carrier_id INT(11) NOT NULL', 'duration VARCHAR(15) NOT NULL', 'FOREIGN KEY (IATA1)  REFERENCES city (id) ON DELETE CASCADE', 'FOREIGN KEY (IATA2)  REFERENCES city (id) ON DELETE CASCADE', 'FOREIGN KEY (planeid)  REFERENCES plane (id) ON DELETE CASCADE', 'FOREIGN KEY (carrier_id)  REFERENCES carrier  (id) ON DELETE CASCADE'],

		'role'     => ['id TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY', 'descri VARCHAR(50)'],

		'user'     => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'first_name VARCHAR(30)', 'second_name VARCHAR(30)', 'last_name VARCHAR(30)', 'document_sn VARCHAR(20)', 'email VARCHAR(255)', 'age TINYINT(1) NOT NULL', 'sex BOOL NOT NULL', 'role TINYINT(1)' , 'FOREIGN KEY (role) REFERENCES role (id) ON DELETE SET NULL', 'login VARCHAR(255) NOT NULL UNIQUE', 'pas VARCHAR(255) NOT NULL'],

		'ticket'   => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'owner INT(11) NOT NULL', 'reservation BOOL NOT NULL', 'type TINYINT(1)', 'flight INT(11)', 'sit VARCHAR(3)', 'pas_full_name VARCHAR(150) NOT NULL', 'FOREIGN KEY (owner)  REFERENCES user (id) ON DELETE CASCADE',  'FOREIGN KEY (type)  REFERENCES type (id) ON DELETE SET NULL', 'FOREIGN KEY (flight)  REFERENCES flight (id) ON DELETE SET NULL'],

		'transfer' => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'flight_id INT(11) NOT NULL', 'IATA1 INT(11) NOT NULL', 'IATA2 INT(11) NOT NULL', 'date_dep DATETIME NOT NULL', 'date_arrival DATETIME NOT NULL', 'planeid INT(11) NOT NULL', 'duration VARCHAR(15) NOT NULL', 'carrier_id INT(11) NOT NULL',   'FOREIGN KEY (flight_id)  REFERENCES flight (id) ON DELETE CASCADE', 'FOREIGN KEY (IATA1)  REFERENCES city (id) ON DELETE CASCADE', 'FOREIGN KEY (IATA2)  REFERENCES city (id) ON DELETE CASCADE', 'FOREIGN KEY (carrier_id)  REFERENCES carrier (id) ON DELETE CASCADE'],

		'carrier' => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'carriers_id VARCHAR(10) NOT NULL UNIQUE', 'full_name VARCHAR(255) NOT NULL'],

		'transfer_ticket' => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'transfer_id INT(11) NOT NULL', 'sit VARCHAR(3)', 'ticket_id INT(11) NOT NULL', 'FOREIGN KEY (transfer_id)  REFERENCES transfer (id) ON DELETE CASCADE', 'FOREIGN KEY (ticket_id)  REFERENCES ticket (id) ON DELETE CASCADE'],

		'order' => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'owner INT(11) NOT NULL', 'special_id VARCHAR(255) NOT NULL', 'ticket_id INT(11) NOT NULL', 'FOREIGN KEY (owner)  REFERENCES user (id) ON DELETE CASCADE', 'FOREIGN KEY (ticket_id)  REFERENCES ticket (id) ON DELETE CASCADE']
	],
];

$tables_city =
[
	'table' =>
	[
		'user' => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'first_name VARCHAR(30)', 'second_name VARCHAR(30)', 'last_name VARCHAR(30)', 'email VARCHAR(255)', 'birth_date DATE NOT NULL', 'login VARCHAR(255) NOT NULL UNIQUE', 'pas VARCHAR(255) NOT NULL'],

		'company' => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'login VARCHAR(255) UNIQUE', 'pas VARCHAR(255)', 'name VARCHAR(100)', 'legal_address VARCHAR(255)', 'physical_address VARCHAR(255)', 'phone VARCHAR(255)', 'email VARCHAR(255)', 'description TEXT', 'type TINYINT', 'FOREIGN KEY (type) REFERENCES type (id)'],

		'c_type' => ['id TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY', 'desc VARCHAR(255)'],

		'worker' => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'full_name VARCHAR(255)', 'company INT(11)', 'position VARCHAR(50)', 'auto_reg VARCHAR(10)', 'email VARCHAR(255)'],

		'role' => ['id TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY', 'descri VARCHAR(50)'],

		'right' => ['id TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY', 'role TINYINT', 'user INT(11)', 'FOREIGN KEY (role)  REFERENCES role (id)', 'FOREIGN KEY (user)  REFERENCES user (id)'],

		'news' => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'news_text TEXT', 'author INT(11)', 'img VARCHAR(255)', 'heading VARCHAR(50)', 'desc VARCHAR(100)', 'public_date DATETIME', 'type TINYINT', 'FOREIGN KEY (author)  REFERENCES user (id)', 'FOREIGN KEY (type)  REFERENCES news_type (id)'],

		'news_moder' => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'news INT(11) NOT NULL', 'moderator INT(11) NOT NULL', 'FOREIGN KEY (news)  REFERENCES news (id)', 'FOREIGN KEY (moderator)  REFERENCES user (id)'],

		'news_type' => ['id TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY', 'desc VARCHAR(50)'],

		//'user_type' => ['id TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY', 'desc VARCHAR(30)'],

		'baner' => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'adver_text VARCHAR(255) NOT NULL', 'img VARCHAR(255) NOT NULL', 'owner INT(11) NOT NULL', 'start_date DATETIME NOT NULL', 'end_date DATETIME NOT NULL'],

		'document' => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'type TINYINT NOT NULL', 'numbers VARCHAR(50) NOT NULL', 'owner INT(11) NOT NULL', 'FOREIGN KEY (owner)  REFERENCES user (id)', 'FOREIGN KEY (owner) REFERENCES user (id) ON DELETE CASCADE', 'FOREIGN KEY (type)  REFERENCES doc_type (id)'],

		'doc_type' => ['id TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY', 'desc VARCHAR(50)'],

		'company_document' => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'company INT(11) NOT NULL', 'nubmers VARCHAR(255)', 'type TINYINT', 'FOREIGN KEY (type) REFERENCES c_document_type (id)'],

		'c_document_type' => ['id TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY', 'desc VARCHAR(50)'],

		'service' => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'type TINYINT', 'price INT(3)', 'provider INT(11)', 'FOREIGN KEY (type) REFERENCES service_type (id) ON DELETE CASCADE'],

		'service_type' => ['id TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY', 'desc VARCHAR(50)'],

		'owner_service' => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'service_id INT(11)', 'user INT(11)', 'FOREIGN KEY (user) REFERENCES user (id)', 'FOREIGN KEY (service_id)  REFERENCES service (id)'],

		'service_promotion' => ['id TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY', 'desc VARCHAR(50)', 'img VARCHAR(255)'],

		'discount_card' => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'owner INT(11)', 'serial_number VARCHAR(50)', 'company INT(11)', 'FOREIGN KEY (company) REFERENCES company (id)'],

		'requests' => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'type TINYINT', 'from INT(11)', 'desc VARCHAR(255)', 'owner_u INT(11)', 'owner_c INT(11)', 'FOREIGN KEY (type) REFERENCES req_type (id)', 'FOREIGN KEY (owner_u) REFERENCES user (id)', 'FOREIGN KEY (owner_c) REFERENCES company (id)'],

		'req_type' => ['id TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY', 'desc VARCHAR(50)'],

		'complaint' => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'to INT(11)', 'from INT(11)', 'body TEXT', 'type TINYINT', 'to_alt VARCHAR(255)', 'FOREIGN KEY (type REFERENCES complaint_type (id)'],

		'complaint_type' => ['id TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY', 'desc VARCHAR(50)'],

		'fine' => ['id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY', 'document VARCHAR(50)', 'type TINYINT'],

		'f_type' => ['id TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY', 'desc VARCHAR(255)'],

	]
];

// $fillingDB = new fillingDB('10.126.10.121:3306', 'script', ';zxxslfc', 'avia');

// $data = $fillingDB->getAmadeusAPI('ABQ', 'SEA', '2020-11-17', 'QZWubhfAXichEUhSJ4DJHzuMjUtk');

// var_dump(count($data['data']));
// var_dump($data);
// $fillingDB->insertApiFlightsData($data = isset($data['errors']) ? die ('Ошибка! ' . $data['errors'][0]['title']) : $data);