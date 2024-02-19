<?php
	$RequireLogin = true;
	include("/var/www/html/modules/AppInit.php");
	$Categories = $site->Modules['Articles']->GetCategories();
	
	ob_start();
		echo '<a href="/modules/articles/admin/categories/manage.php">Add New Category</a><br />';
		foreach($Categories AS $key => $value){
			$ThisCategoryID = $value->get('CategoryID');
			$ThisCategoryName = $value->get('Name');
			echo '<a href="/modules/articles/admin/categories/index.php?CategoryID=' . $ThisCategoryID . '">' . $ThisCategoryName . '</a> - <a href="/modules/articles/admin/categories/manage.php?CategoryID=' . $ThisCategoryID . '">Edit</a><br />';
		}
		$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>
 
<?php
	include("/var/www/html/common/admintemplate.php");
?>