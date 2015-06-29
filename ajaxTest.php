<?php

?>
<html>
  <head>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="jquery/jquery-1.11.2.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="highstock/js/highstock.js"></script>
    <script src="highstock/js/modules/drilldown.js"></script>

    <script>
    $(document).ready( function () {
    //  $('#test').highcharts( {"chart":{"type":"areaspline"}, "title":{"text":""}, "tooltip":{"shared":true,"valueSuffix":""}, "credits":{"enabled":false}, "yAxis":{"title":{"text":""}}, "series":[{"data":[3734,3761,3297,2984,2786,4285,4229,3550,3180,2983,2683,2318,2437,3726,3214,3943,3684,3023,2376,2512,3593,3105,3326,4195,4004,2571,2375,3535,3573,3391,3788,2457],"name":"Pageviews","color":"rgba(205,151,205,1)"}, {"data":[1395,1526,1306,1187,1024,1352,1550,1434,1312,1285,1114,848,957,1435,1257,1620,1456,1253,1031,938,1262,1220,1207,1463,1610,1057,894,1274,1371,1283,1472,929],"name":"Sessions","color":"rgba(205,151,151,1)"}, {"data":[1261,1338,1187,1065,905,1082,1365,1278,1147,1158,994,774,812,1208,1110,1415,1280,1114,912,857,1098,1082,1080,1291,1428,962,812,1131,1223,1137,1291,817],"name":"Users","color":"rgba(151,205,187,1)"}],"legend":{"layout":"horizontal","align":"center","verticalAlign":"top","floating":true,"borderWidth":0,"backgroundColor":"transparent"}, "plotOptions":{"areaspline":{"fillOpacity":0.2,"pointInterval":86400000,"pointStart":1432612800000}}, "xAxis":{"type":"datetime"}} );

    //  alert("ready");




      $.getJSON("ajaxChart.php?chart=web-traffic&account=ga:53193816", function( data )
      {
      //  alert(data);
        $('#test').highcharts(
           data
          );
      }).done(function(data) {



      }).fail(function () {
        alert("Fail!");
      });

      });
    </script>
  </head>
  <body>
    <pre id='debug'>

    </pre>
    <div id="test" style="width: 100%; height: 100%;">

    </div>
  </body>
</html>
