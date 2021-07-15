<?php
require_once 'db.php';

/**
 * 
 */
class model_generator
{

	public function create_model($table_name)
	{
		$db = db::getInstance();
		$fields = $db->get_table_columns($table_name);
		$fc = fopen('models/' . $table_name . '_model.php', 'w') or die('Error! Cant create file');
		$in_file = '
<?php
require_once \'./classes/active_record.php\';

class ' . $table_name . ' extends active_record {
		';
		for($i=0; $i < count($fields); $i++)
		{
			$in_file .= PHP_EOL . 'public $' . $fields[$i] . ';';
		}
		$in_file .= PHP_EOL . '
	public function get_by_id($id)
	{
		return $this->find_by_id($id);
	}
';
		$in_file .= PHP_EOL . '}';
		fwrite($fc, $in_file);
		fclose($fc);
	}
}