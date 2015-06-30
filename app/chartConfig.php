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

	//Green-yellow
	array_push($colors, "rgba(190,205,151,1)");


	return $colors;

}






?>
