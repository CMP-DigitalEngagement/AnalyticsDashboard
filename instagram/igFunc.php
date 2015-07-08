<?php


function getPosts($account, $client)
{

  $url = 'https://api.instagram.com/v1/users/' . $account['id'] . "/media/recent?client_id=$client&count=200";


  return getAPI($url);
}

function getAPI($url)
{
  $curl = curl_init();

  curl_setopt($curl,	CURLOPT_URL				, $url);
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
  //var_dump($result);
  return $data;

}

?>
