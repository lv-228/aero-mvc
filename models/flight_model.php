
<?php
require_once './classes/active_record.php';

class flight extends active_record {
		
public $id;
public $IATA1;
public $IATA2;
public $price;
public $planeid;
public $free_sits;
public $date_d;
public $date_a;
public $duration;
public $carrier_id;

	public function get_by_id($id)
	{
		return $this->find_by_id($id);
	}

}