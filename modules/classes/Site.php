<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class Site extends Record{
		function __construct(){
			record::__construct('Sites','mandjscreations','mandjsdb','root','example');
		}
		
		function LoginUser($Email,$Password,$AdminLogin){
			$UserQuery = $this->DoQuery("SELECT UserID, PasswordSalt, Password FROM Users WHERE SiteID = " . $this->get('SiteID') . " AND Email = '" . $Email . "'");
			if(mysql_num_rows($UserQuery) == 0){
				throw new Exception('Email address not found.');
			}elseif(mysql_num_rows($UserQuery) == 1){
				while($row = mysql_fetch_array($UserQuery)){
					$PasswordSalt = $row['PasswordSalt'];
					$QueryPassword = $row['Password'];
					$UserID = $row['UserID'];
					
				}
				$HashString = $PasswordSalt . $Password;
				$HashedPassword = hash('sha256',$HashString);
				if($HashedPassword == $QueryPassword){
					$user = LoadClass(SiteRoot . '/modules/classes/User');
					$user->load($UserID);
					if($user->get('Admin') == 1 && $AdminLogin){
						$_SESSION['User'] = serialize($user);
					}
					if(!$AdminLogin){
						$_SESSION['User'] = serialize($user);
					}
					if($user->get('Admin') == 0 && $AdminLogin){
						throw new Exception('You do not have access to the administrative section of this site.');
					}
				}else{
					throw new Exception('Incorrect password or email address.');
				}
			}elseif($UserQuery->num_rows() > 1){
				throw new Exception('More than one user with the same email address.');
			}
		}
		
		function LoadModules(){
			$this->Modules = array();
			$Modules = $this->DoQuery("SELECT M.ClassPath
									   FROM Modules M
									   LEFT OUTER JOIN ModuleAssn MA
									   ON M.ModuleID = MA.ModuleID
									   WHERE MA.SiteID = " . $this->get('SiteID'));
			
			while($row = $Modules->fetch_array()){
				$ClassPath = $row['ClassPath'];
				$ClassParts = explode('/', $ClassPath);
				$Name = $ClassParts[count($ClassParts) - 1];
				$this->Modules[$Name] = LoadClass(SiteRoot . $ClassPath);
				$this->Modules[$Name]->LoadBySiteID($this->get('SiteID'));
			}
			
		}
		
		function GetUsers(){
			$Users = $this->DoQuery("SELECT UserID FROM Users WHERE SiteID =" . $this->get('SiteID') . ";");
			
			$UserArray = array();
			
			while($row = $Users->fetch_array()){
				$user = LoadClass(SiteRoot . '/modules/classes/User');
				$user->load($row['UserID']);
				$UserArray[] = $user;
			}
			
			return $UserArray;
		}
		
		function UserLoggedIn(){
			if(!isset($_SESSION['User'])){
				return false;
			}else{
				return true;
			}
		}
		
		function GetAdminNavLinks(){
			$NavLinks = $this->DoQuery("SELECT M.AdminPath, M.Name
									   FROM Modules M
									   LEFT OUTER JOIN ModuleAssn MA
									   ON M.ModuleID = MA.ModuleID
									   WHERE MA.SiteID = " . $this->get('SiteID'));
			
			$NavLinkArray = array();
			while($row = mysql_fetch_array($NavLinks)){
				$NewLink = array();
				$NewLink['Link'] = $row['AdminPath'];
				$NewLink['Name'] = $row['Name'];
				$NavLinkArray[sizeof($NavLinkArray)] = $NewLink;
			}
			
			/*throw new Exception('My Error' . $NavLinkArray[0]['Link']);*/
			
			return $NavLinkArray;
		}
	}
?>