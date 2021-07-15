
<?php
require_once './classes/active_record.php';

class transfer extends active_record {
		
public $id;
public $flight_id;
public $IATA1;
public $IATA2;
public $date_dep;
public $date_arrival;
public $planeid;
public $duration;
public $carrier_id;

	public function get_by_id($id)
	{
		return $this->find_by_id($id);
	}

}