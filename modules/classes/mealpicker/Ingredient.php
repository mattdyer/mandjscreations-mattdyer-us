<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class Ingredient extends Record{
		function __construct(){
			record::__construct('MealPicker_Ingredients','mandjscreations','localhost','root','//att1');
		}
	}
?>