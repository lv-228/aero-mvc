
<?php
require_once './classes/active_record.php';

class city extends active_record {
		
public $id;
public $IATA;
public $name;

	public function get_by_id($id)
	{
		return $this->find_by_id($id);
	}

	public function get_id_by_iata($iata)
	{
		return $this->db_query('SELECT id FROM city WHERE IATA = \'' . $iata . '\'');
	}

}