<?php

/**
 * 
 */
class apiCurlData
{

	public static function getCurlData($url, $key=false)
	{
		$uagent = "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.205 Safari/534.16";
    
    	$ch = curl_init( $url );
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // возвращает веб-страницу
    	curl_setopt($ch, CURLOPT_HEADER, 0);           // возвращает заголовки
    	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);   // переходит по редиректам
    	curl_setopt($ch, CURLOPT_ENCODING, "");        // обрабатывает все кодировки
    	curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
    	curl_setopt($ch, CURLOPT_TIMEOUT, 20);        // таймаут ответа
    	curl_setopt($ch, CURLOPT_MAXREDIRS, 10);       // останавливаться после 10-ого редиректа
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	if($key != false)
    		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    			'Authorization: Bearer '. $key
    		));
		$content = curl_exec( $ch );
		$err     = curl_errno( $ch );
		$errmsg  = curl_error( $ch );
		$header  = curl_getinfo( $ch );
		curl_close( $ch );

		$header['errno']   = $err;
		$header['errmsg']  = $errmsg;
		$header['content'] = $content; 
		return $header;
	}

	public function findAll($preg, $text)
	{
		preg_match_all($preg, $text, $result);
		return $result;
	}

	public function getIATAS($data)
	{
		return $this->getMyListForTable($data);
	}

	private function getMyListForTable($tdElems){
		$result 	= array('test');
		$myList 	= array();

		$myList[$tdElems[0]][0] = $tdElems[1];

		for ($j=2, $k=1; $j < count($tdElems); $j++) {
			if($j % 3 == 0){
				if(!isset($myList[$tdElems[$j]])){
					$myList[$tdElems[$j]] = array();
					$k = 0;
				}
				$myList[$tdElems[$j]][$k] = $tdElems[$j + 1];
				$k++;
			}
		}

		return $myList;
	}

	public function saveToJson($data, $filename)
	{
		$fp = fopen($filename, 'w');
		fwrite($fp, json_encode($data));
		fclose($fp);
	}

	public function getApiAmadeusDate($url, $data)
	{
		# code...
	}
}