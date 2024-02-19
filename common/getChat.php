<?php
	include("/var/www/html/modules/AppInit.php");
	$ScriptName = $_GET['currentPage'];
	$chatUser = LoadClass(SiteRoot . '/modules/classes/chat/ChatUser');
	if($site->UserLoggedIn()){
		if(isset($_SESSION['ChatUserID'])){
			$chatUser->load($_SESSION['ChatUserID']);
			if($chatUser->get('UserID') != $user->get('UserID') | $chatUser->get('Name') != $user->get('FirstName') | $chatUser->get('LastPage') != $ScriptName){
				$chatUser->set('UserID',$user->get('UserID'));
				$chatUser->set('Name',$user->get('FirstName'));
				$chatUser->set('LastPage',$ScriptName);
			}
			$chatUser->save();
		}else{
			$chatUser->LoadOrCreateByUserID($user->get('UserID'),$user->get('FirstName'),$site->get('SiteID'));
			$_SESSION['ChatUserID'] = $chatUser->get('ChatUserID');
		}
	}else{
		if(isset($_SESSION['ChatUserID'])){
			$chatUser->load($_SESSION['ChatUserID']);
			if($chatUser->get('LastPage') != $ScriptName){
				$chatUser->set('LastPage',$ScriptName);
			}
			$chatUser->save();
		}else{
			$chatUser->set('SiteID',$site->get('SiteID'));
			$chatUser->set('Name','User');
			$chatUser->save();
			$chatUser->set('Name','User' . $chatUser->get('ChatUserID'));
			$chatUser->save();
			$_SESSION['ChatUserID'] = $chatUser->get('ChatUserID');
		}
	}
	
	if(array_key_exists('newMessage', $_GET)){
		$Message = LoadClass(SiteRoot . '/modules/classes/chat/Message');
		$Message->set('ChatUserID',$chatUser->get('ChatUserID'));
		$Message->set('Message',$_GET['newMessage']);
		$Message->set('ScriptName',$ScriptName);
		if($site->UserLoggedIn() && array_key_exists('FriendID', $_GET)){
			$Message->set('FriendMessage',1);
			$Message->set('FriendID',$_GET['FriendID']);
			$Message->set('UserID',$user->get('UserID'));
		}else{
			$Message->set('FriendMessage',0);
		}
		$Message->save();
	}
	
	$messages = $chatUser->getMessages($ScriptName);
	$chatusers = $chatUser->getOtherUsers($ScriptName);
	$conversations = $chatUser->getOtherConversations($ScriptName);
	$data['messages'] = $messages;
	$data['chatusers'] = $chatusers;
	$data['conversations'] = $conversations;
	
	if($site->UserLoggedIn()){
		$friends = $user->getFriends();
		$friendmessages = $chatUser->getFriendMessages();
		$data['friends'] = $friends;
		$data['friendmessages'] = $friendmessages;
	}else{
		$data['friends'] = array();
		$data['friendmessages'] = array();
	}
	
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
	echo json_encode($data);
?>