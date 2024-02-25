<?php 
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	header('Content-type: text/javascript');
?>
$(function(){
	setInterval(function(){
		var randomLeft = Math.round(Math.random() * $(window).width());
		var randomTop = Math.round(Math.random() * $(document).height());
		$('body').css('background-position',randomLeft + 'px ' + randomTop + 'px');
	},6000);
});