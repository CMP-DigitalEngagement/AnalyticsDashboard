<?php
require_once "twitAccounts.php";
require_once "twitFunc.php";
//require_once "../sqlConfig.php";


$token = getToken();
$acts = getTwitAccounts();

if($token)
{
  $user = 19412366;
  $params = array("count" => 200, "trim_user" => 1, "exclude_replies" => 1, "include_rts" => 0, "user_id" => $user);
  $url = "https://api.twitter.com/1.1/statuses/user_timeline.json";

  $tweets = APIGet($url, $params, $token);

  usort($tweets, "tweetSort");



  foreach ($tweets as $key => $t)
  {
    if($key < 5)
    {
      $url = "https://api.twitter.com/1.1/statuses/oembed.json";
      $params = array("id" => $t->id_str);
      $tweet = APIGet($url, $params, $token);

      //var_dump($tweet);

      print $tweet->html;
    }
    else
    {
      break;
    }

  }


}

function tweetSort($a, $b)
{
  //Sort higher score to lower

  $rtVal = 2;
  $favVal = 1;

  $rtA = $a->retweet_count;
  $favsA = $a->favorite_count;

  $scoreA = $rtA*$rtVal + $favsA*$favVal;

  $rtB = $b->retweet_count;
  $favsB = $b->favorite_count;

  $scoreB = $rtB*$rtVal + $favsB*$favVal;

  if($scoreA == $scoreB) return 0;

  return ($scoreA > $scoreB) ? -1 : 1;

}

?>
