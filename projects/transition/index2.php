<?php
	include("/var/www/html/modules/AppInit.php");
	
	ob_start();
?>
	<style type="text/css">
		.movingDiv {
			/*background-image:url(http://www.mandjscreations.com/life/fish.gif);*/
			position:relative;
			/*top:50px;
			left:0px;
			width:116px;
			height:38px;*/
			-webkit-transition: top 6s ease, left 6s ease, -webkit-transform 2s ease;
			z-index:100;
		}
	</style>
	<script type="text/javascript">
		function Move(){
			$('body > div > div, body > div > div > div').each(function(){
				
				var numericTop = this.style.top.split('p')[0];
				var numericLeft = this.style.left.split('p')[0];
				
				var randomLeft = Math.round(Math.random() * maxWidth);
				this.style.left = randomLeft + 'px';
				var randomTop = Math.round(Math.random() * maxHeight);
				this.style.top = randomTop + 'px';
				
				calculateRotation(this,randomLeft,numericLeft,randomTop,numericTop);
			});
			moveTimeout = setTimeout(Move,4000);
		}
		function calculateRotation(fish,newLeft,oldLeft,newTop,oldTop){
			
			var NewRotation = Math.atan(Math.abs(newTop - oldTop) / Math.abs(newLeft - oldLeft));
			NewRotation = NewRotation * (180 / Math.PI);
			
			if(newTop > oldTop){
				if(newLeft > oldLeft){
					
				}else{
					NewRotation = 180 - NewRotation;
				}
			}else{
				if(newLeft > oldLeft){
					NewRotation = 360 - NewRotation;
				}else{
					NewRotation = NewRotation + 180;				
				}
			}
			
			$(fish).css('-webkit-transform','rotate(' + NewRotation + 'deg)');
		}
		function start(){
			maxWidth = $(window).width();
			maxHeight = $(window).height();
			$('body > div > div, body > div > div > div').addClass('movingDiv');
			Move();
		}
		$(document).ready(function(){
			setTimeout(start,2000);
			$('html').mousedown(function(){
				clearTimeout(moveTimeout);
				$('body > div > div, body > div > div > div').removeClass('movingDiv');
				$('body > div > div, body > div > div > div').css('left','0px');
				$('body > div > div, body > div > div > div').css('top','0px');
				$('body > div > div, body > div > div > div').css('-webkit-transform','rotate(0deg)');
				setTimeout(start,10000);
			});
		});
	</script>

	<p>The page is falling apart.</p>
<?php
	$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>
