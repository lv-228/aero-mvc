<?php
require_once 'controller.php';
require_once './models/carrier_model.php';
require_once './models/city_model.php';
require_once './models/ticket_model.php';
require_once './models/plane_model.php';
require_once './models/flight_model.php';
require_once './classes/fillingdb.php';
/**
 * 
 */
class ticket_controller extends controller
{
	public static $get =
	[
		'main' => 
		[
			'departure_date' => false,
			'origin'         => false,
			'destination'    => false,
			'non_stop'       => false,
			'adults'         => false,
			'class'          => false
		]
	];

	public static $post = 
	[
		'buy' => 
		[
			'flight_id',
			'class',
			'count',
			'tickets',
		],
		'prepare_order' =>
		[
			'tickets',
			'flight_id',
			'count',
			'class',
		],
		'ticket_reg' =>
		[
			'sit',
			'ticket_id',
			'transfer',
			'transfer_id',
			'reg_doc'
		],
		'invoice' =>
		[
			'flight_id',
			'class',
			'count',
			'tickets'
		]
	];

	public function main($vars)
	{
		//var_dump($_SESSION);

		$data = array();

	if(isset($vars['get']['departure_date']) && strtotime($vars['get']['departure_date']) < $GLOBALS['date'])
        {
            $_SESSION['message'] = ['Departure date error', 'warning'];
        }

        $vars_bool = isset($vars['get']['origin'], $vars['get']['destination'], $vars['get']['departure_date'], $vars['get']['non_stop'], $vars['get']['adults'], $vars['get']['class']);

        if($vars_bool && $vars['get']['non_stop'] === 'true')
        {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $limit = 20;
            $offset = $limit * ($page - 1);
            $query_data = $vars['get']['departure_date'] . ' ' . $GLOBALS['thisDate']['hours'] . ':' .  $GLOBALS['thisDate']['minutes'] . ':' . $GLOBALS['thisDate']['seconds'];
            $compare = '> ? AND date_d < ' . '"' . date('Y-m-d', strtotime($vars['get']['departure_date'] . ' +2 days')) . '"';
            $city = new city();
            $iata1 = $city->get_id_by_iata($vars['get']['origin']);
            $iata2 = $city->get_id_by_iata($vars['get']['destination']);

            $query = $vars['get']['class'] == 'eco' ? 'SELECT * FROM flight WHERE free_sits >= ? AND iata1 = ? AND iata2 = ? AND date_d ' . $compare . ' AND id IN (SELECT flight.id FROM flight LEFT JOIN transfer ON flight.id = transfer.flight_id WHERE transfer.flight_id is NULL) AND id not IN (case when not exists(select flight from ticket where type = 15 group by flight having count(*) >= 144) then 0 end) order by date_d LIMIT ? OFFSET ?' : 'SELECT * FROM flight WHERE free_sits >= ? AND iata1 = ? AND iata2 = ? AND date_d ' . $compare . ' AND id IN (SELECT flight.id FROM flight LEFT JOIN transfer ON flight.id = transfer.flight_id WHERE transfer.flight_id is NULL) AND id not IN (case when not exists(select flight from ticket where type = 16 group by flight having count(*) >= 18) then 0 end) order by date_d LIMIT ? OFFSET ?';

            $query_array = [
            	['var' => $vars['get']['adults'], 'type' => 'INT'],
            	['var' => $iata1[0]['id'], 'type' => 'INT'],
            	['var' => $iata2[0]['id'], 'type' => 'INT'],
            	['var' => $query_data, 'type' => 'STR'],
            	['var' => $limit, 'type' => 'INT'],
            	['var' => $offset, 'type' => 'INT']
            ];

            $result = $city->db_prepare_query($query, $query_array);
            $ticket = new ticket();

            $data['result']     = $ticket->parse_find_ticket_result($result);
            $data['i1']         = $vars['get']['origin'];
            $data['i2']         = $vars['get']['destination'];
            $data['ticket_obj'] = $ticket;
	    //var_dump($city->db_prepare_query($query, $query_array));die;
        }

        if($vars_bool && $vars['get']['non_stop'] === 'false')
        {
            $city = new city();
            $iata1 = $city->get_id_by_iata($vars['get']['origin']);
            $iata2 = $city->get_id_by_iata($vars['get']['destination']);
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $limit = 20;
            $offset = $limit * ($page - 1);
            $query_data = '';
            $compare = '';
            $query_data = $vars['get']['departure_date'] . ' ' . $GLOBALS['thisDate']['hours'] . ':' .  $GLOBALS['thisDate']['minutes'] . ':' . $GLOBALS['thisDate']['seconds'];
            $compare = '> ? AND date_d < ' . '"' . date('Y-m-d', strtotime($vars['get']['departure_date'] . ' +2 days')) . '"';

            $query = $vars['get']['class'] == 'eco' ? 'SELECT * FROM flight WHERE free_sits >= ? AND iata1 = ? AND iata2 = ? AND date_d ' . $compare . ' AND id not IN (case when not exists(select flight from ticket where type = 15 group by flight having count(*) >= 144) then 0 end) order by date_d LIMIT ? OFFSET ?' : 'SELECT * FROM flight WHERE free_sits >= ? AND iata1 = ? AND iata2 = ? AND date_d ' . $compare . ' AND id not IN (case when not exists(select flight from ticket where type = 16 group by flight having count(*) >= 18) then 0 end) order by date_d LIMIT ? OFFSET ?';

            $query_array = [
            	['var' => $vars['get']['adults'], 'type' => 'INT'],
            	['var' => $iata1[0]['id'], 'type' => 'INT'],
            	['var' => $iata2[0]['id'], 'type' => 'INT'],
            	['var' => $query_data, 'type' => 'STR'],
            	['var' => $limit, 'type' => 'INT'],
            	['var' => $offset, 'type' => 'INT']
            ];
            
            $result = $city->db_prepare_query($query, $query_array);

            $ticket  = new ticket();

            $data['result']      = $ticket->parse_find_ticket_result($result);
            $data['i1']          = $vars['get']['origin'];
            $data['i2']          = $vars['get']['destination'];
            $data['ticket_obj']  = $ticket;
        }

		ticket_controller::getView('main', ['page' => 'find_tickets.php', 'data' => $data]);
	}

