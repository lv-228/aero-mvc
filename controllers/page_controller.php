<?php
require_once 'controller.php';
require_once './models/user_model.php';
require_once './classes/view.php';
require_once './models/flight_model.php';
require_once './models/ticket_model.php';
require_once './models/transfer_model.php';
require_once './models/plane_model.php';
require_once './models/carrier_model.php';
require_once './models/city_model.php';
/**
 * 
 */
class page_controller extends controller
{
	public static $get =
	[
		'users_registration_tables' => 
		[
			'fid'        => false,
			'type'       => false,
			'by'         => false,
			'value'      => false,
			'special_id' => false
		],
		'flight_table' =>
		[
			'transfer' => false
		],
		'flight_transfers' =>
		[
			'fid'
		],
		'flight_reg' =>
		[
			'fid',
			'tid',
			'uid' => false
		]
	];

	public function admin_panel()
	{
		$user = new user();
		page_controller::getView('main', ['page' => 'admin_panel.php', 'users' => $user->db_query('SELECT * FROM user WHERE role != 1')]);
	}

	public function users_registration_tables($vars)
	{
		if(isset($vars['get']['type']) && $vars['get']['type'] == 'Flight')
		{
			$flight      = new flight();
			$ticket      = new ticket();
			$transfer    = new transfer();
			$city        = new city();
			$plane       = new plane();
			$carrier     = new carrier();
			$transfer_data = $transfer->db_query('SELECT * FROM transfer WHERE flight_id = ' . $vars['get']['fid']);
			$tickets     = $ticket->db_query('SELECT * FROM ticket WHERE flight = ' . $vars['get']['fid']);
			$flight_data = $flight->get_by_id($vars['get']['fid']);
			if(!empty($tickets))
			{
				for($i=0; $i < count($tickets); $i++)
				{
					//user_data = $ticket->db_query('SELECT * from user where id = (SELECT owner FROM ticket WHERE flight = ' . $vars['get']['fid'] . ')');
					$user[$i]['full_name']   = $tickets[$i]['pas_full_name'];
					$user[$i]['id']          = $tickets[$i]['owner'];
					$user[$i]['doc']         = $tickets[$i]['document'];
					$user[$i]['ticket_type'] = $tickets[$i]['type'];
					$user[$i]['flight']      = $tickets[$i]['flight'];
					$user[$i]['sit']         = $tickets[$i]['sit'] == NULL ? 'Not reg' : $tickets[$i]['sit'];
					$user[$i]['ticket_id']   = $tickets[$i]['id'];
				}
			}
			if(!$flight_data)
			{
				$_SESSION['message'] = ['Flight not found', 'danger'];
				$idIsset = false;
			}
			$flight_data[0]['IATA1']   = $city->get_by_id($flight_data[0]['IATA1'])[0]['name'];
			$flight_data[0]['IATA2']   = $city->get_by_id($flight_data[0]['IATA2'])[0]['name'];
			$flight_data[0]['plane']   = $plane->get_by_id($flight_data[0]['planeid'])[0]['name'];
			$flight_data[0]['carrier'] = $carrier->get_by_id($flight_data[0]['carrier_id'])[0]['full_name'];
			page_controller::getView('main', ['page' => 'users_registration_tables.php', 'user_data' => $user, 'flight_data' => $flight_data, 'transfers' => $transfer_data, 'tickets' => $tickets]);
			return;
		}
		if(isset($vars['get']['type']) && $vars['get']['type'] == 'Transfer')
		{
			$flight           = new flight();
			$ticket           = new ticket();
			$transfer         = new transfer();
			$city             = new city();
			$plane            = new plane();
			$carrier          = new carrier();
			$transfer_data    = $transfer->db_query('SELECT *, date_dep AS date_d, date_arrival AS date_a FROM transfer WHERE id = ' . $vars['get']['fid']);
			$transfer_tickets = $transfer->db_query('SELECT * FROM transfer_ticket WHERE transfer_id = ' . $vars['get']['fid']);
			for($i=0; $i<count($transfer_tickets); $i++)
			{
				$user_ticket = $transfer->db_query('SELECT * FROM ticket WHERE id = ' . $transfer_tickets[$i]['ticket_id']);
				$user[$i]['full_name']   = $user_ticket[0]['pas_full_name'];
				$user[$i]['id']          = $user_ticket[0]['owner'];
				$user[$i]['doc']         = $user_ticket[0]['document'];
				$user[$i]['ticket_type'] = $user_ticket[0]['type'];
				$user[$i]['flight']      = $user_ticket[0]['flight'];
				$user[$i]['ticket_id']   = $user_ticket[0]['id'];
				$user[$i]['sit']         = $transfer_tickets[$i]['sit'] == NULL ? 'Not reg' : $transfer_tickets[$i]['sit'];
			}
			$transfer_data[0]['IATA1']   = $city->get_by_id($transfer_data[0]['IATA1'])[0]['name'];
			$transfer_data[0]['IATA2']   = $city->get_by_id($transfer_data[0]['IATA2'])[0]['name'];
			$transfer_data[0]['plane']   = $plane->get_by_id($transfer_data[0]['planeid'])[0]['name'];
			$transfer_data[0]['carrier'] = $carrier->get_by_id($transfer_data[0]['carrier_id'])[0]['full_name'];
			page_controller::getView('main', ['page' => 'users_registration_tables.php', 'user_data' => $user, 'transfer_data' => $transfer_data, 'flight_data' => $transfer_data, 'tickets' => $transfer_tickets]);
			return;
		}

		if(isset($vars['get']['by']) && $vars['get']['by'] == 'name')
		{
			$ticket      = new ticket();
			$ticket_data = $ticket->db_query('SELECT * FROM ticket WHERE pas_full_name = ' . '\'' . $vars['get']['value'] . '\'');
			page_controller::getView('main', ['page' => 'users_registration_tables.php', 'user_data' => $ticket_data]);
			return;
		}
		if(isset($vars['get']['by']) && $vars['get']['by'] == 'doc')
		{
			$ticket      = new ticket();
			$ticket_data = $ticket->db_query('SELECT * FROM ticket WHERE document = ' . '\'' . $vars['get']['value'] . '\'');
			page_controller::getView('main', ['page' => 'users_registration_tables.php', 'ticket_data' => $ticket_data]);
			return;
		}
		if(isset($vars['get']['special_id']))
		{
			$ticket     = new ticket();
			$user_order = $ticket->db_query('SELECT * FROM user_order WHERE special_id = ' . $vars['get']['special_id']);
    		$query = 'SELECT * FROM ticket WHERE id in (';
    		for($i=0; $i<count($user_order); $i++)
    		{
        		$end = isset($user_order[($i + 1)]['ticket_id']) ? ', ' : ')';
        		$query .= $user_order[$i]['ticket_id'] . $end;
    		}
    		$tickets = $ticket->db_query($query);
			page_controller::getView('main', ['page' => 'users_registration_tables.php', 'tickets' => $tickets]);
			return;
		}
		page_controller::getView('main', ['page' => 'users_registration_tables.php']);
	}

