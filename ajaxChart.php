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

    getChart();

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

function hours()
{

	$app = array("am","pm");
	$res = array();
	for($i = 0; $i < 2; $i++)
	{
		for($j = 0; $j < 12; $j++)
		{
			if($j == 0)
			{
				array_push($res,"12 " . $app[$i]);
			}
			else
			{
				array_push($res,$j . " " . $app[$i]);
			}
		}

	}
	return $res;
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
  $settings["Chart"] = tryGet("chart");
  $settings["Account"] = tryGet("account");
  $settings["From"] = tryGet("from");
  $settings["To"] = tryGet("to");

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
  $res = "";
  foreach ($settings as $k => $s) {
    $res .= "$k=$s;";
  }

  return $res;
}

function getChart()
{
    $set = getSettings();

    //Check if this chart already exists in cache
    $ds = dataSetName($set);
    if(checkCache($ds))
    {
        $chart = loadFromCache($ds);
    }
    else
    {
      //Now determine which chart to get

      switch($set["Chart"])
      {
        case "web-traffic" : $chart = chartWebTraffic($set); break;
        case "mobile-os" : $chart = chartMobileOS($set); break;
        case "traffic-hourly" : $chart = chartTrafficHourly($set); break;


        //Not found
        default: $chart = null;  break;

      }
      if(!empty($chart))
      {
          storeInCache($ds, $chart);
      }
    }

    print $chart;
}

/* Charts */
function chartWebTraffic($settings)
{
  //Setup analytics
  $analytics = getAnalytics();

  //Get data
  $colors = getColorScheme();

  try
  {
    $data = invertData(runQuery($analytics, $settings["Account"], $settings["From"], $settings["To"],"ga:pageviews,ga:visits,ga:users","ga:date")->getRows());
  }
  catch (Exception $e)
  {
    return NULL;
  }

  //Form chart
  $start = strtotime(strtotime($data[0][0]));
  $int = strtotime($data[0][1]) - strtotime($data[0][0]);
  $chart = new Highchart('areaspline');
  $chart->addLegend();
  $chart->addPlotOption('fillOpacity',0.2);
  $chart->addSeries($data[1],'Pageviews',$colors[3]);
  $chart->addSeries($data[2],'Sessions',$colors[2]);
  $chart->addSeries($data[3],'Users',$colors[1]);
  $chart->addTimestamps($start*1000,$int*1000);

  return $chart->toJson();
}

function chartMobileOS($settings)
{
  //Setup analytics
  $analytics = getAnalytics();

  //Get data
  $colors = getColorScheme();
  try
  {
    $data = invertData(runQuery($analytics, $settings["Account"], $settings["From"], $settings["To"],"ga:users","ga:operatingSystem","-ga:users",'5','','gaid::-11')->getRows());
  }
  catch (Exception $e)
  {
    return NULL;
  }

  $chart = new Highchart('bar');
  $chart->addCategories($data[0]);
  $chart->addSeries($data[1],'Users', $colors[1]);

  return $chart->toJson();
}

function chartTrafficHourly($settings)
{
  //Setup analytics
  $analytics = getAnalytics();

  //Get data
  $colors = getColorScheme();
  try
  {
      $data = invertData(runQuery($analytics, $settings["Account"], $settings["From"], $settings["To"],"ga:pageviews,ga:visits,ga:users","ga:hour")->getRows());
  }
  catch (Exception $e)
  {
    return NULL;
  }


  $start = strtotime("12am");
  $int = strtotime("1 hour");

  $chart = new Highchart('areaspline');
  $chart->addLegend();
  $chart->addPlotOption('fillOpacity',0.2);
  $chart->addSeries($data[1],'Pageviews',$colors[3]);
  $chart->addSeries($data[2],'Sessions',$colors[2]);
  $chart->addSeries($data[3],'Users',$colors[1]);
  $chart->addCategories(hours(), 3);

  return $chart->toJSON();
}







 ?>
