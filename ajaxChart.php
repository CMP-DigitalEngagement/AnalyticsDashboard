<?php
/*
*   This file will generate the JSON required to display an analytical chart
*
*   The following charts can be used:
*
*/

function showErrors()
{
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}

showErrors();

require_once "analytics.php";
require_once "highcharts.php";
require_once "cache.php";


/* source: https://jonsuh.com/blog/jquery-ajax-call-to-php-script-with-json-return/ */
if (is_ajax() || true) //Remove TRUE when done testing
{
  if (isset($_GET["chart"]) && !empty($_GET["chart"])) { //Checks if action value exists

    $action = $_GET["chart"];
		switch($action) { //Switch case for value of action
			case "web-traffic" : chartWebTraffic(); break;

		}
	}
}
function is_ajax()
{
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
/* end */

/* General Functions */
function getAnalytics()
{
  $client = getClient();
  $token = Authenticate($client);
  $analytics = new Google_Service_Analytics($client);
  return $analytics;
}

function invertData($data)
{
	$newData = array();
	$cols = 0;
	foreach($data[0] as $d)
	{
		array_push($newData, array());
		$cols++;
	}
	$i = 0;
	foreach($data as $d)
	{
		for($j = 0; $j < $cols; $j++)
		{
			$newData[$j][$i] = $d[$j];

		}
		$i++;
	}
	return $newData;
}

function GoogleDate($time = '')
{
	if($time == '')	return date('Y-m-d');
	return date('Y-m-d', $time);
}

function throwError($message, $function)
{
  $return = array();
  $return["status"] = "error";
  $return["error" ] = array();
  $return["error"]["function"] = $function;
  $return["error"]["message"] = $message;

  print json_encode($return);
}

function tryGet($var)
{
  if(isset($_GET[$var]))
  {
    return $_GET[$var];
  }
  return null;
}


function getSettings()
{
  $settings = array();

  //Get settings
  $settings["Account"] = tryGet("account");
  $settings["From"] = tryGet("from");
  $settings["To"] = tryGet("to");
  $settings["Chart"] = tryGet("chart");

  //Validate settings
  if(empty($settings["Account"]))
  {
    return throwError("*account* must be set","getSettings");
  }
  if(empty($settings["From"])) //From defaults to 1 month prior to today
  {
      $settings["From"] = GoogleDate(strtotime("-1 month -1 day"));
  }
  if(empty($settings["To"])) //To defaults to today
  {
      $settings["To"] = GoogleDate(strtotime("-1 day"));
  }

  return $settings;

}

function dataSetName($settings)
{

  return "chart=" . $settings["Chart"] . ";account=" . $settings["Account"] . ";from=" . $settings["From"] . ";to=" . $settings["To"] . ";";

}

/* Charts */
function chartWebTraffic()
{
  //Get settings
  $set = getSettings();
  $analytics = getAnalytics();
  $colors = getColorScheme();

  //Get data
  $ds = dataSetName($set);
  if(checkCache($ds))
  {
      $data = unserialize(loadFromCache($ds));
  }
  else
  {
    $data = invertData(runQuery($analytics, $set["Account"],$set["From"],$set["To"],"ga:pageviews,ga:visits,ga:users","ga:date")->getRows());
    $dataS = serialize($data);
    storeInCache($ds, $dataS);
  }


  //Form chart
  $start = strtotime($set["From"]);
  $int = strtotime($data[0][1]) - strtotime($data[0][0]);
  $chart = new Highchart('areaspline');
  $chart->addLegend();
  $chart->addPlotOption('fillOpacity',0.2);
  $chart->addSeries($data[1],'Pageviews',$colors[3]);
  $chart->addSeries($data[2],'Sessions',$colors[2]);
  $chart->addSeries($data[3],'Users',$colors[1]);
  $chart->addTimestamps($start*1000,$int*1000);

  print $chart->toJson();

}





 ?>
