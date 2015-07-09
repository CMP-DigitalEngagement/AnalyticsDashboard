

<?php
  require_once "../api.php";
  require_once "fbConfig.php";

//print "<pre>";

//print "</pre>";

  function getToken()
  {
    $url = "https://graph.facebook.com/oauth/access_token";
    $params = array(
      "client_id" => getClientID(),
      "client_secret" => getClientSecret(),
      "grant_type" => "client_credentials"
    );

    $token = getAPI($url,$params);
    $token = explode('=', $token);

    return $token;
  }

  function getTopPosts($act)
  {
    $token = getToken();
    $actID = $act['id'];

    $url = "https://graph.facebook.com/$actID/posts";
    $params = array(
      $token[0] => $token[1],
      "limit" => 100,
      "fields" => "likes.limit(1).summary(true),shares,actions"
    );

    $result = getAPI($url,$params)->data;
    usort($result, "fbSort");
    $posts = array();
    foreach ($result as $key => $p)
    {
      if($key < 5)
      {
        array_push($posts, $p);
      }
      else
      {
        break;
      }

    }

    return $posts;


  }

  function fbSort($a, $b)
  {
    $share = 1;
    $likes = 1;

    $likesB = 0;
    $likesA = 0;

    $shareA = 0;
    $shareB = 0;

    if(isset($a->likes))
    {
      $likesA = $a->likes->summary->total_count;
    }
    if(isset($b->likes))
    {
      $likesB = $b->likes->summary->total_count;
    }
    if(isset($a->shares))
    {
      $shareA = $a->shares->count;
    }
    if(isset($b->shares))
    {
      $shareB = $b->shares->count;
    }

    $pointA = $likesA*$likes + $shareA*$share;
    $pointB = $likesB*$likes + $shareB*$share;

    if($pointA == $pointB)
    {
      return 0;
    }

    return ($pointA > $pointB) ? -1 : 1;

  }



 ?>