	public function fill_db()
	{
		$fillingDB = new fillingDB('10.159.12.23:3306', 'script', 'pedagog321!', 'avia');

		$data = $fillingDB->getAmadeusAPI('HKG', 'SIN', '2021-05-21', 'WLdB0GawficTIfYLoF9IhHqf2L28');

		var_dump(count($data['data']));
		//var_dump($data);
		$fillingDB->insertApiFlightsData($data = isset($data['errors']) ? die ('Ошибка! ' . $data['errors'][0]['title']) : $data);
	}

	public function print_doc_form()
	{
		$data = json_decode(file_get_contents('php://input'), true);

		$liswitcher = ['<li><a href="#">', '</a></li>'];
		$answer = ['liform' => '', 'liswitcher' => ''];
		for($i = 0; $i < $data['cnt']; $i++)
		{
			$answer['liform'] .= '<li><div class="uk-margin"><label class="uk-form-label">Document</label><input placeholder="____ _______" form="ticket_form' . $data["flight_id"] . '" id="doc_reg" class="uk-input uk-form-blank uk-form-width-small" type="text" name=\'' . 'tickets[' . $i . ']' . '[regdoc]' . '\' maxlength="11" required=""></div><div class="uk-margin"><label class="uk-form-label">First name</label><input name=\'' . 'tickets[' . $i . ']' . '[fname]' . '\' form="ticket_form' . $data["flight_id"] . '" class="uk-input uk-form-blank uk-form-width-large" type="text" placeholder="__________" required=""></div><div class="uk-margin"><label class="uk-form-label">Second name</label><input form="ticket_form' . $data["flight_id"] . '" class="uk-input uk-form-blank uk-form-width-large" type="text" name=\'' . 'tickets[' . $i . ']' . '[sname]' . '\' placeholder="__________" required=""></div><div class="uk-margin"><label class="uk-form-label">Last name</label><input form="ticket_form' . $data["flight_id"] . '" class="uk-input uk-form-blank uk-form-width-large" type="text" name=\'' . 'tickets[' . $i . ']' . '[lname]' . '\' placeholder="__________" required=""></div></li>';
			$answer['liswitcher'] .= $liswitcher[0] . 'Ticket ' . ($i + 1) . $liswitcher[1];
		}

		echo stripslashes(json_encode($answer, JSON_HEX_QUOT));
	}

	public function prepare_order($vars)
	{
		//var_dump($vars);
		$flight = new flight();
		$flight_data = $flight->get_by_id($vars['post']['flight_id']);
		$price = $flight_data[0]['price'] * 75;
		//var_dump($price);
	}

