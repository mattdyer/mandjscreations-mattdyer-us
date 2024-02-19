htmledit = function(Settings){
	var init = function(){
		
		Settings.parent = ReplaceObject.parent();
		Settings.height = ReplaceObject.height();
		Settings.width = ReplaceObject.width();
		Settings.origContent = ReplaceObject.html();
		//this.Editor = $('<div></div>');
		Editor.css({'position':'relative','height':Settings.height + 'px','width':Settings.width + 'px','border':'1px solid #000','overflow':'scroll'});
		Editor.insertAfter(ReplaceObject);
		ReplaceObject.hide();
		
		/* Detect Key Presses */
		$(document).bind('keypress',function(evt){
			//alert(evt.keyCode);
			
			var allowKeyPress = true;
			
			if(!EditingText){
				if(evt.shiftKey){
					var adjustment = 5;
				}else{
					var adjustment = 1;
				}
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
								'.ToolBar{z-index:1000; border:1px solid #000; height:20px; width:280px; position:relative; top:1px; background-color:rgba(200,200,200,0.7); font-size:9px;}' +
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
		Tools.addClass('ToolBar');
		//Tools.draggable();
		
		var FinishItem = $('<div></div>');
		FinishItem.addClass('ToolButton');
		FinishItem.text('Finish');
		FinishItem.click(function(){
			FinishEditing();
			//init();
			//$(".MoveAble").draggable();
		});
		
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
			CombineContent();
			
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
			//EditingText = true;
			var SelectedItems = $('.Selected:not(.Editing)');
			
			SelectedItems.each(function(){
				var SelectedItem = $(this);
				
				if(SelectedItem.hasClass('TextBlock')){
					SelectedItem.draggable('destroy');
					var TextArea = $('<textarea></textarea>');
					TextArea.css({height:SelectedItem.height() + 'px',
								  width:SelectedItem.width() + 'px',
								  position:'absolute',
								  top:'0px',
								  left:'0px'});
					TextArea.text($.trim(SelectedItem.html()));
					TextArea.addClass('EditingTextArea');
					/*TextArea.keyup(function(){
						var ItemText = $(this).val();
						$(this).parent().contents()[0].nodeValue = ItemText;
					});*/
					SelectedItem.append(TextArea);
					//SelectedItem.html('<textarea style="height:' + SelectedItem.height() + 'px; width:' + SelectedItem.width() + 'px;">' + $.trim(SelectedItem.html()) + '</textarea>');
					SelectedItem.addClass('Editing');
				}
			});
			
			if($('#DoneEditItem').length == 0){
				var DoneEditItem = $('<div></div>');
				DoneEditItem.attr('id','DoneEditItem');
				DoneEditItem.addClass('ToolButton');
				DoneEditItem.text('Done');
				DoneEditItem.click(function(){
					//EditingText = false;
					$('.Editing').each(function(){
						var SelectedItem = $(this);	
						var ItemText = SelectedItem.find('textarea.EditingTextArea').val();
						SelectedItem.html(ItemText);
					});
					$('.Editing').draggable();
					$('.Editing').removeClass('Editing');
					$(this).remove();
				});
				Tools.append(DoneEditItem);
			}
		});
		
		
		
		Editor.before(Tools);
		Tools.append(FinishItem);
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
	
	var CombineContent = function(){
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
		NewItem.addClass('Divisible');
		
		var RowArray = new Array();
		var Positioning = true;
		
		while(Positioning){
			Positioning = false;
			
			var ThisTop = 0;
			var ThisLeft = 0;
			
			/* remove classes and special formating from items */
			for(var i in DimensionArray){
				var I = DimensionArray[i];
				I.Item.draggable('destroy');
				I.Item.resizable('destroy');
				I.Item.removeClass('Selected');
				I.Item.removeClass('MoveAble');
				I.Item.removeClass('Divisible');
				I.Item.removeClass('TextBlock');
				ThisTop = parseInt(I.Item.css('top')) - MinTop;
				ThisLeft = parseInt(I.Item.css('left')) - MinLeft;
				I.Item.css({top:ThisTop + 'px',left:ThisLeft + 'px'});
				I.top = ThisTop;
				I.left = ThisLeft;
				//I.Item.attr('title','left:' + I.left + ' top:' + I.top);
				//I.Item.css({'position':'static'});
				//NewItem.append(I.Item);
				//I.inserted = true;
			}
			
			DimensionArray.sort(function(a,b){return(a.top - b.top)});
			//alert(DimensionArray.length);
			
			var BottomOfLastRow = 0;
			var NewRowHeight = 0;
			var LastAddedIndex = 0;
			var RowIndex = -1;
			
			/* find rows */
			for(i=0;i<DimensionArray.length;i++){
				if(i+1 < DimensionArray.length){
					if(DimensionArray[i+1].top >= DimensionArray[i].top + DimensionArray[i].height){
						NewRowHeight = DimensionArray[i+1].top - BottomOfLastRow;
						RowArray.push({height:NewRowHeight,Items:new Array()});
						RowIndex++;
						BottomOfLastRow += NewRowHeight;
						for(var j=LastAddedIndex;j<=i;j++){
							DimensionArray[j].row = RowIndex;
							RowArray[RowIndex].Items.push(DimensionArray[j]);
							LastAddedIndex++;
						}
					}
				}else{
					if(RowIndex == -1 || DimensionArray[i].top >= BottomOfLastRow){
						NewRowHeight = DimensionArray[i].top + DimensionArray[i].height - BottomOfLastRow;
						RowArray.push({height:NewRowHeight,Items:new Array()});
						RowIndex++;
					}
					for(var j=LastAddedIndex;j<DimensionArray.length;j++){
						DimensionArray[j].row = RowIndex;
						RowArray[RowIndex].Items.push(DimensionArray[j]);
					}
				}
			}
			
			
			
			if(RowArray.length > 1){
				for(i=0;i<RowArray.length;i++){
					//alert(RowArray[i].height);
					RowArray[i].Row = $('<div></div>');
					RowArray[i].Row.css({height:RowArray[i].height + 'px'});
					NewItem.append(RowArray[i].Row);
					RowArray[i].Items.sort(function(a,b){return(a.left - b.left)});
					var UsedWidth = 0;
					for(j=0;j<RowArray[i].Items.length;j++){
						I = RowArray[i].Items[j];
						SpacerWidth = I.left - UsedWidth;
						var SpacerItem = $('<div></div>');
						SpacerItem.css({display:'inline-block',width:SpacerWidth + 'px'});
						UsedWidth += SpacerWidth + I.width;
						
						I.Item.css({position:'static',display:'inline-block'});
						if(SpacerWidth > 0){
							RowArray[i].Row.append(SpacerItem);
						}
						RowArray[i].Row.append(I.Item);
						I.inserted = true;
					}
				}
			}else{
				RowArray[0].Items.sort(function(a,b){return(a.left - b.left)});
				var UsedWidth = 0;
				for(j=0;j<RowArray[0].Items.length;j++){
					I = RowArray[0].Items[j];
					SpacerWidth = I.left - UsedWidth;
					var SpacerItem = $('<div></div>');
					SpacerItem.css({display:'inline-block',width:SpacerWidth + 'px'});
					UsedWidth += SpacerWidth + I.width;
					
					I.Item.css({position:'static',display:'inline-block'});
					if(SpacerWidth > 0){
						NewItem.append(SpacerItem);
					}
					NewItem.append(I.Item);
					I.inserted = true;
				}
			}
			
			/*  check if all items have been inserted */
			for(i=0;i<DimensionArray.length;i++){
				//alert(DimensionArray[i].row);
				var I = DimensionArray[i];
				if(I.inserted == false){
					Positioning = true;
				}
			}
		}
		
		
	}
	
	var FinishEditing = function(){
		Editor.children().each(function(){
			$(this).addClass('Selected');
		});
		CombineContent();
		Editor.children().each(function(){
			var Child = $(this);
			Child.removeAttr('style');
			Child.removeAttr('class');
			Child.draggable('destroy');
			Child.resizable('destroy');
			//Child.removeClass('Selected');
			//Child.removeClass('MoveAble');
			//Child.removeClass('Divisible');
			//Child.removeClass('TextBlock');
		});
		ReplaceObject.html(Editor.html().trim());
		Editor.hide();
		Tools.hide();
		ReplaceObject.show();
	}
	
	var that = this;
	var Editor = $('<div></div>');
	Editor.addClass('Divisible');
	Editor.addClass('MainEditor');
	
	var ReplaceObject = $(Settings.Editor);
	var Tools = $('<div></div>');
	
	var EditingText = false;
	
	init();
	$(".MoveAble").draggable();
}