<?php
require_once 'controller.php';
require_once './classes/model_generator.php';

/**
 * 
 */
class model_create extends controller
{
	public static $get = 
	[
		'get_table' => ['table_name']
	];

	public function get_table(array $data)
	{
		$table_data = array();
		$model_generator = new model_generator();
		echo 'table: ' . $data['get']['table_name'] . '<br>';
		$model_generator->create_model($data['get']['table_name']);
	}
}

model_create::do();