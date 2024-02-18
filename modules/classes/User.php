<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class User extends Record{
		function __construct(){
			record::__construct('Users','mandjscreations','localhost','root','//att1');
		}
		
		function SetPassword($Password){
			$Salt = RandomString(32);
			$HashString = $Salt . $Password;
			$HashedPassword = hash('sha256',$HashString);
			
			$this->set('PasswordSalt',$Salt);
			$this->set('Password',$HashedPassword);
			$this->save();
		}
		
		function beforeSave(){
			if($this->IsNewRecord() && strlen($this->get('DateEntered')) == 0){
				$this->set('DateEntered',$this->SQLDate(time()));
			}
			if($this->IsNewRecord()){
				ob_start();
			
				echo "New user added";
				
				$NewUser = ob_get_contents();
				ob_end_clean();
				
				mail('matt@mandjscreations.com','New User Signup',$NewUser,'From: matt@mandjscreations.com');
			}
		}
		
		function checkFriendStatus($FriendUserID){
			$FriendQuery1 = $this->DoQuery("SELECT Approved FROM Profile_Friends WHERE UserID = " . $this->get('UserID') . " AND FriendUserID = " . $FriendUserID);
			$FriendQuery2 = $this->DoQuery("SELECT Approved FROM Profile_Friends WHERE FriendUserID = " . $this->get('UserID') . " AND UserID = " . $FriendUserID);
			
			if(mysql_num_rows($FriendQuery1) == 0 && mysql_num_rows($FriendQuery2) == 0){
				return 'New';
			}
			if(mysql_num_rows($FriendQuery1) > 0){
				while($row = mysql_fetch_array($FriendQuery1)){
					if(ord($row['Approved']) == 1){
						return 'Approved';
					}else{
						return 'Unapproved';
					}
				}
			}
			if(mysql_num_rows($FriendQuery2) > 0){
				while($row = mysql_fetch_array($FriendQuery2)){
					if(ord($row['Approved']) == 1){
						return 'Approved';
					}else{
						return 'Requested';
					}
				}
			}
		}
		
		function getFriendRequests(){
			$FriendRequestQuery = $this->DoQuery("SELECT F.FriendID, F.UserID, U.FirstName, U.LastName FROM Profile_Friends F LEFT OUTER JOIN Users U ON F.UserID = U.UserID WHERE F.FriendUserID = " . $this->get('UserID') . " AND F.Approved = 0");
			
			$FriendRequestArray = array();
			
			while($row = mysql_fetch_array($FriendRequestQuery)){
				$FriendRequestArray[] = $row;
			}
			
			return $FriendRequestArray;
		}
		
		function getFriends(){
			$FriendQuery1 = $this->DoQuery("SELECT F.FriendID, U.UserID, U.FirstName, U.LastName
										   FROM Profile_Friends F
										   LEFT OUTER JOIN Users U
										   ON F.UserID = U.UserID
										   WHERE F.FriendUserID = " . $this->get('UserID') . " AND F.Approved = 1");
			
			$FriendQuery2 = $this->DoQuery("SELECT F.FriendID, U.UserID, U.FirstName, U.LastName
										   FROM Profile_Friends F
										   LEFT OUTER JOIN Users U
										   ON F.FriendUserID = U.UserID
										   WHERE F.UserID = " . $this->get('UserID') . " AND F.Approved = 1");
			
			$FriendArray = array();
			
			while($row = mysql_fetch_array($FriendQuery1)){
				$FriendArray[] = $row;
			}
			while($row = mysql_fetch_array($FriendQuery2)){
				$FriendArray[] = $row;
			}
			
			return $FriendArray;
		}
		
		function getArticleComments(){
			$Comments = $this->DoQuery("SELECT CommentID FROM Comments WHERE UserID = " . $this->get('UserID') . " ORDER BY DateEntered");
			
			$CommentArray = array();
			
			while($row = mysql_fetch_array($Comments)){
				$comment = LoadClass(SiteRoot . '/modules/classes/articles/Comment');
				$comment->load($row['CommentID']);
				$CommentArray[] = $comment;
			}
			
			return $CommentArray;
		}
		
		function MakeLink(){
			$Link = "/profile/" . $this->get('UserID') . "/" . preg_replace('/[^0-9a-zA-Z-]+/','-',$this->get('FirstName')) . '-' . preg_replace('/[^0-9a-zA-Z-]+/','-',$this->get('LastName'));
			
			return $Link;
		}
		
		function getLifeSaves(){
			$SaveQuery = $this->DoQuery("SELECT LifeSaveID, Name FROM LifeSaves WHERE UserID = " . $this->get('UserID'));
			
			$LifeSaves = array();
			
			while($row = mysql_fetch_array($SaveQuery)){
				$LifeSave['LifeSaveID'] = $row['LifeSaveID'];
				$LifeSave['Name'] = htmlentities($row['Name']);
				
				$LifeSaves[] = $LifeSave;
			}
			
			return $LifeSaves;
		}
	}
?>