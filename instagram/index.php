<?php
require_once  'igFunc.php';
require_once  'igConfig.php';

$client = getClientID();
$accts = getAccts();

$posts = getPosts($accts[0], $client)->data;


usort($posts, "igSort");

foreach ($posts as $key => $p) {
  if($key < 5)
  {

    $embed = igEmbed($p->link);

    print $embed->html;

  }
  else {
    break;
  }
}

function igEmbed($url)
{
  $url = "http://api.instagram.com/publicapi/oembed/?url=$url";
  $data = getAPI($url);

  return $data;
}

function igSort($a, $b)
{
  $likesA = $a->likes->count;
  $likesB = $b->likes->count;

  if($likesA == $likesB)
  {
    return 0;
  }

  return ($likesA > $likesB) ? -1 : 1;

}


 ?>
