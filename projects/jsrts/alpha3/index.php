<?php
	include("/var/www/html/modules/AppInit.php");
	
	ob_start();
?>
	<script type="text/javascript" src="common/jsrts.js"></script>
	<script type="text/javascript">
		
		$(document).ready(function(){
			Game = new JSRTS(
			{
				GameArea:$('#Game'),
				width:'600px',
				height:'600px',
				class:'GameArea',
				statusClass:'StatusBar'
			});
			Game.StartGame();
			Game.addPlayer({Name:'Player 1'});
			Game.addPlayer({Name:'Player 2'});
			Game.addUnit({player:0,type:'Soldier',startTop:0,startLeft:0});
			Game.addUnit({player:1,type:'Soldier',startTop:200,startLeft:200});
		});
	</script>
	
	<style type="text/css">
		.GameArea{
			border:1px solid #000;
			overflow:auto;
		}
		.StatusBar{
			background-color:#CCC;
			border-bottom:1px solid #000;
			border-left:1px solid #000;
			border-right:1px solid #000;
			overflow:auto;
		}
		.Unit{
			position:absolute;
			background-color:#CCC;
			cursor:pointer;
		}
		.Soldier{
			height:20px;
			width:20px;
		}
		.Selected{
			outline:1px solid #F00;
		}
	</style>
	<p>This page, hopefully, will someday be a javascript real time strategy game. It will be a very large project if it ends up having even some of the features that I am planning.</p>
	<div id="Game"></div>
	<button type="button" onclick="Game.addUnit({player:0,type:'Soldier',startTop:0,startLeft:0});">Add Unit</button>
	<button type="button" onclick="Game.addUnit({player:1,type:'Soldier',startTop:Math.round(Math.random() * 500),startLeft:Math.round(Math.random() * 500)});">Add Enemy</button>
	
<?php
	$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>
