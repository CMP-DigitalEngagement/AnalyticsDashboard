<?php
require_once "twitAccounts.php";
require_once "twitFunc.php";
require_once "../sqlConfig.php";


$token = getToken();
$acts = getTwitAccounts();


if($token)
{
	$users = "";
	foreach($acts as $a)
	{
		$users .= $a["id"] . ",";
	}
	
	$users = trim($users,", ");
	
	$userList = APIGet("https://api.twitter.com/1.1/users/lookup.json",array("user_id"=>$users),$token);
	
	$sql = getSql();
	
	print "<pre>";
	foreach($userList as $u)
	{
		$stats = array();
		
		$id = $u->id;
		$follow = $u->followers_count;
		$tweets = $u->statuses_count;
		
		print $u->name . "\n\n";
		
		$query = "INSERT INTO twitter_analytics VALUES ($id, $follow, $tweets, NOW())";
		
		
		if(!mysqli_query($sql,$query))
		{
			print $query . " :(". $sql->connect_errno . ") " . $sql->connect_error . "\n\n";
		}
		else
		{
			print $query . "\n\n";
		}
		
	}
	print "</pre>";

}


?>