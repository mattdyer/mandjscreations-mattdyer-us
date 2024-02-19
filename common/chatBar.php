<?php
	include("/var/www/html/modules/AppInit.php");
?>
<div class="notificationBar">
	<?php
		if($site->UserLoggedIn()){
			echo "<div>Welcome Back " . $user->get('FirstName') . " <a href=\"/modules/profile/logout.php\">Logout</a></div>";
		}else{
			echo '<div><a href="/modules/profile/signup.php">Signup</a> or <a href="/modules/profile/index.php">Login</a></div>';
		}
	?>
	<div onclick="toggleChat();" id="chatButton">
		Chat
		<div id="chatContainer" class="chatCorners" style="display:none;"></div>
		<div id="chatMessageBox" style="display:none;"><textarea name="newMessage" id="generalMessage" class="newMessage" onfocus="this.value = ''; this.style.color = '#000';" onkeypress="return checkKey(event,this);" style="">Click here to start chatting</textarea></div>
	</div>
	<div onclick="toggleChatUsers();">
		Other Users
		<div id="chatUsers" class="chatCorners" style="display:none;"></div>
	</div>
	<div onclick="toggleConversations();">
		Users On Other Pages
		<div id="Conversations" class="chatCorners" style="display:none;"></div>
	</div>
	<?php
		if($site->UserLoggedIn()){
	?>
	<div onclick="toggleFriends();" id="friendButton">
		Friends
		<div id="Friends" class="chatCorners" style="display:none;"></div>
	</div>
	<?php
		}
	?>
</div>