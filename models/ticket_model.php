
<?php
require_once './classes/active_record.php';
require_once 'city_model.php';
require_once 'plane_model.php';
require_once 'carrier_model.php';

class ticket extends active_record {
		
public $id;
public $owner;
public $reservation;
public $type;
public $flight;
public $sit;
public $document;
public $pas_full_name;

	public function get_by_id($id)
	{
		return $this->find_by_id($id);
	}

	public function parse_find_ticket_result($result)
	{
		$answer   = array();
		$city     = new city();
		$plane    = new plane();
		$carrier  = new carrier();
		$i = 0;
		foreach ($result as $key => $value)
		{
			$answer[$i]['id']       = $value['id'];
			$answer[$i]['i1']       = $city->get_by_id($value['IATA1'])[0]['IATA'];
			$answer[$i]['i2']       = $city->get_by_id($value['IATA2'])[0]['IATA'];
			$answer[$i]['price']    = $value['price'];
			$answer[$i]['plane']    = $plane->get_by_id($value['planeid'])[0]['name'];
			$answer[$i]['carrier']  = $carrier->get_by_id($value['carrier_id'])[0]['full_name'];
			$answer[$i]['date_d']   = $value['date_d'];
			$answer[$i]['date_a']   = $value['date_a'];
			$answer[$i]['duration'] = $value['duration'];
			$answer[$i]['sits']     = $value['free_sits'];
			$i++;
		}
		return $answer;
	}

	public function parse_find_transfers($result)
	{
		$answer   = array();
		$city     = new city();
		$plane    = new plane();
		$carrier  = new carrier();
		$i = 0;
		foreach ($result as $key => $value)
		{
			$answer[$i]['id']        = $value['id'];
			$answer[$i]['flight_id'] = $value['flight_id'];
			$answer[$i]['i1']        = $city->get_by_id($value['IATA1'])[0]['IATA'];
			$answer[$i]['i2']        = $city->get_by_id($value['IATA2'])[0]['IATA'];
			$answer[$i]['plane']     = $plane->get_by_id($value['planeid'])[0]['name'];
			$answer[$i]['carrier']   = $carrier->get_by_id($value['carrier_id'])[0]['full_name'];
			$answer[$i]['date_d']    = $value['date_dep'];
			$answer[$i]['date_a']    = $value['date_arrival'];
			$answer[$i]['duration']  = $value['duration'];
			$i++;
		}
		return $answer;
	}

	public function get_transfers($flight_id)
	{
		return $this->db_query('SELECT * FROM transfer WHERE flight_id = ' . $flight_id);
	}

	public function check_free_tickets($f_id, $type)
	{
		if($type == 15)
		{
			$ticket_count = $this->db_query('SELECT count(*) FROM ticket WHERE type = 15 AND flight = ' . (int)$f_id);
			if($ticket_count[0]['count(*)'] >= 144)
			{
				$response = 'All econom tickets was buying!';
				return $response;
			}
		}

		if($type == 16)
		{
			$ticket_count = $this->db_query('SELECT count(*) FROM ticket WHERE type = 16 AND flight = ' . (int)$f_id);

			if($ticket_count[0]['count(*)'] >= 18)
			{
				$response = 'All business tickets was buying!';
				return $response;
			}
		}

		return true;
	}

	public function check_registration_ticket($ticket_id)
	{
		$transfer_ticket = $this->db_query('SELECT transfer_id, sit, document FROM transfer_ticket WHERE ticket_id = ' . $ticket_id);
		if(!$transfer_ticket)
		{
			$ticket = $this->db_query('SELECT sit, document FROM ticket WHERE id = ' . $ticket_id);
			return $ticket;
		}
		return $transfer_ticket;
	}
}