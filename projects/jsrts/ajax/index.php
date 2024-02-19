<?php
	include("/var/www/html/modules/AppInit.php");
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
	switch($_POST['Action']){
		case 'GetPlayerName':
			$returnInfo = array();
			if($site->UserLoggedIn()){
				$returnInfo['PlayerName'] = $user->get('FirstName');
			}else{
				$returnInfo['PlayerName'] = '';
			}
			echo json_encode($returnInfo);
			break;
		case 'GetGames':
			$game = LoadClass(SiteRoot . '/modules/classes/jsrts/Game');
			echo json_encode($game->getNewGames());
			break;
		case 'CreateGame':
			$game = LoadClass(SiteRoot . '/modules/classes/jsrts/Game');
			$game->UpdateAttributes($_POST);
			$game->set('ColorIndex',0);
			$game->set('Cancelled','No');
			$game->save();
			echo json_encode($game->get('GameID'));
			break;
		case 'GetPlayers':
			$game = LoadClass(SiteRoot . '/modules/classes/jsrts/Game');
			$game->load($_POST['GameID']);
			if($game->get('Started') == 'Yes'){
				echo json_encode('Start');
			}elseif($game->get('Cancelled') == 'Yes'){
				echo json_encode('Cancelled');
			}else{
				echo json_encode($game->getPlayers());
			}
			break;
		case 'StartGame':
			$game = LoadClass(SiteRoot . '/modules/classes/jsrts/Game');
			$game->load($_POST['GameID']);
			$game->set('Started','Yes');
			$game->save();
			break;
		case 'CreatePlayer':
			$PlayerColors = array("#FF0000","#00FF00","#0000FF");
			$PlayerColors = array('Blue','Red','Green','Black','Aqua','Brown','Orange','Salmon','SpringGreen','SteelBlue');
			$game = LoadClass(SiteRoot . '/modules/classes/jsrts/Game');
			$game->load($_POST['GameID']);
			$player = LoadClass(SiteRoot . '/modules/classes/jsrts/Player');
			$player->UpdateAttributes($_POST);
			$player->set('Color',$PlayerColors[$game->get('ColorIndex')]);
			$player->set('ReadyToStart','No');
			$player->set('LeftGame','No');
			$player->save();
			$game->set('ColorIndex',$game->get('ColorIndex') + 1);
			$game->set('Started','No');
			$game->save();
			//echo json_encode($player->get('PlayerID'));
			echo '{"PlayerID":' . $player->get('PlayerID') . ',"Color":"' . $player->get('Color') . '"}';
			break;
		case 'MarkPlayerReady':
			$player = LoadClass(SiteRoot . '/modules/classes/jsrts/Player');
			$player->load($_POST['PlayerID']);
			$player->set('ReadyToStart','Yes');
			$player->save();
			break;
		case 'LeaveGame':
			$player = LoadClass(SiteRoot . '/modules/classes/jsrts/Player');
			$player->load($_POST['PlayerID']);
			$player->set('LeftGame','Yes');
			$player->save();
			if($_POST['GameCreator'] == 'true'){
				$game = LoadClass(SiteRoot . '/modules/classes/jsrts/Game');
				$game->load($player->get('GameID'));
				$game->set('Cancelled','Yes');
				$game->save();
			}
			break;
		case 'CreateUnit':
			$unit = LoadClass(SiteRoot . '/modules/classes/jsrts/Unit');
			$unit->UpdateAttributes($_POST);
			$unit->save();
			echo json_encode($unit->get('UnitID'));
			break;
		case 'GetActions':
			$player = LoadClass(SiteRoot . '/modules/classes/jsrts/Player');
			$player->load($_POST['PlayerID']);
			$Actions = $player->getActions();
			echo '[';
			$Count = 0;
			foreach($Actions AS $key => $Action){
				$Count++;
				if($Count > 1){
					echo ',';
				}
				echo '{"ActionID":' . $Action['ActionID'] . ',"Type":"' . $Action['Type'] . '","Data":' . $Action['Data'] . '}';
			}
			echo ']';
			break;
		case 'AddAction':
			$action = LoadClass(SiteRoot . '/modules/classes/jsrts/Action');
			$action->UpdateAttributes($_POST);
			$action->save();
			echo json_encode($action->get('ActionID'));
			break;
		case 'CompleteAction':
			$playeraction = LoadClass(SiteRoot . '/modules/classes/jsrts/PlayerActionComplete');
			$playeraction->UpdateAttributes($_POST);
			$playeraction->save();
			echo json_encode($playeraction->get('PlayerActionCompleteID'));
			break;
		default:
			echo json_encode('Invalid Action');
	}
	//echo 'Test';
?>