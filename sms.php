<?php
function send($host, $port, $login, $password, $phone, $text, $sender = false, $wapurl = false )
{
	$fp = fsockopen($host, $port, $errno, $errstr);
	if (!$fp) {
		return "errno: $errno \nerrstr: $errstr\n";
	}
	fwrite($fp, "GET /send/" .
		"?phone=" . rawurlencode($phone) .
		"&text=" . rawurlencode($text) .
		($sender ? "&sender=" . rawurlencode($sender) : "") .
		($wapurl ? "&wapurl=" . rawurlencode($wapurl) : "") .
		" HTTP/1.0\n");
	fwrite($fp, "Host: " . $host . "\r\n");
	if ($login != "") {
		fwrite($fp, "Authorization: Basic " . 
			base64_encode($login. ":" . $password) . "\n");
	}
	fwrite($fp, "\n");
	$response = "";
	while(!feof($fp)) {
		$response .= fread($fp, 1);
	}
	fclose($fp);
	list($other, $responseBody) = explode("\r\n\r\n", $response, 2);
	return $responseBody;
}

/* 
* использование функции передачи сообщения
*/

/* 
* функция проверки состояния отправленного сообщения
*/
function status($host, $port, $login, $password, $sms_id)
{
	$fp = fsockopen($host, $port, $errno, $errstr);
	if (!$fp) {
		return "errno: $errno \nerrstr: $errstr\n";
	}
	fwrite($fp, "GET /status/" .
		"?id=" . $sms_id .
		" HTTP/1.0\n");
	fwrite($fp, "Host: " . $host . "\r\n");
	if ($login != "") {
		fwrite($fp, "Authorization: Basic " . 
			base64_encode($login. ":" . $password) . "\n");
	}
	fwrite($fp, "\n");
	$response = "";
	while(!feof($fp)) {
		$response .= fread($fp, 1);
	}
	fclose($fp);
	list($other, $responseBody) = explode("\r\n\r\n", $response, 2);
	return $responseBody;
}

/* 
* использование функции проверки состояния отправленного сообщения
*/
//echo status("gate.iqsms.ru", 80, "z1540396107190", "421793", "12345");
?>