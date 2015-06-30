<?php
/* This file provides functions for index.php */

function getAccounts()
{
	$accounts = array();
	$accounts["CMOA"] =  "ga:53193816";
	$accounts["CSC"] =   "ga:12575410";
	$accounts["CMNH"] =  "ga:19917087"; //"ga:86907168";
	$accounts["AWM"] =   "ga:30663551";
	$accounts["CMP"] =   "ga:43375288";

	return $accounts;
}



function getActive()
{
	if(isset($_GET["m"]))
	{
		if($_GET["m"] == "Compare" || $_GET["m"] == "Combined")
		{
			print "\"#" . $_GET["m"] . "\"";
		}
		else
		{
			print "\"#" . $_GET["m"] . "tab\"";
		}
	}
	else
	{
		print "\"#Combined\"";
	}

}

function getMuseum()
{
	$accounts = getAccounts();

	//Set account to use
	if(isset($_GET["m"]))
	{
		if($_GET["m"] == "Compare") return "Compare";
		if($_GET["m"] == "Combined") return "Combined";
		$account = $accounts[$_GET["m"]];
	}
	else
	{
		return "Combined";
	}

	return $account;
}


function getChartList()
{


  if(getMuseum() == "Compare")
  {

    // setupCharts(
    // array("hist","duration","users"),
    // array("Historical Pageviews","Average Time on Site","New Users"));
  }
  else if(getMuseum() == "Combined")
  {
    // setupCharts(
    // array("views"),
    // array("Web Traffic (Pageviews)"));
  }
  else
  {
    $charts = json_decode(file_get_contents("charts.json"), true);
    return $charts;
  }

}

function setupChartHead($chartList, $from = null, $to = null);
{
  $acct = getMuseum();


  foreach($chartList as $c)
	{
    $chart = $c["name"];
		$jq = "$.getJSON('./app/ajaxChart.php?account=$acct&chart=$chart'";
    if(!empty($from))
    {
      $jq .= "&from=$from";
    }
    if(!empty($to))
    {
      $jq .= "&to=$to";
    }
    $jq .= "', function( data ) {\n$('#$chart').highcharts(";
    if($c["stock"])
    {
      $jq .= "'StockChart' ,";
    }
    $jq .= "\ndata\n});\n});\n\n"

    print $jq;
	}

}

function setupChartDisplay($chartList)
{

	foreach($chartList as $c)
	{
		setupChart($c["name"], $c["title"]);
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
