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
								'.ToolBar{z-index:1000; border:1px solid #000; height:20px; width:280px; position:absolute; top:0px; left:0px; background-color:rgba(200,200,200,0.7); font-size:9px;}' +
								'.ToolButton{cursor:pointer; border:1px solid #AAA; height:12px; padding:3px 3px; display:inline-block; vertical-align:top;}' +
								'.Resizing{cursor:auto;}' +
								'.Editing{cursor:auto;}' +
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
			NewItem.css({'position':'absolute','height':'20px','width':'50px','left':'50px','top':'50px'});
			NewItem.addClass('MoveAble');
			NewItem.addClass('TextBlock');
			//NewItem.text('New Item');
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
		
		var CombineItem = $('<div></div>');
		CombineItem.addClass('ToolButton');
		CombineItem.text('Combine');
		CombineItem.click(function(){
			CombineItems();
			
			//$(".MoveAble").draggable();
		});
		
		var ResizeItem = $('<div></div>');
		ResizeItem.addClass('ToolButton');
		ResizeItem.text('Resize');
		ResizeItem.click(function(){
			var SelectedItems = $('.Selected:not(.Resizing):not(.Editing)');
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
				
				if(SelectedItem.hasClass('TextBlock')){
					SelectedItem.html('<textarea style="height:' + SelectedItem.height() + 'px; width:' + SelectedItem.width() + 'px;">' + $.trim(SelectedItem.html()) + '</textarea>');
					SelectedItem.addClass('Editing');
				}
			});
			
			if($('#DoneEditItem').length == 0){
				var DoneEditItem = $('<div></div>');
				DoneEditItem.attr('id','DoneEditItem');
				DoneEditItem.addClass('ToolButton');
				DoneEditItem.text('Done');
				DoneEditItem.click(function(){
					$('.Editing').each(function(){
						var SelectedItem = $(this);
						var ItemText = $(SelectedItem.children()[0]).val();		
						SelectedItem.html(ItemText);
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
		Tools.append(CombineItem);
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
					Child.addClass('TextBlock');
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
	
	var CombineItems = function(){
		var SelectedItems = $('.Selected:not(.Resizing):not(.Editing)');
		if(SelectedItems.length <= 1){
			return false;
		}
		var MinTop = Editor[0].scrollHeight;
		var MinLeft = Editor[0].scrollWidth;
		var MaxBottom = 0;
		var MaxRight = 0;
		
		var DimensionArray = new Array();
		
		SelectedItems.each(function(){
			var Item = $(this);
			DimensionArray.push({Item:Item,inserted:false,top:parseInt(Item.css('top')),left:parseInt(Item.css('left')),width:Item.width(),height:Item.height()});
			if(parseInt(Item.css('top'),10) < MinTop){
				MinTop = parseInt(Item.css('top'),10);
			}
			if(parseInt(Item.css('left'),10) < MinLeft){
				MinLeft = parseInt(Item.css('left'),10);
			}
			if(parseInt(Item.css('top'),10) + Item.height() > MaxBottom){
				MaxBottom = parseInt(Item.css('top'),10) + Item.height();
			}
			if(parseInt(Item.css('left'),10) + Item.width() > MaxRight){
				MaxRight = parseInt(Item.css('left'),10) + Item.width();
			}
		});
		
		NewHeight = MaxBottom - MinTop;
		NewWidth = MaxRight - MinLeft;
		
		var NewItem = $('<div></div>');
		NewItem.css({'position':'absolute','height': + NewHeight + 'px','width':NewWidth + 'px','left':MinLeft + 'px','top':MinTop + 'px'});
		NewItem.addClass('MoveAble');
		Editor.append(NewItem);
		NewItem.draggable();
		
		/*var Positioning = true;
		
		while(Positioning){
			Positioning = false;
			*/
			/* find item to insert */
			/*for(var i in DimensionArray){
				var I = DimensionArray[i];
				
			}
			*/
			/*  check if all items have been inserted */
			/*for(var i in DimensionArray){
				var I = DimensionArray[i];
				if(I.inserted == false){
					Positioning = true;
				}
			}
		}*/
		
		
	}
	
	var that = this;
	var Editor = $('<div></div>');
	Editor.addClass('Divisible');
	Editor.addClass('MainEditor');
	init();
	$(".MoveAble").draggable();
}