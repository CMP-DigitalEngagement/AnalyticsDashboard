<?php

require_once "analytics.php";
require_once "highcharts.php";


function getAccounts()
{
	$accounts = array();
	$accounts["CMOA"] = "ga:53193816";
	$accounts["CSC"] = "ga:12575410";
	$accounts["CMNH"] = "ga:86907168";
	$accounts["AWM"] = "ga:30663551";
	$accounts["CMP"] = "";
	
	return $accounts;
}


function getData($analytics, $from = "2015-01-01", $to = "2015-01-31")
{
	$accounts = getAccounts();
	
	//Set account to use
	if(isset($_POST["Museum"]))
	{
		$account = $accounts[$_POST["Museum"]];
	}
	else
	{
		$account = $accounts["CMP"];
	}
	
	$data = array();
	//all queries should be in try catch blocks
	
	//Mobile OS data
	try
	{
		$data["mobile-os"] = runQuery($analytics, $account,$from,$to,"ga:users","ga:operatingSystem","-ga:users",'5','','gaid::-11')->getRows();
	}
	catch (Exception $e)	{	}
	//web traffic
	try
	{
		$data["web-traffic"] = runQuery($analytics, $account,$from,$to,"ga:pageviews,ga:visits,ga:users","ga:date")->getRows();
	}
	catch (Exception $e)	{	}
	
	try
	{
		$data["web-byhour"] = runQuery($analytics, $account,$from,$to,"ga:pageviews,ga:visits,ga:users","ga:hour")->getRows();
	}
	catch (Exception $e)	{	}
	
	try
	{
		$data["web-browser"] = runQuery($analytics, $account,$from,$to,"ga:users","ga:browser","-ga:users",'5','ga:deviceCategory==desktop')->getRows();
	}
	catch (Exception $e)	{	}
	
	
	
	return $data;
}

function getCharts($analytics)
{
$charts = array();


$data = getData($analytics);
$colors = getColorScheme();


//Overall web traffic

if(isset($data["web-traffic"]))
{
	$wt = invertData($data["web-traffic"]);

	$start = strtotime($wt[0][0]);
	$int = strtotime($wt[0][1]) - strtotime($wt[0][0]);

	$areaspline = new Highchart('areaspline');
	$areaspline->addLegend();
	$areaspline->addPlotOption('fillOpacity',0.2);
	$areaspline->addSeries($wt[1],'Pageviews',$colors[3]);
	$areaspline->addSeries($wt[2],'Sessions',$colors[2]);
	$areaspline->addSeries($wt[3],'Users',$colors[1]);
	$areaspline->addTimestamps($start*1000, $int*1000);
	$charts["web-traffic"] = $areaspline->toChart("#web-traffic");
}

if(isset($data["mobile-os"]))
{
	$mos = invertData($data["mobile-os"]);
	
	$bar = new Highchart('bar');
	$bar->addCategories($mos[0]);
	$bar->addSeries($mos[1],'Users', $colors[1]);
	$charts["mobile-os"] = $bar->toChart("#mobile-os");

}

if(isset($data["web-byhour"]))
{
	$wt = invertData($data["web-byhour"]);

	$start = strtotime("12am");
	$int = strtotime("1 hour");

	$areaspline = new Highchart('areaspline');
	$areaspline->addLegend();
	$areaspline->addPlotOption('fillOpacity',0.2);
	$areaspline->addSeries($wt[1],'Pageviews',$colors[3]);
	$areaspline->addSeries($wt[2],'Sessions',$colors[2]);
	$areaspline->addSeries($wt[3],'Users',$colors[1]);
	$areaspline->addCategories($wt[0], 3);
	$charts["web-byhour"] = $areaspline->toChart("#web-byhour");

}

if(isset($data["web-browser"]))
{
	$wb = invertData($data["web-browser"]);
	
	$bar = new Highchart('bar');
	$bar->addCategories($wb[0]);
	$bar->addSeries($wb[1],'Users', $colors[0]);
	$charts["web-browser"] = $bar->toChart("#web-browser");

}

return $charts;

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

function getClient()
{
	$client = new Google_Client();
	$client->setApplicationName(getAppName());
	$client->setClientId(getClientID());
	
	return $client;
	
}



function setupCharts($cnames, $ctitles)
{
	$i = 0;
	foreach($cnames as $cname)
	{
		setupChart($cname, $ctitles[$i]);
		$i ++;
	}

}

function setupChart($cname, $ctitle)
{
?>

<div class="col-xs-12 col-md-6">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><?php print $ctitle;?></h3>
		</div>
		<div class="panel-body">
			<div class='chart-holder' id="<?php print $cname;?>">
			</div>
		</div>
	</div>
</div>

<?php
}

?>