<?php
require_once 'controller.php';
require './models/carrier_model.php';
/**
 * 
 */
class testAction extends controller
{

	// public static $get = 
	// [
	// 	'method_name' => ['var']
	// ];

	public function test()
	{
		$qwerty = ['test' => 'qwerty', 'test2' => 12345];
		testAction::getView('testView', $qwerty);
	}

	public function test_model()
	{
		
	}
}

testAction::do();