	public function flight_table($vars)
	{
		$flight      = new flight();
		$city        = new city();
		$plane       = new plane();
		$carrier     = new carrier();
		$time        = $GLOBALS['thisDate']['hours'] . ':' . $GLOBALS['thisDate']['minutes'] . ':' . $GLOBALS['thisDate']['seconds'];
		$flight_data = $flight->db_query('SELECT IATA1, IATA2, planeid, date_d, date_a, carrier_id, id from flight where id not in (select flight_id from transfer) AND date_d BETWEEN "'. $GLOBALS['date'] . ' ' . $time . '" AND "' . $GLOBALS['thisDate']['year'] . '-' . $GLOBALS['mon'] . '-' . $GLOBALS['day']++ . ' ' . $time . '" union all (select IATA1, IATA2, planeid, date_dep, date_arrival, carrier_id, id from transfer WHERE date_dep BETWEEN "'. $GLOBALS['date'] . ' ' . $time . '" AND "' . $GLOBALS['thisDate']['year'] . '-' . $GLOBALS['mon'] . '-' . $GLOBALS['day']++ . ' ' . $time . '") order by date_d');
		$user_tickets = $flight->db_query('SELECT * FROM ticket WHERE owner = ' . $_SESSION['uid']);
		$query = 'SELECT * FROM flight WHERE id in (';
    	for($i=0; $i<count($user_tickets); $i++)
    	{
        	$end = isset($user_tickets[($i + 1)]['flight']) ? ', ' : ')';
        	$query .= $user_tickets[$i]['flight'] . $end;
    	}
		$result = $flight->db_query($query);
		for($i=0; $i < count($result); $i++)
  		{
  			for($j=0; $j < count($user_tickets); $j++)
  			{
  				if($key = array_search($result[$i]['id'], $user_tickets[$j]))
  				{
  					$result[$i]['tickets'][] = $user_tickets[$j]['id'];
  				}
  			}
  		}
  		for($i=0; $i < count($result); $i++)
  		{
  			$result[$i]['IATA1']   = $city->get_by_id((int)$result[$i]['IATA1'])[0]['name'];
			$result[$i]['IATA2']   = $city->get_by_id((int)$result[$i]['IATA2'])[0]['name'];
			$result[$i]['plane']   = $plane->get_by_id($result[$i]['planeid'])[0]['name'];
			$result[$i]['carrier'] = $carrier->get_by_id($result[$i]['carrier_id'])[0]['full_name'];
  		}
  		for($i=0; $i < count($flight_data); $i++)
  		{
  			$flight_data[$i]['IATA1']   = $city->get_by_id((int)$flight_data[$i]['IATA1'])[0]['name'];
			$flight_data[$i]['IATA2']   = $city->get_by_id((int)$flight_data[$i]['IATA2'])[0]['name'];
			$flight_data[$i]['plane']   = $plane->get_by_id($flight_data[$i]['planeid'])[0]['name'];
			$flight_data[$i]['carrier'] = $carrier->get_by_id($flight_data[$i]['carrier_id'])[0]['full_name'];
  		}
		page_controller::getView('main', ['page' => 'flight_table.php', 'flights' => $flight_data, 'user_flights' => $result]);
	}

