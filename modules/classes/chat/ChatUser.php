<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class ChatUser extends Record{
		function __construct(){
			record::__construct('Chat_ChatUsers','mandjscreations','mandjsdb', 'root', 'example');
		}
		
		function beforeSave(){
			if ($this->IsNewRecord()){
				$this->set('DateEntered',$this->SQLDate(time()));
			}
			$this->set('LastPageDate',$this->SQLDate(time()));
		}
		
		function LoadOrCreatyByUserID($UserID,$Name,$SiteID){
			$FindUser = $this->DoQuery("SELECT ChatUserID FROM Chat_ChatUsers WHERE UserID = $UserID ORDER BY DateEntered DESC LIMIT 1");
			
			if($FindUser->num_rows == 0){
				$this->set('SiteID',$SiteID);
				$this->set('UserID',$UserID);
				$this->set('Name',$Name);
				$this->save();
			}else{
				while($row = $FindUser->fetch_array()){
					$this->load($row['ChatUserID']);
				}
			}
		}
		
		function getMessages($ScriptName){
			/*$MessageQuery = $this->DoQuery("SELECT C.ChatUserID, C.Name, M.Message, M.DateEntered
										   FROM Chat_Messages M
										   LEFT OUTER JOIN Chat_ChatUsers C ON M.ChatUserID = C.ChatUserID
										   WHERE M.ScriptName = '$ScriptName' AND M.DateEntered > DATE_ADD(NOW(),INTERVAL -24 HOUR)
										   ORDER BY M.DateEntered DESC
										   LIMIT 20");*/
			
			$MessageQuery = $this->DoQuery("SELECT C.ChatUserID, C.Name, M.MessageID, M.Message, M.DateEntered
										   FROM Chat_Messages M
										   LEFT OUTER JOIN Chat_ChatUsers C ON M.ChatUserID = C.ChatUserID
										   WHERE M.DateEntered > DATE_ADD(NOW(),INTERVAL -24 HOUR) AND M.FriendMessage = 0
										   ORDER BY M.DateEntered DESC
										   LIMIT 20");
			
			$Messages = array();
			
			while($row = $MessageQuery->fetch_array()){
				list($date,$time) = split(' ',$row['DateEntered']);
				list($hour,$minute,$second) = split(':',$time);
				if($hour >= 12){
					if($hour == 12){
						$fixedhour = $hour;
					}else{
						$fixedhour = $hour - 12;
					}
					$dayperiod = 'pm';
				}else{
					$fixedhour = $hour;
					$dayperiod = 'am';
				}
				$Message['MessageID'] = $row['MessageID'];
				$Message['ChatUserID'] = $row['ChatUserID'];
				$Message['Name'] = $row['Name'];
				$Message['Message'] = htmlentities($row['Message']);
				$Message['Time'] = $fixedhour . ':' . $minute . ' ' . $dayperiod;
				$Messages[] = $Message;
			}
			
			return $Messages;
		}
		
		function getFriendMessages(){
			
			$MessageQuery = $this->DoQuery("SELECT C.ChatUserID, C.Name, M.MessageID, M.Message, M.DateEntered, M.FriendID, M.UserID
										   FROM Chat_Messages M
										   LEFT OUTER JOIN Chat_ChatUsers C ON M.ChatUserID = C.ChatUserID
										   WHERE M.DateEntered > DATE_ADD(NOW(),INTERVAL -24 HOUR) AND M.FriendMessage = 1 AND (M.UserID = " . $this->get('UserID') . " OR M.FriendID = " . $this->get('UserID') . ")
										   ORDER BY M.DateEntered DESC");
			
			$Messages = array();
			
			while($row = $MessageQuery->fetch_array()){
				list($date,$time) = split(' ',$row['DateEntered']);
				list($hour,$minute,$second) = split(':',$time);
				if($hour >= 12){
					if($hour == 12){
						$fixedhour = $hour;
					}else{
						$fixedhour = $hour - 12;
					}
					$dayperiod = 'pm';
				}else{
					$fixedhour = $hour;
					$dayperiod = 'am';
				}
				$Message['MessageID'] = $row['MessageID'];
				$Message['ChatUserID'] = $row['ChatUserID'];
				if($row['UserID'] == $this->get('UserID')){
					$Message['FriendID'] = $row['FriendID'];
					$Message['UserID'] = $row['UserID'];
				}else{
					$Message['FriendID'] = $row['UserID'];
					$Message['UserID'] = $row['FriendID'];
				}
				$Message['Name'] = $row['Name'];
				$Message['Message'] = htmlentities($row['Message']);
				$Message['Time'] = $fixedhour . ':' . $minute . ' ' . $dayperiod;
				$Messages[] = $Message;
			}
			
			return $Messages;
		}
		
		function getOtherUsers($ScriptName){
			/*$ChatUserQuery = $this->DoQuery("SELECT C.ChatUserID, C.Name FROM Chat_ChatUsers C WHERE C.LastPage = '$ScriptName' AND C.LastPageDate > DATE_ADD(NOW(),INTERVAL -10 SECOND)");*/
			
			$ChatUserQuery = $this->DoQuery("SELECT C.ChatUserID, C.Name, U.UserID FROM Chat_ChatUsers C LEFT OUTER JOIN Users U ON C.UserID = U.UserID WHERE C.LastPageDate > DATE_ADD(NOW(),INTERVAL -10 SECOND)");
			
			$ChatUsers = array();
			
			while($row = $ChatUserQuery->fetch_array()){
				$ChatUser['ChatUserID'] = $row['ChatUserID'];
				$ChatUser['UserID'] = $row['UserID'];
				$ChatUser['Name'] = $row['Name'];
				if($ChatUser['ChatUserID'] == $this->get('ChatUserID')){
					$ChatUser['Name'] = '&gt;' . $row['Name'];
				}
				$ChatUsers[] = $ChatUser;
			}
			
			return $ChatUsers;
		}
		
		function getOtherConversations($ScriptName){
			$ChatUserQuery = $this->DoQuery("SELECT COUNT(C.ChatUserID) AS TotalUsers, C.LastPage FROM Chat_ChatUsers C WHERE C.LastPage != '$ScriptName' AND C.LastPageDate > DATE_ADD(NOW(),INTERVAL -10 SECOND) GROUP BY C.LastPage");
		
			$Conversations = array();
			
			while($row = $ChatUserQuery->fetch_array()){
				$Conversation['TotalUsers'] = $row['TotalUsers'];
				$Conversation['LastPage'] = $row['LastPage'];
				$Conversations[] = $Conversation;
			}
			
			return $Conversations;
		}
	}
?>