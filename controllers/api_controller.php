<?php
require_once 'controller.php';
require_once './models/flight_model.php';
require_once './models/plane_model.php';
require_once './models/carrier_model.php';
require_once './models/city_model.php';

/**
 * 
 */
class api_controller extends controller
{
	public static $get =
	[
		'get_flights' =>
		[
			'origin'      => false,
			'destination' => false
		]
	];

	public function get_flights($vars)
	{
		$city        = new city();
		$plane       = new plane();
		$carrier     = new carrier();
		$time        = $GLOBALS['thisDate']['hours'] . ':' . $GLOBALS['thisDate']['minutes'] . ':' . $GLOBALS['thisDate']['seconds'];
		$flight      = new flight();
		$flight_data = $flight->db_query('SELECT * FROM flight where id in (5305, 4918, 4911)');
		if($flight_data != false)
		{
			for($i=0; $i < count($flight_data); $i++)
			{
				$flight_data[$i]['IATA1']   = $city->get_by_id((int)$flight_data[$i]['IATA1'])[0]['name'];
				$flight_data[$i]['IATA2']   = $city->get_by_id((int)$flight_data[$i]['IATA2'])[0]['name'];
				$flight_data[$i]['plane']   = $plane->get_by_id($flight_data[$i]['planeid'])[0]['name'];
				$flight_data[$i]['carrier'] = $carrier->get_by_id($flight_data[$i]['carrier_id'])[0]['full_name'];
				$transfer_data              = $flight->db_query('SELECT * FROM transfer WHERE flight_id = ' . $flight_data[$i]['id']);
				if(!empty($transfer_data))
				{
					for($j=0; $j < count($transfer_data); $j++)
					{
						$transfer_data[$j]['IATA1']   = $city->get_by_id((int)$transfer_data[$j]['IATA1'])[0]['name'];
						$transfer_data[$j]['IATA2']   = $city->get_by_id((int)$transfer_data[$j]['IATA2'])[0]['name'];
						$transfer_data[$j]['plane']   = $plane->get_by_id((int)$transfer_data[$j]['planeid'])[0]['name'];
						$transfer_data[$j]['carrier'] = $carrier->get_by_id((int)$transfer_data[$j]['carrier_id'])[0]['full_name'];
						$flight_data[$i]['transfer']  = $transfer_data;
					}
				}
			}
		}
		echo json_encode($flight_data);
	}
}

api_controller::do();
