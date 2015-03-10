<?php
require_once 'chartConfig.php';

class Highchart
{
	var $chart = array();
	var $chart_type = '';
	public function __construct($type='') 
	{
		//Here we setup some standard fields
		$this->chart = array();
		$this->chart['chart'] = array();
		
		$this->chart['title'] = array();
		$this->chart['tooltip'] = array();
		$this->chart['credits'] = array();
		$this->chart['yAxis'] = array();
		$this->chart['xAxis'] = array();
		$this->chart['series'] = array();
		
		$this->chart['credits']['enabled'] = false;
		$this->chart['tooltip']['shared'] = true;
		$this->chart['tooltip']['valueSuffix'] ='';
		$this->chart['title']['text'] = '';
		$this->chart['yAxis']['title'] = array();
		$this->chart['yAxis']['title']['text'] = '';
		
		
		//If type is set add some more standard settings
		if(!empty($type))
		{
			$this->chart_type = $type;
			$this->chart['chart']['type'] = $type;
			//$this->chart['plotOptions'][$type] = array();
		}
	}
	
	function correctData($data)
	{
		$newdata = array();
		foreach($data as $d)
		{
			array_push($newdata, intval($d));
		}
		
		return $newdata;
	
	}
	
	public function setType($type)
	{
		$this->chart['chart']['type'] = $type;
		//$this->chart['plotOptions'][$type] = array();
		$this->chart_type = $type;
	}
	
	public function addPlotOption($option, $value)
	{
		if(!empty($this->chart_type))
		{
			if(!isset($this->chart['plotOptions']))
			{
				$this->chart['plotOptions'] = array();
			}
			if(!isset($this->chart['plotOptions'][$this->chart_type]))
			{
				$this->chart['plotOptions'][$this->chart_type] = array();
			}
			
			$this->chart['plotOptions'][$this->chart_type][$option] = $value;
		}
		else
		{
			return false;
		}
	}
	public function addTimestamps($start, $interval)
	{
		$this->chart['xAxis']['type'] = "datetime";
		$this->addPlotOption("pointInterval", $interval);
		$this->addPlotOption("pointStart",$start);
	
	}
	
	public function setTitle($title)
	{
		$this->chart['title']['text'] = $title;
	}
	
	public function setYAxisLabel($label)
	{
		$this->chart['yAxis']['title']['text'] = $label;
	}
	
	public function addLegend()
	{
		$this->chart['legend']['layout'] = 'horizontal';
		$this->chart['legend']['align'] = 'center';
		$this->chart['legend']['verticalAlign'] = 'top';
		$this->chart['legend']['floating'] = true;
		$this->chart['legend']['borderWidth'] = 0;
		$this->chart['legend']['backgroundColor'] = 'white';
	}
	
	public function addCategories($cats, $step =1)
	{
		$this->chart['xAxis']["categories"] = $cats;
		if($step > 1)
		{
			$this->chart['xAxis']['labels'] = array();
			$this->chart['xAxis']['labels']['step'] = $step;
		}
	}
	
	public function addPlotBand($from, $to, $color)
	{
		if(is_null($this->chart['xAxis']['plotBands']))
		{
			$this->chart['xAxis']['plotBands'] = array();
		}
		
		$newPlot = array();
		$newPlot['from'] = $from;
		$newPlot['to'] = $to;
		$newPlot['color'] = $color;
		
		array_push($this->chart['xAxis']['plotBands'], $newPlot);
		
	}
	
	public function addSeries($data, $name='', $color='')
	{
		$newSeries = array();
		$newSeries['data'] = $this->correctData($data);
		if(!empty($name))
		{
		$newSeries['name'] = $name;
		}
		if(!empty($color))
		{
		$newSeries['color'] = $color;
		}
		
		array_push($this->chart['series'],$newSeries);
	}
	
	public function toJSON()
	{
		return str_replace("},","},\n",json_encode($this->chart));
	}
	
	public function toChart($selector)
	{
		return "$('" . $selector . "').highcharts(\n" . $this->toJSON() . "\n);\n\n";
	}


}

/*

function generateAreaspline()
{
$ret = <<<EOT
{
chart: {
	type: 'areaspline'
},

plotOptions: {
	areaspline: {
		fillOpacity: 0.2
	}
},
EOT;

$ret .= generateTitles();
		


}


function generateTimeChart($type)
{

}



function generateTitles($this->chartTitle='', $yaxisTitle='', $units='')
{
return <<<EOT
title: {
	text: '$this->chartTitle'
},
	 tooltip: {
	shared: true,
	valueSuffix: ' $units'
},
credits: {
	enabled: false
},
  yAxis: {
	title: {
		text: '$yaxisTitle'
	},
},
EOT;

}


function getLegend()
{
return <<<EOT
legend: {
	layout: 'horizontal',
	align: 'center',
	verticalAlign: 'top',
	floating: true,
	borderWidth: 0,
	backgroundColor: '#FFFFFF'
},
EOT;

}

function generateLabels($labels, $step=1)
{
	$ret = "categories : [";
	$first = true;
	foreach($labels as $l)
	{
		if(!$first)
		{
			$ret .= ", ";
		}
		$first = false;
		$ret .= $l;
	}
	
	$ret .= "],\n";
	if($step > 1)
	{
	 $ret .= "labels:{step:$step},\n";
	}
	
	return $ret;

}
function genMultipleSeries($dataArray, $nameArray, $colorArray)
{
	$i = 0;
	$ret = "";
	foreach($data as $d)
	{
		$ret .= generateSeries($d, $nameArray[$i],$colorArray[$i]);
	
	}
	return $ret;

}
function generateSeries($data, $name='', $color='', $more='')
{
	$ret = "{\n";
	if(!empty($name))
	{
		$ret .= "name: '$name',\n";
	}
	if(!empty($color))
	{
		$ret .= "color: '$color',\n";
	}
	$ret .= getDataString($data) . ",\n";
	$ret .= $more;
	$ret .= "\n},\n";
	return $ret;
} 


function getDataString($data)
{

	$ret = "data : [";
	$first = true;
	foreach($data as $d)
	{
		if(!$first)
		{
			$ret .= ", ";
		}
		$first = false;
		$ret .= $d;
	}
	
	$ret .= "],";
	
	return $ret;
}
*/



?>