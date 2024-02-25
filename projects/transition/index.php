<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	
	ob_start();
?>
	<style type="text/css">
		.bodyContent div {
			background-image:url(/projects/life/fish.gif);
			position:absolute;
			top:50px;
			left:0px;
			width:116px;
			height:38px;
			-webkit-transition: top 6s ease, left 6s ease, -webkit-transform 2s ease;
			-moz-transition: top 6s ease, left 6s ease, -moz-transform 2s ease;
			z-index:100;
		}
		.bodyContent div.fast{
			-webkit-transition: top 2s ease, left 2s ease, -webkit-transform 0.5s ease;
			-moz-transition: top 2s ease, left 2s ease, -moz-transform 0.5s ease;
		}
		.bodyContent div.outLine{
			border:1px solid #F00;
		}
	</style>
	<script type="text/javascript">
		function Move(){
			$('.bodyContent div').each(function(){
				
				var numericTop = this.style.top.split('p')[0];
				var numericLeft = this.style.left.split('p')[0];
				
				var randomLeft = Math.round(Math.random() * $(window).width());
				this.style.left = randomLeft + 'px';
				var randomTop = Math.round(Math.random() * $(document).height());
				this.style.top = randomTop + 'px';
				
				calculateRotation(this,randomLeft,numericLeft,randomTop,numericTop);
			});
			setTimeout(Move,4000);
		}
		function removeFast(){
			$('.bodyContent div').removeClass('fast');
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
			$(fish).css('-moz-transform','rotate(' + NewRotation + 'deg)');
		}
		$(document).ready(function(){
			Move();
			TotalClicks = 0;
			Code = '';
			StartTime = Date.now();
			$('.bodyContent div').mouseover(function(){
				$(this).addClass('fast');
				$(this).addClass('outLine');
				
				var numericTop = this.style.top.split('p')[0];
				var numericLeft = this.style.left.split('p')[0];
				
				randomLeft = Math.round(Math.random() * 700);
				this.style.left = randomLeft + 'px';
				randomTop = Math.round(Math.random() * 700);
				this.style.top = randomTop + 'px';
				
				calculateRotation(this,randomLeft,numericLeft,randomTop,numericTop);
				
				setTimeout(removeFast,3000);
			}).mousedown(function(){
				$(this).remove();
			}).mouseout(function(){
				$(this).removeClass('outLine');
			});
			$('html').mousedown(function(){
				if(StartTime == 0){
					StartTime = Date.now();
				}
				var DeadFish = 50 - $('.bodyContent div').length;
				if(DeadFish == 50){
					$('html').unbind('mousedown');
				}
				TotalClicks ++;
				var Rate = Math.round((DeadFish / TotalClicks) * 100);
				var DisplayTime = Math.round((Date.now() - StartTime) / 1000);
				$('#stats').html(DeadFish + ' dead fish from ' + TotalClicks + ' clicks ' + Rate + '% ' + DisplayTime + ' sec');
			}).keypress(function(event){
				Code += String.fromCharCode(event.which);
				if(event.which == 17){
					Code = '';
				}
				if(Code == 'abc'){
					$('.bodyContent div').unbind('mouseover');
					$('.bodyContent div').mouseover(function(){
						$(this).addClass('outLine');
					});
					alert('Code Entered');
					Code = '';
				}
			});
		});
	</script>

	<span id="stats"></span>
	<p>Click on the fish to remove them.  Try to remove all the fish with the fewest clicks possible.  This pages uses transitions css that will only work in webkit based browsers like Chrome and Safari.</p>
	<p>The page has been updated so it will work with Firefox 4.</p>
	<p><a href="index2.php">Try this page for something interesting.</a></p>
	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>

	<div></div>
<?php
	$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>
