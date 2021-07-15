
<?php
require_once './classes/active_record.php';

class order_payment extends active_record {
		
public $id;
public $order_id;
public $cost;
public $payment;

	public function get_by_id($id)
	{
		return $this->find_by_id($id);
	}

}