	public function flight_transfers($vars)
	{
		$transfer      = new transfer();
		$flight        = new flight();
		$city          = new city();
		$plane         = new plane();
		$carrier       = new carrier();
		$transfer_data = $transfer->db_query('SELECT * FROM transfer WHERE flight_id = ' . $vars['get']['fid']);
		for($i=0; $i < count($transfer_data); $i++)
		{
			$transfer_data[$i]['IATA1']   = $city->get_by_id((int)$transfer_data[$i]['IATA1'])[0]['name'];
			$transfer_data[$i]['IATA2']   = $city->get_by_id((int)$transfer_data[$i]['IATA2'])[0]['name'];
			$transfer_data[$i]['plane']   = $plane->get_by_id((int)$transfer_data[$i]['planeid'])[0]['name'];
			$transfer_data[$i]['carrier'] = $carrier->get_by_id((int)$transfer_data[$i]['carrier_id'])[0]['full_name'];
		}
		//var_dump($transfer_data);die;
		page_controller::getView('main', ['page' => 'flight_transfer.php', 'transfer_data' => $transfer_data]);
	}

	public function flight_reg($vars)
	{
		$flight      = new flight();
		$city        = new city();
		$plane       = new plane();
		$carrier     = new carrier();
		$flight_data = $flight->get_by_id($vars['get']['fid']);
		$transfers   = $flight->db_query('SELECT * FROM transfer WHERE flight_id = ' . $vars['get']['fid']);
		$ticket      = $flight->db_query('SELECT * FROM ticket WHERE id = ' . $vars['get']['tid']);

		$flight_data[0]['IATA1']   = $city->get_by_id((int)$flight_data[0]['IATA1'])[0]['name'];
		$flight_data[0]['IATA2']   = $city->get_by_id((int)$flight_data[0]['IATA2'])[0]['name'];
		$flight_data[0]['plane']   = $plane->get_by_id($flight_data[0]['planeid'])[0]['name'];
		$flight_data[0]['carrier'] = $carrier->get_by_id($flight_data[0]['carrier_id'])[0]['full_name'];

		for($i=0; $i < count($transfers); $i++)
		{
			$transfers[$i]['IATA1']   = $city->get_by_id((int)$transfers[$i]['IATA1'])[0]['name'];
			$transfers[$i]['IATA2']   = $city->get_by_id((int)$transfers[$i]['IATA2'])[0]['name'];
			$transfers[$i]['plane']   = $plane->get_by_id((int)$transfers[$i]['planeid'])[0]['name'];
			$transfers[$i]['carrier'] = $carrier->get_by_id((int)$transfers[$i]['carrier_id'])[0]['full_name'];
		}
		page_controller::getView('main', ['page' => 'flight_reg.php', 'flight_data' => $flight_data, 'transfer_data' => $transfers, 'ticket' => $ticket, 'flight_obj' => $flight]);
	}
}

page_controller::do();
