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
	<link rel="stylesheet" type="text/css" href="common/jsrts.css"/>
	<?php /*?><form method="post" enctype="multipart/form-data" name="test" action="ajax/index.php">
		<input type="text" name="Action" />
		<input type="text" name="PlayerID" />
		<input type="submit" name="Go" />
	</form><?php */?> 
	<p>Enter your name below and Create or Join a game.</p>
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
		<a href="/projects/jsrts/alpha6/index.php">Alpha 6</a>
	</div>
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