	public function buy($vars)
	{
		//var_dump($vars);die;
		$count = count($vars['post']['tickets']);
		$ticket = new ticket();

		if($vars['post']['class'] == 'eco')
		{
			$ticket_count = $ticket->db_query('SELECT count(*) FROM ticket WHERE type = 15 AND flight = ' . (int)$vars['post']['flight_id']);

			if($ticket_count[0]['count(*)'] + $count > 144)
			{
				$response = 'Not enough econom tickets!';
				return $response;
			}
		}

		if($vars['post']['class'] == 'bui')
		{
			$ticket_count = $ticket->db_query('SELECT count(*) FROM ticket WHERE type = 16 AND flight = ' . (int)$vars['post']['flight_id']);
			if($ticket_count[0]['count(*)'] + $count > 18)
			{
				$response = 'Not enough business tickets!';
				return $response;
			}
		}

		$common = [ ['var' => $_SESSION['uid'], 'type' => 'INT'], ['var' => 0 ,'type' => 'INT'], ['var' => $vars['post']['class'] == 'bui' ? 16 : 15 , 'type' => 'INT'], [ 'var' => $vars['post']['flight_id'], 'type' => 'INT'] ];

		//var_dump($_SESSION);die;
		for($i=0; $i<$count; $i++)
		{
			$passanger =[ ['var' => (string)$vars['post']['tickets'][$i]['regdoc'], 'type' => 'STR'], ['var' => $vars['post']['tickets'][$i]['fname'] . ' ' . $vars['post']['tickets'][$i]['sname'] . ' ' . $vars['post']['tickets'][$i]['lname'], 'type' => 'STR']];

			$connector = $ticket->create_and_return_connector();
			$prepare   = $connector->prepare('INSERT INTO ticket values (null, ?, ?, ?, ?, null, ?, ?)');
			$values_query_array = array_merge($common, $passanger);

			$ticket::bind_param($prepare, $values_query_array);

			$prepare->execute();
			
			$id = $connector->lastInsertId();
			$order = $connector->query('INSERT INTO user_order VALUES (null, ' . $common[0]['var'] . ', ' . time() . $common[0]['var'] . ', ' . $id . ')');
			if($connector->query('SELECT * FROM ticket WHERE id = ' . $id))
    		{
        		if($transfers = $connector->query('SELECT id FROM transfer where flight_id = ' . $vars['post']['flight_id']))
        		{
        			$transfers = $transfers->fetchAll();
        			$prepare_transfer = $connector->prepare('INSERT INTO transfer_ticket values(null, ?, ?,  null, null)');
            		for($j=0; $j < count($transfers); $j++)
            		{
            			$trans_params = [['var' => $transfers[$j]['id'], 'type' => 'INT'], ['var' => $id, 'type' => 'INT']];
            			$ticket::bind_param($prepare_transfer, $trans_params);
            			$prepare_transfer->execute();
            		}
        		}
        		$connector->query('UPDATE flight SET free_sits = free_sits - 1 WHERE id = ' . $vars['post']['flight_id'] . ' AND free_sits != 0');
        		$tickets = $ticket->db_query('SELECT id, sit FROM ticket WHERE owner = ' . $_SESSION['uid']);
        		$_SESSION['u_tickets'] = $tickets;
        		$_SESSION['message']   = ['Ticket(s) was buy', 'success'];
    		}
    		else
    		{
        		$_SESSION['message'] = ['Error ticket buy', 'danger'];
    		}
		}
		for($j=0; $j<count($_SESSION['u_tickets']); $j++)
        {
        	$_SESSION['u_tickets'][$j]['transfers'] = $ticket->db_query('SELECT * FROM transfer_ticket WHERE ticket_id = ' . $_SESSION['u_tickets'][$j]['id']);
        }
        header('Location:index.php?ticket=main');
	}

	public function ticket_reg($vars)
	{
		if($vars['post']['transfer'] == 'true')
		{
			$ticket = new ticket();
			$ticket->db_query('UPDATE transfer_ticket SET sit = ' . '\'' . $vars['post']['sit'] . '\'' . ', document = ' . '\'' . $vars['post']['reg_doc'] . '\'' . ' WHERE ticket_id = ' . $vars['post']['ticket_id'] . ' AND transfer_id = ' . $vars['post']['transfer_id']);
		}
		if($vars['post']['transfer'] == 'false')
		{
			$ticket = new ticket();
			$ticket->db_query('UPDATE ticket SET sit = ' . '\'' . $vars['post']['sit'] . '\'' . ', document = ' . '\'' . $vars['post']['reg_doc'] . '\'' . ' WHERE id = ' . $vars['post']['ticket_id']);
		}
		header('Location:' . $_SERVER['HTTP_REFERER']);
	}

	public function invoice($vars)
	{
		ticket_controller::getView('main', ['page' => 'invoice.php', 'data' => $vars['post']]);
	}
}

ticket_controller::do();
