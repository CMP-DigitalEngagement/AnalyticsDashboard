<?php
require_once "twitConfig.php";

function getToken()
{
	$url = "https://api.twitter.com/oauth2/token";
	$cred = getBearerCred();

	$headers = array(
	"Authorization: Basic $cred",
	"Content-Type: application/x-www-form-urlencoded;charset=UTF-8");
	//"Content-Length: 29",
	//"Accept-Encoding: gzip");



	$params = http_build_query(array("grant_type" => "client_credentials"));


	$curl = curl_init();

	curl_setopt($curl,	CURLOPT_URL				, $url);
	curl_setopt($curl,	CURLOPT_POST			, 1);
	curl_setopt($curl,	CURLOPT_POSTFIELDS		, $params);
	curl_setopt($curl,	CURLOPT_HTTPHEADER		, $headers);
	curl_setopt($curl,	CURLOPT_RETURNTRANSFER	, true);
	curl_setopt($curl,	CURLOPT_ENCODING 		, "gzip");
	curl_setopt($curl,	CURLOPT_SSL_VERIFYPEER	, false);


	if( ! $result = curl_exec($curl))
    {
        var_dump(curl_error($curl));
		return NULL;
    }

	curl_close($curl);
	$data = json_decode($result);

	if(isset($data->access_token))
	{
		return $data->access_token;
	}

	return NULL;
}

function APIGet($url, $params, $token, $toArray = false)
{
	$curl = curl_init();
	$params = http_build_query($params);
	$headers = array("Authorization: Bearer " . $token);

	curl_setopt($curl, CURLOPT_URL, $url . "?" . $params);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl,	CURLOPT_ENCODING 		, "gzip");
	curl_setopt($curl,	CURLOPT_SSL_VERIFYPEER	, false);
	curl_setopt($curl,	CURLOPT_RETURNTRANSFER	, true);

	if( ! $result = curl_exec($curl))
    {
        var_dump(curl_error($curl));
		return NULL;
    }
	curl_close($curl);

	$data = json_decode($result, $toArray);

	return $data;
}


?>
