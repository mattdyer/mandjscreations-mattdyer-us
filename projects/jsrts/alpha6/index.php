<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	ob_start();
?>
	<script type="text/javascript" src="common/jsrts.js"></script>
	<script type="text/javascript">
		
		$(document).ready(function(){
			Game = new JSRTS(
			{
				GameArea:$('#Game'),
				width:'600px',
				height:'400px',
				mapWidth:'2000px',
				mapHeight:'2000px',
				'class':'GameArea',
				statusClass:'StatusBar',
				unitBarClass:'UnitBar'
			});
			Game.ShowGameForm();
			/*Game.StartGame();
			Game.addPlayer({Name:'Player 1'});
			Game.addPlayer({Name:'Player 2'});
			Game.addUnit({player:0,type:'Soldier',startTop:0,startLeft:0});
			Game.addUnit({player:1,type:'Soldier',startTop:200,startLeft:200});*/
		});
	</script>
	
	<style type="text/css">
		.GameArea{
			border:1px solid #000;
			overflow:auto;
			background-color:#FFF;
		}
		#GameCreateMessage{
			color:#F00;
			padding:5px;
		}
		.joinGameRow{
			white-space:nowrap;
		}
		#JoinGameForm div:nth-child(2n){
			background-color:#DDD;
		}
		#JoinGameForm div:nth-child(2n+1){
			background-color:#EEE;
		}
		.StatusBar{
			background-color:#CCC;
			border-bottom:1px solid #000;
			border-left:1px solid #000;
			border-right:1px solid #000;
			overflow:auto;
		}
		.UnitBar{
			background-color:#CCC;
			border-bottom:1px solid #000;
			border-left:1px solid #000;
			border-right:1px solid #000;
			overflow:auto;
		}
		.UnitBar > div{
			cursor:pointer;
		}
		.Unit{
			position:absolute;
			cursor:pointer;
			color:#F00;
		}
		.Attacking{
			background-color:#000;
		}
		.Soldier{
			background-image:url(images/soldier2.gif);
			background-repeat:no-repeat;
			height:30px;
			width:20px;
		}
		.Tank{
			background-color:#CCC;
			height:50px;
			width:50px;
		}
		.Soldier.Attacking{
			
		}
		.Selected{
			outline:1px solid #F00;
		}
	</style>
	<?php /*?><form method="post" enctype="multipart/form-data" name="test" action="ajax/index.php">
		<input type="text" name="Action" />
		<input type="text" name="PlayerID" />
		<input type="submit" name="Go" />
	</form><?php */?> 
	<p>This page, hopefully, will someday be a javascript real time strategy game. It will be a very large project if it ends up having even some of the features that I am planning.</p>
	<div id="Game"></div>
	<?php /*?><button type="button" onclick="Game.addUnit('Queue',{PlayerID:Game.getActivePlayerID(),type:'Soldier',startTop:0,startLeft:0});">Add Unit</button>
	<button type="button" onclick="Game.getActions();">Get Actions</button>
	<button type="button" onclick="Game.addUnit({PlayerID:1,type:'Soldier',startTop:Math.round(Math.random() * 500),startLeft:Math.round(Math.random() * 500)});">Add Enemy</button><?php */?>
	<h3>Changes From Previous Version</h3>
	<ul>
		<li>You can now join an existing game. This allows for some demonstration of the multiplayer working.</li>
		<li>The server queue system is working to transmit actions to all players in a game.</li>
	</ul>
	<h3>Previous Versions</h3>
	<div>
		<a href="/projects/jsrts/alpha5/index.php">Alpha 5</a>
	</div>
	<div>
		<a href="/projects/jsrts/alpha4/index.php">Alpha 4</a>
	</div>
	<div>
		<a href="/projects/jsrts/alpha3/index.php">Alpha 3</a>
	</div>
	<div>
		<a href="/projects/jsrts/alpha2/index.php">Alpha 2</a>
	</div>
	<div>
		<a href="/projects/jsrts/alpha1/index.php">Alpha 1</a>
	</div>
<?php
	$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>
