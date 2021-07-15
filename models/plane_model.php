<?php
require_once './classes/active_record.php';

class plane extends active_record {
		
public $id;
public $planeid;
public $name;
public $sits;

	public function get_by_id($id)
	{
		return $this->find_by_id($id);
	}

}