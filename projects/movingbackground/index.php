<?php
	include("/var/www/html/modules/AppInit.php");
	
	ob_start();
?>
	<script type="text/javascript">
		$(function(){
			setInterval(function(){
				var randomLeft = Math.round(Math.random() * $(window).width());
				var randomTop = Math.round(Math.random() * $(document).height());
				$('body').css('background-position',randomLeft + 'px ' + randomTop + 'px');
			},6000);
		});
	</script>
	<style type="text/css">
		body{
			background-image:url(/images/menu_header.png);
			background-repeat:no-repeat;
			-webkit-transition: background-position 6s ease;
		}
	</style>
<?php
	$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>