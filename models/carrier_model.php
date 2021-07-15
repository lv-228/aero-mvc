
<?php
require_once './classes/active_record.php';

class carrier extends active_record {
		
public $id;
public $carriers_id;
public $full_name;

	public function get_by_id($id)
	{
		return $this->find_by_id($id);
	}

}