<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	
	ob_start();
?>
	<script type="text/javascript" src="jscolor.js"></script>
	<script type="text/javascript">
		/*$(document).ready(function(){
			var image = document.getElementById('myImage');
			var canvas = document.getElementById('myCanvas');
			var ctx = canvas.getContext('2d');
			ctx.drawImage(image,200,200,100,100);
			
			ctx.drawImage(image,100,100,50,50,250,290,50,50);
			
			ctx.drawImage(canvas,200,200,150,150,350,350,200,200);
			
			ctx.fillStyle = "rgb(200,0,0)";  
			ctx.fillRect (10, 10, 55, 50);  
			
			ctx.fillStyle = "rgba(0, 0, 200, 0.5)";  
			ctx.fillRect (30, 30, 55, 50);  
		});*/
		
		Drawing = {startX:0,startY:0,Drawing:false,DrawColor:'rgba(0,0,0,1.0)',LineWidth:1,DrawLines:false};
		
		//startX = 0;
		//startY = 0;
		//Drawing = false;
		
		function setStart(evt,canvas){
			var e = evt || window.event;
			var offset = $('#myCanvas').offset();
			Drawing.startX = e.pageX - offset.left;
			Drawing.startY = e.pageY - offset.top;
			Drawing.Drawing = true;
		}
		
		function stopDrawing(evt,canvas){
			Drawing.Drawing = false;
		}
		
		function drawLine(evt,canvas){
			if(Drawing.Drawing){
				var e = evt || window.event;
				
				var offset = $('#myCanvas').offset();
				//alert(e.pageX + ' ' + e.pageY);
				
				var ctx = canvas.getContext('2d');
				
				ctx.strokeStyle = Drawing.DrawColor;
				ctx.lineWidth = Drawing.LineWidth;
				ctx.lineCap = 'round';
				
				ctx.beginPath();  
				ctx.moveTo(Drawing.startX,Drawing.startY);  
				ctx.lineTo(e.pageX - offset.left,e.pageY - offset.top);   
				ctx.stroke();
				
				// Comment out these two lines to do line drawing from starting point.
				if(!Drawing.DrawLines){
					Drawing.startX = e.pageX - offset.left;
					Drawing.startY = e.pageY - offset.top;
				}
				
				//ctx.fillStyle = "rgb(200,0,0)";  
				//ctx.fillRect (e.pageX, e.pageY, 2, 2); 
			}
		}
		
		function getImage(){
			var canvas = document.getElementById('myCanvas');
			var ctx = canvas.getContext('2d');
			
			//alert(canvas.toDataURL());
			
			
			
			//alert(ctx.getImageData(1, 1, 599, 499));
			//var ImageData = ctx.getImageData(1, 1, 599, 499);
			
			
			
			$('#ResultImage').attr('src',canvas.toDataURL());
			
			//alert(ImageData.data[4]);
			
			//$('#Result').html(ImageData.toString());
			
			/*for(prop in ImageData){
				alert(prop);
			}*/
		}
		
		function lightenImage(){
			var canvas = document.getElementById('myCanvas');
			var ctx = canvas.getContext('2d');
			
			var ImageData = ctx.getImageData(1, 1, 599, 499);
			
			for(index in ImageData.data){
				if(index % 4 != 3){
					ImageData.data[index] += 10;
				}
				//$('#DataNumbers').append(' ' + ImageData.data[i]);
			}
			
			ctx.putImageData(ImageData,1,1);
		}
		
		function fadeImage(){
			var canvas = document.getElementById('myCanvas');
			var ctx = canvas.getContext('2d');
			var ImageData = ctx.getImageData(1, 1, 599, 499);
			
			for(var i = 0; i < ImageData.height; i++){
				for(var j = 0; j < ImageData.width; j++){
					var index = (i*4)*ImageData.width+(j*4);
					var red = index;	  
					var green = index + 1;
					var blue = index + 2;	  
					var alpha = index + 3;
					
					ImageData.data[alpha] = (1 - (i/ImageData.height)) * 255;
					
					//ImageData.data[red] += (i/ImageData.height) * 255;
					//ImageData.data[green] += (i/ImageData.height) * 255;
					//ImageData.data[blue] += (i/ImageData.height) * 255;
					
				}
			}
			
			ctx.putImageData(ImageData,1,1);
			
		}
		
		function adjustColor(color,direction){
			var canvas = document.getElementById('myCanvas');
			var ctx = canvas.getContext('2d');
			var ImageData = ctx.getImageData(1, 1, 599, 499);
			
			for(var i = 0; i < ImageData.height; i++){
				for(var j = 0; j < ImageData.width; j++){
					var index = (i*4)*ImageData.width+(j*4);
					var red = index;	  
					var green = index + 1;
					var blue = index + 2;	  
					var alpha = index + 3;
					
					switch(color){
					case 'red':
						ImageData.data[red] += 10 * direction;
						break
					case 'green':
						ImageData.data[green] += 10 * direction;
						break
					case 'blue':
						ImageData.data[blue] += 10 * direction;
						break
					}
					
					//ImageData.data[red] += (i/ImageData.height) * 255;
					//ImageData.data[green] += (i/ImageData.height) * 255;
					//ImageData.data[blue] += (i/ImageData.height) * 255;
					
				}
			}
			
			ctx.putImageData(ImageData,1,1);
			
		}
		
		function loadImage(){
			var image = document.getElementById('myImage');
			var canvas = document.getElementById('myCanvas');
			var ctx = canvas.getContext('2d');
			ctx.drawImage(image,1,1,599,499);
		}
		
		function setColor(){
			var Red = $('input[name=Red]').val();
			var Green = $('input[name=Green]').val();
			var Blue = $('input[name=Blue]').val();
			var Alpha = $('input[name=Alpha]').val();
			
			$('#CurrentColor').css('background-color','rgb(' + Red + ',' + Green + ',' + Blue + ')');
			
			Drawing.DrawColor = 'rgba(' + Red + ',' + Green + ',' + Blue + ',' + Alpha + ')';
			//alert(Drawing.DrawColor);
		}
		
		function setColor2(colorInput){
			
			//alert(colorInput.color.rgb[0] * 255);
			
			var Red = colorInput.color.rgb[0] * 255;
			var Green = colorInput.color.rgb[1] * 255;
			var Blue = colorInput.color.rgb[2] * 255;
			var Alpha = '1.0';
			
			//document.getElementById('red').value = this.color.rgb[0]*100 + '%';
			//document.getElementById('grn').value = this.color.rgb[1]*100 + '%';
			//document.getElementById('blu').value = this.color.rgb[2]*100 + '%';
			//document.getElementById('hue').value = this.color.hsv[0]* 60 + '&deg;';
			//document.getElementById('sat').value = this.color.hsv[1]*100 + '%';
			//document.getElementById('val').value = this.color.hsv[2]*100 + '%';
			
			Drawing.DrawColor = 'rgba(' + Math.round(Red) + ',' + Math.round(Green) + ',' + Math.round(Blue) + ',' + Alpha + ')';
			colorInput.color.hidePicker();
		}
		
		function setWidth(){
			var NewWidth = $('input[name=Width]').val();
			Drawing.LineWidth = NewWidth;
		}
		
		function toggleDrawLines(){
			if(Drawing.DrawLines){
				Drawing.DrawLines = false;
			}else{
				Drawing.DrawLines = true;
			}
		}
		
		/*function save(){
			var canvas = document.getElementById('myCanvas');
			var ctx = canvas.getContext('2d');
			ctx.save();
		}
		
		function restore(){
			var canvas = document.getElementById('myCanvas');
			var ctx = canvas.getContext('2d');
			ctx.restore();
		}*/
		function transformCanvas(){
			var canvas = document.getElementById('myCanvas');
			var ctx = canvas.getContext('2d');
			
			var ImageData = ctx.getImageData(1, 1, 599, 499);
			
			ctx.clearRect(1,1,599,499);
			ctx.setTransform(1,0.1,0,1,0,0);
			
			var otherCanvas = document.getElementById('myOtherCanvas');
			var otherCtx = otherCanvas.getContext('2d');
			
			otherCtx.putImageData(ImageData,1,1);
			
			ctx.drawImage(otherCanvas,1,1,599,499);
			
			//ctx.putImageData(ImageData,1,1);
		}
		
		function clearTransform(){
			var canvas = document.getElementById('myCanvas');
			var ctx = canvas.getContext('2d');
			
			var ImageData = ctx.getImageData(1, 1, 599, 499);
			
			ctx.clearRect(1,1,599,499);
			ctx.setTransform(1,0,0,1,0,0);
			
			var otherCanvas = document.getElementById('myOtherCanvas');
			var otherCtx = otherCanvas.getContext('2d');
			
			otherCtx.putImageData(ImageData,1,1);
			
			ctx.drawImage(otherCanvas,1,1,599,499);
		}
	</script>
	<style type="text/css">
		body{
			margin:0px;
		}
		canvas{
			border:1px solid #000;
			/*background-color:#999;*/
		}
		#Controls div{
			cursor:pointer;
		}
	</style>
	<img id="myImage" src="/images/logo.jpg" style="display:none;" />

	<canvas id="myCanvas" height="500" width="600" onmousedown="setStart(event, this)" onmouseup="stopDrawing(event, this);" onmousemove="drawLine(event, this);"></canvas>
	<canvas id="myOtherCanvas" height="500" width="600" style="display:none;"></canvas>
	<div id="Controls">
		<div onClick="loadImage();">Load Image</div>
		<div onClick="getImage();">Get Image</div>
		<div onClick="lightenImage();">Lighten Image</div>
		<div onClick="fadeImage();">Fade Image</div>

		<div>More: <span onClick="adjustColor('red',1);">Red</span> <span onClick="adjustColor('green',1);">Green</span> <span onClick="adjustColor('blue',1);">Blue</span></div>
		<div>Less: <span onClick="adjustColor('red',-1);">Red</span> <span onClick="adjustColor('green',-1);">Green</span> <span onClick="adjustColor('blue',-1);">Blue</span></div>
		<?php /*?><div>Color: <input type="text" name="Red" value="0"> <input type="text" name="Green" value="0"> <input type="text" name="Blue" value="0"> <input type="text" name="Alpha" value="1.0"> <span onClick="setColor();">Set Color</span><span id="CurrentColor">1</span></div><?php */?>

		<div>Width: <input type="text" name="Width" value="1"> <span onClick="setWidth();">Set Width</span></div>
		<div><button type="button" onClick="toggleDrawLines();">Toggle Lines</button></div>
		<div><button type="button" onClick="transformCanvas();">Transform</button></div>
		<div><button type="button" onClick="clearTransform();">Clear Transform</button></div>
		<div>
			Color: <input class="color" id="myColor" onchange="setColor2(this);">
		</div>
	</div>
	<div id="DataNumbers"></div>

	<img src="" id="ResultImage">
<?php
	$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>