<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	
	$ArticleID = 44;
	$article = LoadClass(SiteRoot . '/modules/classes/articles/Article');
	
	$article->load($ArticleID);
	
	
	ob_start();
?>
	<link rel="stylesheet" type="text/css" href="jquery-ui-1.8.4.custom.css"/>
	<script type="text/javascript">
		htmledit = function(Settings){
			var init = function(){
				var ReplaceObject = $(Settings.Editor);
				Settings.parent = ReplaceObject.parent();
				Settings.height = ReplaceObject.height();
				Settings.width = ReplaceObject.width();
				Settings.origContent = ReplaceObject.html();
				//this.Editor = $('<div></div>');
				Editor.css({'position':'relative','height':Settings.height + 'px','width':Settings.width + 'px','border':'1px solid #000','overflow':'scroll'});
				Editor.insertAfter(ReplaceObject);
				ReplaceObject.remove();
				
				/* Detect Key Presses */
				$(document).bind('keypress',function(evt){
					//alert(evt.keyCode);
					
					if(evt.shiftKey){
						var adjustment = 5;
					}else{
						var adjustment = 1;
					}
					var allowKeyPress = true;
					switch(evt.keyCode){
						case 37:
							$('.Selected').each(function(){
								var NewLeft = ($(this).css('left').split('p')[0] * 1) - adjustment;
								//alert(NewLeft);
								$(this).css('left',NewLeft + 'px');
							});
							allowKeyPress = false;
							break;
						case 38:
							$('.Selected').each(function(){
								var NewTop = ($(this).css('top').split('p')[0] * 1) - adjustment;
								//alert(NewTop);
								$(this).css('top',NewTop + 'px');
							});
							allowKeyPress = false;
							break;
						case 39:
							$('.Selected').each(function(){
								var NewLeft = ($(this).css('left').split('p')[0] * 1) + adjustment;
								//alert(NewLeft);
								$(this).css('left',NewLeft + 'px');
							});
							allowKeyPress = false;
							break;
						case 40:
							$('.Selected').each(function(){
								var NewTop = ($(this).css('top').split('p')[0] * 1) + adjustment;
								//alert(NewTop);
								$(this).css('top',NewTop + 'px');
							});
							allowKeyPress = false;
							break;
						case 46:
							$('.Selected').remove();
							allowKeyPress = false;
							break;
					}
					return allowKeyPress;
					/*for(prop in evt){
						alert(prop + ': ' + evt[prop]);
					}*/
				});
				
				/* Add Styles */
				$('body').append($('<style type="text/css">' +
								    	'.MoveAble{z-index:500; outline:1px solid #CCC; cursor:move;}' +
										'.Selected{outline:1px solid #F00;}' +
										'.ToolBar{z-index:1000; border:1px solid #000; height:30px; width:200px; position:absolute; top:0px; left:0px; background-color:rgba(200,200,200,0.7); font-size:9px;}' +
										'.ToolButton{border:1px solid #AAA; height:22px; padding:3px 3px; display:inline-block; vertical-align:top;}' +
									'</style>'));
				
				/* Select Item(s) */
				$('.MoveAble').live('mousedown',function(evt){
					if(!evt.shiftKey){
						$(".MoveAble").removeClass('Selected');
					}
					$(this).addClass('Selected');
				});
				
				/* Divide Up Elements */
				/*$('.EditorPart').live('dblclick',function(){
					DivideContent($(this));
					if($(this).children().length > 0){
						$(this).remove();
					}
					$(".MoveAble").draggable();
				});*/
				
				TransformContent(Settings.origContent);
				
				/* Add Tools */
				var Tools = $('<div></div>');
				Tools.addClass('ToolBar');
				Tools.draggable();
				
				var AddItem = $('<div></div>');
				AddItem.addClass('ToolButton');
				AddItem.text('Add');
				AddItem.click(function(){
					var NewItem = $('<div></div>');
					NewItem.css({'height':'20px','width':'50px','left':'50px','top':'50px'});
					NewItem.addClass('MoveAble');
					NewItem.text('New Item');
					Editor.append(NewItem);
					NewItem.draggable();
				});
				
				var DivideItem = $('<div></div>');
				DivideItem.addClass('ToolButton');
				DivideItem.text('Divide');
				DivideItem.click(function(){
					DivideContent($('.Selected'));
					/*if($('.Selected').children().length > 0){
						$('.Selected').remove();
					}*/
					$(".MoveAble").draggable();
				});
				
				var ResizeItem = $('<div></div>');
				ResizeItem.addClass('ToolButton');
				ResizeItem.text('Resize');
				ResizeItem.click(function(){
					var SelectedItems = $('.Selected:not(.Resizing)');
					SelectedItems.draggable('destroy');
					SelectedItems.resizable();
					$('.Selected:not(.Resizing)').addClass('Resizing');
					if($('#DoneResizeItem').length == 0){
						var DoneResizeItem = $('<div></div>');
						DoneResizeItem.attr('id','DoneResizeItem');
						DoneResizeItem.addClass('ToolButton');
						DoneResizeItem.text('Done');
						DoneResizeItem.click(function(){
							$('.Resizing').resizable('destroy');
							$('.Resizing').draggable();
							$('.Resizing').removeClass('Resizing');
							$(this).remove();
						});
						Tools.append(DoneResizeItem);
					}
				});
				
				var EditItem = $('<div></div>');
				EditItem.addClass('ToolButton');
				EditItem.text('Edit');
				EditItem.click(function(){
					var SelectedItems = $('.Selected:not(.Editing)');
					SelectedItems.draggable('destroy');
					SelectedItems.each(function(){
						var SelectedItem = $(this);
						
						var FindTextNodes = function(Item){
							if(Item.contents().length == 1 && Item.contents()[0].nodeName == '#text'){
								var ItemText = Item.text();
								
								Item.html('<textarea style="height:' + Item.height() + 'px; width:' + Item.width() + 'px;">' + ItemText + '</textarea>');
								
								//Item.replaceWith('<textarea style="' + Item.attr('style') + '" class="' + Item.attr('class') + '">' + Item.text() + '</textarea>');
							}else if(Item.children().length > 0){
								Item.children().each(function(){
									FindTextNodes($(this));
								});
							}
						}
						FindTextNodes(SelectedItem);
					});
					/* this line doesn't work with the SelectedItems variable */
					$('.Selected').addClass('Editing');
					
					if($('#DoneEditItem').length == 0){
						var DoneEditItem = $('<div></div>');
						DoneEditItem.attr('id','DoneEditItem');
						DoneEditItem.addClass('ToolButton');
						DoneEditItem.text('Done');
						DoneEditItem.click(function(){
							$('.Editing').each(function(){
								var SelectedItem = $(this);
								/*if(SelectedItem[0].nodeName == 'TEXTAREA'){
									SelectedItem.replaceWith('<div style="' + SelectedItem.attr('style') + '" class="' + SelectedItem.attr('class') + '">' + SelectedItem.val() + '</div>');
								}*/
								
								var FindTextAreas = function(Item){
									if(Item.children().length == 1 && Item.children()[0].nodeName == 'TEXTAREA'){
										var ItemText = $(Item.children()[0]).val();
										
										Item.html(ItemText);
										
										//Item.replaceWith('<textarea style="' + Item.attr('style') + '" class="' + Item.attr('class') + '">' + Item.text() + '</textarea>');
									}else if(Item.children().length > 0){
										Item.children().each(function(){
											FindTextAreas($(this));
										});
									}
								}
								FindTextAreas(SelectedItem);
								
							});
							$('.Editing').draggable();
							$('.Editing').removeClass('Editing');
							$(this).remove();
						});
						Tools.append(DoneEditItem);
					}
				});
				
				
				
				Editor.append(Tools);
				Tools.append(AddItem);
				Tools.append(DivideItem);
				Tools.append(EditItem);
				Tools.append(ResizeItem);
				
				//var HiddenTextArea = $('<textarea></textarea>');
				//HiddenTextArea.css({'position':'absolute','height':'0px','width':'0px'});
				//HiddenTextArea
			}
			
			/* Get Content From Text Area And Divide once */
			var TransformContent = function(origContent){
				var unescapedContent = origContent.replace(/&quot;/g,'"').replace(/&amp;/g,"&").replace(/&lt;/g,"<").replace(/&gt;/g,">");
				
				Editor.html(unescapedContent);
				DivideContent(Editor);
			}
			
			/* Divide up elements of a part */
			var DivideContent = function(Part){
				if(!Part.hasClass('Divisible')){
					return false;
				}
				var Container = $('<div></div>');
				Container.css('position','relative');
				
				/* Find Position of children of Part */
				Part.children().each(function(){
					var Child = $(this);
					var NextParent = Child.parent();
					var NewTop = Child.position().top;
					var NewLeft = Child.position().left;
					while(NextParent.width() != Settings.width && NextParent.height() != Settings.height){
						NewTop += NextParent.position().top;
						NewLeft += NextParent.position().left;
						NextParent = NextParent.parent();
					}
					Child.css({'height':Child.css('height'),'width':Child.css('width'),'left':NewLeft + 'px','top':NewTop + 'px'});
					Child.addClass('Divisible');
					Child.addClass('MoveAble');
					if(Child[0].nodeName == 'TABLE'){
						Child.removeClass('Divisible');
					}
					if(Child.children().length == 0){
						Child.removeClass('Divisible');
					}
					Child.contents().each(function(){
						if(this.nodeName == '#text' && $.trim($(this).text()).length > 0){
							Child.removeClass('Divisible');
						}
						//alert($.trim($(this).text()).length);
					});
					/*if(Child.contents().length > Child.children().length){
						Child.removeClass('Divisible');
					}*/
				});
				/* Put children in container */
				Part.children().each(function(){
					var Child = $(this);
					if(Child.html().length > 0 || Child[0].nodeName == 'IMG'){
						Child.css({'position':'absolute'});
						Container.append(Child);
					}
				});
				if(!Part.hasClass('MainEditor')){
					Part.remove();
				}
				
				/* Add found parts to editor */
				Editor.append(Container.html());
			}
			
			/* Prepare content for saving */
			var PrepareContent = function(){
				
			}
			
			var that = this;
			var Editor = $('<div></div>');
			Editor.addClass('Divisible');
			Editor.addClass('MainEditor');
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
	<div>
		<span onclick="alert(DivideContent);">Title:</span><br />
		<input type="text" name="Title" />
	</div>
	<div>
		Content:<br />
		<textarea id="Content" name="Content">
			<div>
				<div style="display:inline-block;">
					I am attempting to build something that works more like a graphics editing program like Fireworks or Photoshop. You can create text blocks and add images and then drag them around to create the layout you want. Then the editor will generate html to create this layout.
				</div>
				<div style="display:inline-block;">
					I have started working on a <span style="color:#F00;">javascript</span> html editor. I have used other editors <a href="#something">similar</a> like TinyMCE. They usually try to present users with an interface similar to Word or other text editors. In my experience this often isn't very compatible with the way html works. Web pages can have much more than just paragraphs of text with pictures.
				</div>
			</div>
			<div>
				The project is in the very early stages now but I hope to work on it this winter. You can see an example of the javascript html editor here.
			</div>
			<?php
				/*echo $article->get('Content');*/
			?>
		</textarea>
	</div>
<?php
	$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>
