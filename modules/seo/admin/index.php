<?php
	$RequireLogin = true;
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	
	if (array_key_exists('DeletePageID', $_GET)) {
		$page = LoadClass(SiteRoot . '/modules/classes/seo/Page');
		$page->load($_GET['DeletePageID']);
		$page->delete();
		header('Location: ' . $_SERVER['PHP_SELF']);
	}
	
	$Pages = $site->Modules['SEO']->GetPages();
	
	ob_start();
		echo '<a href="/modules/seo/admin/pages/manage.php">Add New Page</a><br />';
		
		echo '<h3>Pages</h3>';
		
		foreach($Pages AS $key => $value){
			echo '<div>' . $value->get('PageType') . ': <a href="/modules/seo/admin/pages/manage.php?PageID=' . $value->get('PageID') . '">' . $value->get('Name') . '</a> ' . $value->get('ScriptName') . ' <a href="' . $_SERVER['PHP_SELF'] . '?DeletePageID=' . $value->get('PageID') . '">Delete</a></div>';
		}
		
		$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>
 
<?php
	include(SiteRoot . "/common/admintemplate.php");
?>