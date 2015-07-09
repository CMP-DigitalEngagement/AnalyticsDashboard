<?php
require_once "../api.php";
require_once "fbConfig.php";
require_once "fbFunc.php";

$acts = getAccts();
$posts = getTopPosts($acts[0]);


?>
<html>
  <body>
    <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.2&appId=<?php print getClientID(); ?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
    <?php
      foreach ($posts as $key => $p)
      {
        print "<div><h1>" . ($key + 1) . "</h1><div class='fb-post' data-href='" . $p->actions[0]->link . "'></div></div>"; // data-width='500'
      }
    ?>
  </body>
  </html>
