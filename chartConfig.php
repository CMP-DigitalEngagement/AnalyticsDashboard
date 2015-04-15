<?php

/* returns an array of colors for use in series plots */
function getColorScheme()
{
	$colors = array();

	
	//Light blue
	array_push($colors, "rgba(151,187,205,1)");
	
	//light green
	array_push($colors, "rgba(151,205,187,1)");

	//light red
	array_push($colors, "rgba(205,151,151,1)");

	//light purple
	array_push($colors, "rgba(205,151,205,1)");

	
	return $colors;

}

function insertScripts()
{
?>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

<script src="jquery/jquery-1.11.2.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="highstock/js/highstock.js"></script>
<script src="highstock/js/modules/drilldown.js"></script>

<?php
}




?>