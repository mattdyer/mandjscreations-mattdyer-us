	function getChatInfo(){
		$.getJSON('/common/getChat.php',{currentPage:currentPageString},function(data){
			displayChatInfo(data);
		});
		setTimeout(getChatInfo,5000);
	}
	function sendMessage(){
		$('#chatButton').css('background-color','transparent');
		var content = $('#generalMessage').val();
		if(content.length > 0){
			$.getJSON('/common/getChat.php',{currentPage:currentPageString,newMessage:content},function(data){
				displayChatInfo(data);
			});
		}
		$('#generalMessage').val('');
	}
	function displayChatInfo(data){
		//alert(data.messages);
		$('#chatContainer').html('');
		if(PageReload && data.messages.length > 0){
			//PageReload = false;
			LastMessageID = data.messages[0].MessageID;
		}
		for(index in data.messages){
			NewMessage = data.messages[index];
			if(NewMessage.MessageID != LastMessageID && index == 0){
				LastMessageID = NewMessage.MessageID;
				if($('#chatContainer:visible').length == 0){
					flashChat(0);
				}
			}
			$('#chatContainer').prepend('<div class="User' + NewMessage.ChatUserID + '"><strong>' + NewMessage.Name + '</strong> <span>(' + NewMessage.Time + ')</span>: ' + NewMessage.Message + '</div>');
		}
		
		var objDiv = document.getElementById("chatContainer");
		objDiv.scrollTop = objDiv.scrollHeight;

		$('#chatUsers').html('');
		for(index in data.chatusers){
			NewUser = data.chatusers[index];
			if(NewUser.UserID){
				$('#chatUsers').append('<div class="User' + NewUser.ChatUserID + '">' + NewUser.Name + ' <a href="/modules/profile/index.php?UserID=' + NewUser.UserID + '">[View Profile]</a></div>');
			}else{
				$('#chatUsers').append('<div class="User' + NewUser.ChatUserID + '">' + NewUser.Name + '</div>');
			}
		}
		$('#Conversations').html('');
		for(index in data.conversations){
			Conversation = data.conversations[index];
			$('#Conversations').append('<div><a href="' + Conversation.LastPage + '">' + Conversation.TotalUsers + ' Users</a></div>');
		}
		$('#Friends').html('');
		for(index in data.friends){
			Friend = data.friends[index];
			$('#Friends').append('<div><span onclick="openFriendChat(' + Friend.UserID + ');">' + Friend.FirstName + ' ' + Friend.LastName + '</span> <a href="/modules/profile/index.php?UserID=' + Friend.UserID + '">[View Profile]</a></div>');
		}
		
		$('.friendChat').html('');
		for(index in data.friendmessages){
			NewMessage = data.friendmessages[index];
			//alert(NewMessage.FriendID);
			/*if(NewMessage.MessageID != LastMessageID && index == 0){
				LastMessageID = NewMessage.MessageID;
				if($('#chatContainer:visible').length == 0){
					flashChat(0);
				}
			}*/
			if($('#FriendChat_' + NewMessage.FriendID).length == 0){
				createFriendChatBox(NewMessage.FriendID)
			}
			$('#FriendChat_' + NewMessage.FriendID).prepend('<div class="User' + NewMessage.ChatUserID + '"><strong>' + NewMessage.Name + '</strong> <span>(' + NewMessage.Time + ')</span>: ' + NewMessage.Message + '</div>');
		}
		$('.friendChat').each(function(){
			this.scrollTop = this.scrollHeight;
		});
		if(PageReload){
			//alert('hide');
			PageReload = false;
			$('.friendChat, .friendChatMessageBox').hide();
		}
		
		var classes = new Object();
		$('#chatUsers div').each(function(){
			classes[$(this).attr('class')] = 1;
		});
		/*$('#chatContainer div').each(function(){
			classes[$(this).attr('class')] = 1;
		});*/
		var colors = ['Blue','Red','Green','Black','Aqua','Brown','Orange','Salmon','SpringGreen','SteelBlue'];
		var count = 0;
		for(userclass in classes){
			$('.' + userclass).css('color',colors[count]);
			count++;
			if(count == 10){
				count = 0;
			}
			//alert(class);
		}
	}
	function checkKey(evt,messageBox){
		var charCode = (evt.which) ? evt.which : event.keyCode;
		//alert(charCode);
		if(charCode == 13){
			if(messageBox.id == 'generalMessage'){
				sendMessage();
			}else{
				sendFriendMessage(messageBox.id);
			}
			return false;
		}
		return true;
	}
	function toggleChat(){
		$('#chatButton').css('background-color','transparent');
		var chatBox = document.getElementById('chatContainer');
		var chatMessage = document.getElementById('chatMessageBox');
		if(chatBox.style.display == 'none'){
			chatBox.style.display = '';
			chatMessage.style.display = '';
		}else{
			chatBox.style.display = 'none';
			chatMessage.style.display = 'none';
		}
	}
	function toggleChatUsers(){
		var chatBox = document.getElementById('chatUsers');
		if(chatBox.style.display == 'none'){
			chatBox.style.display = '';
		}else{
			chatBox.style.display = 'none';
		}
	}
	function toggleConversations(){
		var chatBox = document.getElementById('Conversations');
		if(chatBox.style.display == 'none'){
			chatBox.style.display = '';
		}else{
			chatBox.style.display = 'none';
		}
	}
	function toggleFriends(){
		var chatBox = document.getElementById('Friends');
		if(chatBox.style.display == 'none'){
			chatBox.style.display = '';
			$('.friendChat, .friendChatMessageBox').show();
		}else{
			chatBox.style.display = 'none';
			$('.friendChat, .friendChatMessageBox').hide();
		}
	}
	function flashChat(count){
		$('#chatButton').css('background-color','orange');
		if(count < 5){
			setTimeout(function(){$('#chatButton').css('background-color','transparent');},500);
			var nextCount = count + 1;
			setTimeout('flashChat(' + nextCount + ')',1000);
		}
	}
	function openFriendChat(UserID){
		if($('#FriendChat_' + UserID).length == 0){
			$('.friendChat, .friendChatMessageBox').hide();
			createFriendChatBox(UserID);
		}else{
			$('.friendChat, .friendChatMessageBox').hide();
			$('#FriendChat_' + UserID + ',#FriendChatText_' + UserID).show();
		}
	}
	function createFriendChatBox(UserID){
		var newFriendChat = document.createElement('div');
		var newFriendChatMessageBox = document.createElement('div');
		var newFriendChatText = document.createElement('textarea');
		$(newFriendChatText).keypress(function(event){
			return checkKey(event,this);
		});
		
		$('#friendButton').append(newFriendChatMessageBox);
		$(newFriendChatMessageBox).addClass('friendChatMessageBox');
		newFriendChatMessageBox.id = 'FriendChatText_' + UserID;
		
		$(newFriendChatMessageBox).append(newFriendChatText);
		$(newFriendChatText).addClass('newMessageFriend');
		newFriendChatText.id = 'FriendChatTextArea_' + UserID;
		
		$('#friendButton').append(newFriendChat);
		$(newFriendChat).addClass('friendChat').addClass('chatCorners');
		newFriendChat.id = 'FriendChat_' + UserID;
		
		$('.notificationBar, .notificationBar > div, .notificationBar > div > div').click(function(event){
			event.stopPropagation();
		});
	}
	function sendFriendMessage(messageBoxID){
		$('#friendButton').css('background-color','transparent');
		var content = $('#' + messageBoxID).val();
		var friendID = messageBoxID.split('_')[1];
		//alert(content);
		if(content.length > 0){
			$.getJSON('/common/getChat.php',{currentPage:currentPageString,newMessage:content,FriendID:friendID},function(data){
				displayChatInfo(data);
			});
		}
		$('#' + messageBoxID).val('');
	}
	function displayNotificationBar(){
		$.get('/common/chatBar.php',function(data){
			$('body').append(data);
			$('.notificationBar, .notificationBar > div, .notificationBar > div > div').click(function(event){
				event.stopPropagation();
			});
			$('body').click(function(event){
				$('.notificationBar > div > div').hide();
				//$('#chatContainer, #chatMessageBox, #chatUsers, #Conversations').hide();
			});
		});
	}
	$(document).ready(function(){
		displayNotificationBar();
		PageReload = true;
		LastMessageID = 0;
		getChatInfo();
		
		/*$('.mainBody a, .header a').live('click',function(){
			
			$.get($(this).attr('href'),{'ContentOnly':1},function(data){
				var BodyContent = $('.bodyContent');
				BodyContent.slideUp('100',function(){
					BodyContent.html(data);
					BodyContent.slideDown('100');
				});
			});
			
			return false;
		});*/
		
	});