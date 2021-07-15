<?php

/**
 * 
 */
class view
{
	
	public static $sitsCoords;
	public static $pages;

	function __construct()
	{
			
	}

	public static function send_message($message, $type)
	{
        echo '<script>UIkit.notification({message: "' . $message . '", status: "' . $type . '"})</script>';
	}

	public static function printSit(array $coords, $sit)
	{
		echo '<area onclick="getSit(this)" shape="rect" style="cursor: pointer;" coords="' . $coords['x'] . ',' . $coords['y'] . ',' . $coords['xx'] . ',' . $coords['yy'] . '" alt="Sun" sit="' . $sit . '">';
	}

	public static function printRectangleSits(array $coords, $numRow, $numColumn, $offsetX, $offsetY, array $sitLetters,  $sit,array $occupedPlaces = [], $empty=false)
	{
		for($j=0; $j < $numRow; $j++)
		{
			$offy = $j * $offsetY;
			for($i=0; $i < $numColumn; $i++)
			{
				if(!in_array($sitLetters[$i] . $sit, $occupedPlaces) && $i !== $empty)
				{
					$offx = $i * $offsetX;
					$buffArray = [ 'x' => $coords['x'] + $offx, 'xx' => $coords['xx'] + $offx, 'y' =>  $coords['y'] + $offy, 'yy' => $coords['yy'] + $offy];
					view::printSit($buffArray, $sitLetters[$i] . $sit);
				}
			}
			$sit++;
		}
	}

	public static function checkAndPrintSits($occupedPlaces)
	{
    	view::printRectangleSits([ 'x' => '538', 'y' => '214', 'xx' => '555', 'yy' => '234'], 5, 3, 18, 26, ['A', 'B', 'C'], 1, $occupedPlaces, 1);
    	view::printRectangleSits([ 'x' => '538', 'y' => '351', 'xx' => '555', 'yy' => '371'], 6, 3, 18, 26, ['A', 'B', 'C'], 6, $occupedPlaces);
    	view::printRectangleSits([ 'x' => '538', 'y' => '524', 'xx' => '555', 'yy' => '544'], 1, 3, 18, 26, ['A', 'B', 'C'], 12, $occupedPlaces);
    	view::printRectangleSits([ 'x' => '538', 'y' => '564', 'xx' => '555', 'yy' => '584'], 1, 3, 18, 26, ['A', 'B', 'C'], 14, $occupedPlaces);
    	view::printRectangleSits([ 'x' => '536', 'y' => '592', 'xx' => '553', 'yy' => '612'], 15, 3, 18, 26, ['A', 'B', 'C'], 16, $occupedPlaces);
    	view::printRectangleSits([ 'x' => '539', 'y' => '977', 'xx' => '556', 'yy' => '997'], 1, 3, 18, 26, ['A', 'B', 'C'], 31, $occupedPlaces);
    	view::printRectangleSits([ 'x' => '607', 'y' => '239', 'xx' => '624', 'yy' => '259'], 4, 3, 18, 26, ['D', 'E', 'F'], 2,$occupedPlaces, 1);
    	view::printRectangleSits([ 'x' => '607', 'y' => '351', 'xx' => '624', 'yy' => '371'], 6, 3, 18, 26, ['D', 'E', 'F'], 6, $occupedPlaces);
    	view::printRectangleSits([ 'x' => '607', 'y' => '524', 'xx' => '624', 'yy' => '544'], 1, 3, 18, 26, ['D', 'E', 'F'], 12, $occupedPlaces);
    	view::printRectangleSits([ 'x' => '607', 'y' => '564', 'xx' => '624', 'yy' => '584'], 1, 3, 18, 26, ['D', 'E', 'F'], 14, $occupedPlaces);
    	view::printRectangleSits([ 'x' => '607', 'y' => '592', 'xx' => '624', 'yy' => '612'], 15, 3, 18, 26, ['D', 'E', 'F'], 16, $occupedPlaces);
    	view::printRectangleSits([ 'x' => '606', 'y' => '977', 'xx' => '623', 'yy' => '997'], 1, 3, 18, 26, ['D', 'E', 'F'], 31, $occupedPlaces);
	}

	public static function printBui($occupedPlaces)
	{
		view::printRectangleSits([ 'x' => '538', 'y' => '214', 'xx' => '555', 'yy' => '234'], 5, 3, 18, 26, ['A', 'B', 'C'], 1, $occupedPlaces, 1);
		view::printRectangleSits([ 'x' => '607', 'y' => '239', 'xx' => '624', 'yy' => '259'], 4, 3, 18, 26, ['D', 'E', 'F'], 2,$occupedPlaces, 1);
	}

	public static function printEco($occupedPlaces)
	{
		view::printRectangleSits([ 'x' => '538', 'y' => '351', 'xx' => '555', 'yy' => '371'], 6, 3, 18, 26, ['A', 'B', 'C'], 6, $occupedPlaces);
    	view::printRectangleSits([ 'x' => '538', 'y' => '524', 'xx' => '555', 'yy' => '544'], 1, 3, 18, 26, ['A', 'B', 'C'], 12, $occupedPlaces);
    	view::printRectangleSits([ 'x' => '538', 'y' => '564', 'xx' => '555', 'yy' => '584'], 1, 3, 18, 26, ['A', 'B', 'C'], 14, $occupedPlaces);
    	view::printRectangleSits([ 'x' => '536', 'y' => '592', 'xx' => '553', 'yy' => '612'], 15, 3, 18, 26, ['A', 'B', 'C'], 16, $occupedPlaces);
    	view::printRectangleSits([ 'x' => '539', 'y' => '977', 'xx' => '556', 'yy' => '997'], 1, 3, 18, 26, ['A', 'B', 'C'], 31, $occupedPlaces);
    	view::printRectangleSits([ 'x' => '607', 'y' => '351', 'xx' => '624', 'yy' => '371'], 6, 3, 18, 26, ['D', 'E', 'F'], 6, $occupedPlaces);
    	view::printRectangleSits([ 'x' => '607', 'y' => '524', 'xx' => '624', 'yy' => '544'], 1, 3, 18, 26, ['D', 'E', 'F'], 12, $occupedPlaces);
    	view::printRectangleSits([ 'x' => '607', 'y' => '564', 'xx' => '624', 'yy' => '584'], 1, 3, 18, 26, ['D', 'E', 'F'], 14, $occupedPlaces);
    	view::printRectangleSits([ 'x' => '607', 'y' => '592', 'xx' => '624', 'yy' => '612'], 15, 3, 18, 26, ['D', 'E', 'F'], 16, $occupedPlaces);
    	view::printRectangleSits([ 'x' => '606', 'y' => '977', 'xx' => '623', 'yy' => '997'], 1, 3, 18, 26, ['D', 'E', 'F'], 31, $occupedPlaces);
	}
}