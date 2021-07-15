
<?php
require_once './classes/active_record.php';

class user extends active_record {
		
public $id;
public $first_name;
public $second_name;
public $last_name;
public $document_sn;
public $age;
public $sex;
public $role;
public $email;
public $login;
public $pas;

	public function get_by_id($id)
	{
		return $this->find_by_id($id);
	}

}