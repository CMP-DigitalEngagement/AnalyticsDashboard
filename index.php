<?php


function showErrors()
{
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}

showErrors();

require_once 'dashboard.php';

$chartList = getChartList();
?>

<!DOCTYPE html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php insertScripts() ?>
	<script>
		$( document ).ready(function($) {






			$('#main-nav').children().removeClass("active");
			$(<?php getActive(); ?>).addClass("active");
			console.log(<?php getActive(); ?>);


			$('#main-nav').children().click(function()
			{
				//Active class change
				$('#main-nav').children().removeClass("active");
				$(this).addClass("active");

				//Get museum ID
				var id = $(this).attr("id");
				if(id == 'Compare' || id == 'Combined')
				{}
				else
				{
					id = id.substring(0,id.length - 3);
				}
				console.log(id);

				//Submit a form so that php knows which museum to generate data for
				$("#MuseumID").attr("value",id);
				$("#MForm").submit();


			});

			<?php setupChartHead($chartList); ?>

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
		<li id='Compare' role="presentation"><a href="#">Compare</a></li>

		<li id='Combined' role="presentation" class="active"><a href="#">Combined</a></li>

		<li id='CMPtab' role="presentation" ><a href="#">Central</a></li>

		<li id='CMOAtab' role="presentation" ><a href="#">CMOA</a></li>

		<li id='CMNHtab' role="presentation"><a href="#">CMNH</a></li>

		<li id='CSCtab' role="presentation"><a href="#">CSC</a></li>

		<li id='AWMtab' role="presentation"><a href="#">Warhol</a></li>

	</ul>

		<form id='MForm' method='get'>
			<input id='MuseumID' type='hidden' name='m'>
		</form>




	<div id='Panel' class='main-panel container'>

		<?php
			setupChartDisplay($chartList);
		?>
	</div>

</body>
