<?php

$method = $_REQUEST["REQUEST_METHOD"];
$state = $_REQUEST["state"]; //either "on" or "off"

include "secret.php";

include "simple_html_dom.php";

$state = "off";

function log($text) {
	$log_path = "log_file.log";
	file_put_contents($log_path, $text.PHP_EOL, FILE_APPEND);
}

function login($email, $password) {
	$url = "https://mbasic.facebook.com/";
	$login_url = "https://mbasic.facebook.com/login/device-based/regular/login/?refsrc=deprecated&lwv=100";

	$cookie_file = "jar.txt";
	$agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36 Edg/111.0.1661.41";
	
	$ch = curl_init();
	curl_setopt_array($ch, [
		CURLOPT_URL => $url,
		CURLOPT_COOKIEJAR => $cookie_file,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_USERAGENT => $agent,
		CURLOPT_HTTPHEADER, ['Accept-Language: en']
	]);

	$facebook_res = curl_exec($ch);

	$html = new simple_html_dom();
	$html->load($facebook_res);

	$lsd = $html->find("input[name=lsd]", 0)->value;
	$jazoest = $html->find("input[name=jazoest]", 0)->value;
	$m_ts = $html->find("input[name=m_ts]")->value;
	$li = $html->find("input[name=li]")->value;
	$try_number = $html->find("input[name=try_number]")->value;
	$unrecognized_tries = $html->find("input[name=unrecognized_tries]")->value;
	$bi_xrwh = $html->find("input[name=bi_xrwh]")->value;
	
	$form = [
		"lsd" => $lsd,
		"jazoest" => $jazoest,
		"m_ts" => $m_ts,
		"li" => $li,
		"try_number" => $try_number,
		"unrecognized_tries" => $unrecognized_tries,
		"email" => $email,
		"pass" => $password,
		"bi_xrwh" => $bi_xrwh,
		"login" => "Log in"
	];

	$ch = curl_init();
	curl_setopt_array($ch, [
		CURLOPT_URL => $login_url,
		CURLOPT_CONNECTTIMEOUT => 30,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_COOKIEJAR => $cookie_file,
		CURLOPT_COOKIEFILE => $cookie_file,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => http_build_query($form),
		CURLOPT_HTTPHEADER, ['Accept-Language: en']
	]);
	curl_exec($ch);

	return $ch;
}

switch ($method) {
	case "POST":
		if (!($_REQUEST["code"] == "kekw")) {
			break;
		}

		if (!($_REQUEST["command"] == "login")) {
			break;
		}

		$ch = login($email, $pass);
		curl_setopt_array($ch, [
			CURLOPT_URL => "https://mbasic.facebook.com/",
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_COOKIEFILE => "jar.txt",
			CURLOPT_COOKIEJAR => "jar.txt"
		]);
		$res = curl_exec($ch);

		echo $res;

		break;
}

?>
