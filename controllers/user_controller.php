<?php

require_once 'controller.php';
require_once './classes/user.php';
require_once './models/user_model.php';
require_once './models/ticket_model.php';
require_once './models/city_model.php';
require_once './models/plane_model.php';
require_once './models/carrier_model.php';

class user_controller extends controller
{
	public static $post = 
	[
		'auth'    => ['login', 'password'],
		'registration' =>
		[
			'login',
			'email',
			'first_name',
			'second_name',
			'last_name',
			'pas',
			'reppass',
			'age',
			'sex'
		],
		'change_role' =>
		[
			'uid',
			'role'
		]
	];

	public static $get =
	[
		'profile' => ['uid'],
	];

	public function auth($vars)
	{
		//$url = explode('&', $_SERVER['HTTP_REFERER']);
		$user = user_class::getInstance();
		$user->set_hash('sha512');
		$user->set_table('user');
		$user->set_login_field('login');
		$user->set_pass_field('pas');
		$user->set_role_field('role');
		$db_info = $user->authentication($vars['post']['login'], $vars['post']['password'], ['login' => $vars['post']['login']]);
		if(!$db_info)
		{
			$_SESSION['message'] = ['msg' => 'Error! Wrong password or login', 'type' => 'danger'];
			header('Location:index.php?ticket=main');
			return;
		}
		$_SESSION['message'] = ['msg' => 'Login succsess', 'type' => 'warning'];
		header('Location:index.php?ticket=main');
		return;
	}

	public function un_auth()
	{
		user_controller::unset_array($_SESSION);
		header('Location: index.php?ticket=main');
		return;
	}

	public function profile($vars)
	{
		$user         = new user();
		$ticket       = new ticket();
		$city         = new city();
		$plane        = new plane();
		$carrier      = new carrier();
		$user_data    = $user->get_by_id($_SESSION['uid']);
		$user_tickets = $ticket->db_query('SELECT flight.*, ticket.* from flight inner join ticket on flight.id = ticket.flight where ticket.owner = ' . $_SESSION['uid']);
		$data = ['u_data' => $user_data, 'u_tickets' => $user_tickets, 'ticket_obj' => $ticket, 'city_obj' => $city, 'plane_obj' => $plane, 'carrier_obj' => $carrier];
		user_controller::getView('main', ['page' => 'user_page.php', 'data' => $data]);
	}

	public function registration($vars)
	{
		$user      = new user();
		$connector = $user->create_and_return_connector();
		$vars['post']['pas'] = hash('sha512', $vars['post']['pas']);
		$vars['post']['role'] = 1;
		unset($vars['post']['reppass']);
		$query     = user_class::generate_query('user', $vars['post']);
		//var_dump($query);die;
		$result    = $connector->query($query);
		if(!empty($user->get_by_id($connector->lastInsertId())))
			$_SESSION['message'] = ['msg' => 'Registration succsess', 'type' => 'warning'];
		else
			$_SESSION['message'] = ['msg' => 'Registration failed! Login or email exists', 'type' => 'warning'];
		header('Location:' . $_SERVER['HTTP_REFERER']);
	}

	public function change_role($vars)
	{
		$user = new user();
		$user->db_query('UPDATE user SET role = ' . $vars['post']['role'] . ' WHERE id = ' . $vars['post']['uid']);
		header('Location:' . $_SERVER['HTTP_REFERER']);
	}
}

user_controller::do();
