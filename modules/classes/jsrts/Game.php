<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class Game extends Record{
		function __construct(){
			record::__construct('JSRTS_Games','mandjscreations','mandjsdb', 'root', 'example');
		}
		
		function beforeSave(){
			if ($this->IsNewRecord()){
				$this->set('DateEntered',$this->SQLDate(time()));
			}
		}
		
		function getNewGames(){
			$Games = $this->DoQuery("SELECT GameID FROM JSRTS_Games WHERE Started = 'No' AND Cancelled = 'No' AND DateEntered > '" . date("Y-m-d H:i:s",strtotime('1 hour ago')) . "'");
			
			$GameArray = array();
			
			while($row = mysql_fetch_array($Games)){
				$game = LoadClass(SiteRoot . '/modules/classes/jsrts/Game');
				$game->load($row['GameID']);
				$Players = $game->getPlayers();
				
				$JSGame = array("GameID" => $game->get('GameID'),"Name" => $game->get('Name'),"PlayerCount" => count($Players));
				$GameArray[] = $JSGame;
			}
			
			return $GameArray;
		}
		
		function getPlayers(){
			$Players = $this->DoQuery("SELECT PlayerID FROM JSRTS_Players WHERE GameID = " . $this->get('GameID') . " AND LeftGame = 'No'");
			
			$PlayerArray = array();
			
			while($row = mysql_fetch_array($Players)){
				$player = LoadClass(SiteRoot . '/modules/classes/jsrts/Player');
				$player->load($row['PlayerID']);
				$JSPlayer = array("PlayerID" => $player->get('PlayerID'),"Name" => $player->get('Name'),"Ready" => $player->get('ReadyToStart'));
				$PlayerArray[] = $JSPlayer;
			}
			
			return $PlayerArray;
		}
		
	}
?>