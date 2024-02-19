<?php
	include("/var/www/html/modules/AppInit.php");
	
	ob_start();
?>
	<script type="text/javascript">
		htmledit = function(Settings){
			var init = function(){
				//var Settings = Settings;
				var ReplaceObject = $(Settings.Editor);
				Settings.parent = ReplaceObject.parent();
				Settings.height = ReplaceObject.height();
				Settings.width = ReplaceObject.width();
				Settings.origContent = ReplaceObject.html();
				Editor = $('<div></div');
				Editor.css({'position':'relative','height':Settings.height + 'px','width':Settings.width + 'px','border':'1px solid #000','overflow':'scroll'});
				Settings.parent.html('');
				Settings.parent.append(Editor);
				
				$('.EditorPart').live('click',function(){
					var Container = $('<div></div>');
					Container.css('position','relative');
					DivideContent($(this),Container);
					if($(this).children().length > 0){
						$(this).remove();
					}
					Editor.append(Container.html());
					$(".MoveAble").draggable();
					/*$('.EditorPart').each(function(){
						if($(this).contents ().length == 0){
							$(this).remove();
						}
					});*/
					//alert(Editor.html());
				});
				
				TransformContent(Settings.origContent);
				//alert(Editor.height());
			}
			var TransformContent = function(origContent){
				var unescapedContent = origContent.replace(/&quot;/g,'"').replace(/&amp;/g,"&").replace(/&lt;/g,"<").replace(/&gt;/g,">");
				var Container = $('<div></div>');
				Container.css('position','relative');
				//Container.html(unescapedContent);
				Editor.html(unescapedContent);
				DivideContent(Editor,Container);
				//FindContent(Editor,Container);
				Editor.html(Container.html());
			}
			var DivideContent = function(Editor,Container){
				
				Editor.children().each(function(){
					//alert($(this).position().top);
					//var NewItem = $('<div></div>');
					//NewItem.html($(this).clone());
					var NextParent = $(this).parent();
					var NewTop = $(this).position().top;
					var NewLeft = $(this).position().left;
					while(NextParent.width() != Settings.width && NextParent.height() != Settings.height){
						NewTop += NextParent.position().top;
						NewLeft += NextParent.position().left;
						NextParent = NextParent.parent();
					}
					$(this).css({'height':$(this).css('height'),'width':$(this).css('width'),'left':NewLeft + 'px','top':NewTop + 'px','outline':'1px solid #CCC'});
					$(this).addClass('EditorPart');
					$(this).addClass('MoveAble');
					$('table').removeClass('EditorPart');
					//Container.append($(this));
				});
				Editor.children().each(function(){
					if($(this).html().length > 0){
						$(this).css({'position':'absolute','display':'block'});
						Container.append($(this));
					}
				});
				
			}
			/*var FindContent = function(Editor,Container){
				Editor.children().each(function(){
					if($(this).html().length > 0 && $(this).children().length <= 1){
						Container.append($('<div></div>').html($(this).html()).css({'position':'absolute','height':$(this).css('height'),'width':$(this).css('width'),'left':$(this).position().left + 'px','top':$(this).position().top + 'px','border':'1px solid red'}));
					}
					FindContent($(this),Container);
				});
			}*/
			init();
		}
		$(document).ready(function(){
			myeditor = new htmledit({
				'Editor':'#Content'
			});
			$(".MoveAble").draggable();
		});
	</script>
	<style type="text/css">
		#Content{
			height:800px;
			width:600px;
		}
	</style>
	<textarea id="Content" name="Content">
		<div>
			<div style="display:inline-block; width:300px;"></div>
			<div style="display:inline-block; width:100px;">Hello World</div>
			<div style="display:inline-block; width:200px;">
				<div style="display:inline-block; width:40px;"></div>
				<div style="display:inline-block; width:100px;">Something</div>
				<div style="display:inline-block; width:60px;"></div>
			</div>
		</div>
		<table cellpadding="0" cellspacing="0" border="0">				
			<tr>
				<td>Table Content</td>
				<td>Another Cell</td>
			</tr>
			<tr>
				<td>Hello</td>
				<td>Test</td>
			</tr>
		</table>
	</textarea>

<?php
	$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>
