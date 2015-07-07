<?php


	
	$aToken = "3342863357-CQj2QsPx8nYoXdSMEFIvv07WNo6fScsrPXTLUwp";
	$aTokenSecret = "rEUQ1T7TFOblIl6iqOD1MY6WRmuPs51ctivvdyKyzmR97";
	
	function getBearerCred()
	{
		$apiKey = "R3rdZndxWrrMeMPfkBgNJOe1u";
		$apiSecret = "ysWKwF5Y3T7D9J4hUGsatwcXT1zVqbjpQOrpQuD3d8Lm8SbXPI";
		
		$ret = base64_encode($apiKey . ":" . $apiSecret);
		
		return $ret;
	
	}

?>