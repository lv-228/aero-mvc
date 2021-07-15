<?php

class file_system
{
	public $uploadfile;

	public function save_file()
	{
		if($_FILES['usrfile']['error'] == 2)
		{
			$_SESSION['message'] = ['Error! Large file size!', 'danger'];
			return false;
		}
		$uploadfile = self::$uploadfile . preg_replace('/\s+/', '', basename(time() . $_FILES['usrfile']['name']));
		if(!move_uploaded_file($_FILES['usrfile']['tmp_name'], $uploadfile))
		{
			throw new Exception('Error! Can\'t download file!', 1);
		}
	}

	public function check_filename($file_name)
	{
		if(stristr($file_name, 'php'))
		{
			throw new Exception('Error! Wrong file extension!', 1);
		}
		return true;
	}

	public function check_extension($file_name,array $permit_extensions)
	{
		$extension = stristr($file_name, '.');
		$extension = str_replace('.', '', $extension);
		$new_name = stristr($file_name, '.', true);
		if(in_array($extension, $permit_extensions))
		{
			return false;
		}
		if(!$new_name)
			throw new Exception('Error! File extension not found!', 1);
		else
			return $new_name . $extension;
	}
}