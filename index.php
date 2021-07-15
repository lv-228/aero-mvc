<?php
require_once 'classes/route.php';
require_once 'constans.php';

    $thisDate = getdate();
    $mon = $thisDate['mon'] < 10 ? 0 . $thisDate['mon'] : $thisDate['mon'];
    $day = $thisDate['mday'] < 10 ? 0 . $thisDate['mday'] : $thisDate['mday'];
    $date = $thisDate['year'] . '-' . $mon . '-' . $day;
    $payment_form = true;
    
try
{
	route::parsePath();
}
catch(Exception $e)
{
	if($e->getMessage() == 'Error 404')
		require_once '404.php';
	elseif($e->getMessage() == 'Error 401')
		require_once '401.php';
	elseif($e->getMessage() == 'Error 400')
		require_once '400.php';
		elseif($e->getMessage() == 'Error 403')
		require_once '403.php';
	else
		echo $e->getMessage();
}
