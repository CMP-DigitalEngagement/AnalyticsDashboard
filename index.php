<?php


function showErrors()
{
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}

showErrors();

require_once 'dashboard.php';





$client = getClient();

$token = Authenticate($client);

$analytics = new Google_Service_Analytics($client);

$charts = getCharts($analytics);

function getActive()
{
	if(isset($_POST["Museum"]))
	{
		print "\"#" . $_POST["Museum"] . "tab\"";
	}
	else
	{
		print "\"#CMPtab\"";
	}

}
?>

<!DOCTYPE html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php insertScripts() ?>
	<script>
		$( document ).ready(function($) {
			
			
			
			<?php
			foreach($charts as $c)
			{
				print $c;
			}
			?>
			
			
			$('#main-nav').children().removeClass("active");
			$(<?php getActive(); ?>).addClass("active");
			
			
		
			$('#main-nav').children().click(function()
			{
				//Active class change
				$('#main-nav').children().removeClass("active");
				$(this).addClass("active");
				
				//Get museum ID
				var id = $(this).attr("id");
				var id = id.substring(0,id.length - 3);
				
				//Submit a form so that php knows which museum to generate data for
				$("#MuseumID").attr("value",id);
				$("#MForm").submit();
				
			});
		
		});
		
		
		
	
	
		
	</script>
	<style>
	ul{
		list-style-type: none;
	}
	.chart-holder
	{
		position:relative;
		margin-left: 1%;
		margin-top:1%;
		width:98%;
		height:98%;
	}
	.legend
	{
		position:absolute;
		top:1%;
		right:1%;
	}
	.legend span
	{
		float:right;
		margin-bottom: 5px;
	}
	.main-panel
	{
		padding-left: 1%;
		padding-top:1%;
		width:100%;
	}
	.panel-body
	{
	position:relative;
	}
	</style>
</head>
<body>
	<ul id='main-nav' class="nav nav-tabs">
		<li id='CMPtab' role="presentation" class="active"><a href="#">Central</a></li>

		<li id='CMOAtab' role="presentation" ><a href="#">CMOA</a></li>

		<li id='CMNHtab' role="presentation"><a href="#">CMNH</a></li>

		<li id='CSCtab' role="presentation"><a href="#">CSC</a></li>

		<li id='AWMtab' role="presentation"><a href="#">Warhol</a></li>

	</ul>
	
		<form id='MForm' method='post'>
			<input id='MuseumID' type='hidden' name='Museum'>
		</form>

	
	
	
	<div id='Panel' class='main-panel container'>
		
		<?php setupCharts(
			array("web-traffic","web-byhour","mobile-os","web-browser","most-viewed"),
			array("Overall Web Traffic","Web Traffic by Hour","Mobile Operating Systems","Web Browsers","Most Viewed Pages"));
			?>
	</div>
	
</body>