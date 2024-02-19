<?php
	include("/var/www/html/modules/AppInit.php");
	
	if($site->UserLoggedIn()){
		$lifeSave = LoadClass(SiteRoot . '/modules/classes/life/LifeSave');
		if($_POST['Action'] == 'Save'){
			if(array_key_exists('LifeSaveID', $_POST)){
				$lifeSave->load($_POST['LifeSaveID']);
			}
			$lifeSave->set('UserID',$user->get('UserID'));
			$lifeSave->set('Name',$_POST['Name']);
			$lifeSave->set('GameData',$_POST['GameData']);
			$lifeSave->save();
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: application/json');
			echo json_encode($lifeSave->getAllSaves());
		}
		if($_POST['Action'] == 'Load'){
			$lifeSave->load($_POST['LifeSaveID']);
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: application/json');
			echo $lifeSave->get('GameData');
		}
		if($_POST['Action'] == 'Delete'){
			$lifeSave->load($_POST['LifeSaveID']);
			$lifeSave->delete();
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: application/json');
			echo json_encode($user->getLifeSaves());
		}
		if($_POST['Action'] == 'GetSaves'){
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: application/json');
			echo json_encode($user->getLifeSaves());
		}
	}
?>