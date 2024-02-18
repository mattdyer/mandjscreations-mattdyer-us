<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class MealType extends Record{
		function __construct(){
			record::__construct('MealPicker_MealTypes','mandjscreations','localhost','root','//att1');
		}
	}
?>