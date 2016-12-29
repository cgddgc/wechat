<?php
$user="3113004181";
$passwd="kong1994";
//function curl_login($user,$passwd){
	$login_url="http://222.200.98.147";
	$cookie_file=tempnam('./temp','cookie');
	$data='account=3113004181&passwd=kong1994&j_captcha=';
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,$login_url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_POST,1);
	curl_setopt($ch,CURLOPT_COOKIEJAR,$cookie_file);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
	curl_exec($ch);
	curl_close($ch);

//function get_data($url){
	$url="http://222.200.98.147/login!welcome.action";
	$curl=curl_init();
	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	//curl_setopt($curl,CURLOPT_POST,1);
	curl_setopt($curl,CURLOPT_COOKIEFILE,$cookie_file);
	//curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
	$output=curl_exec($curl);
	curl_close($curl);
	//return $output;
//}
//curl_login($user,$passwd);
echo $output;
?>
