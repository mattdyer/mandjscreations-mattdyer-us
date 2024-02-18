<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class Meal extends Record{
		function __construct(){
			record::__construct('MealPicker_Meals','mandjscreations','localhost','root','//att1');
		}
	}
?>