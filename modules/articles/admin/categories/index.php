<?php
	$RequireLogin = true;
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	$category = LoadClass(SiteRoot . '/modules/classes/articles/Category');
	
	if (array_key_exists('CategoryID', $_GET)) {
		$CategoryID = $_GET['CategoryID'];
		$category->load($CategoryID);
		$Articles = $category->GetArticles();
	}
	
	if (array_key_exists('DeleteArticleID', $_GET)) {
		$article = LoadClass(SiteRoot . '/modules/classes/articles/Article');
		$article->load($_GET['DeleteArticleID']);
		$result = $article->delete();
		header('Location: ' . $_SERVER['PHP_SELF'] . '?CategoryID=' . $_GET['CategoryID']);
	}
	
	ob_start();
		echo '<div><a href="/modules/articles/admin/index.php">Back</a></div>';
		echo '<div><a href="/modules/articles/admin/articles/index.php?CategoryID=' . $category->get('CategoryID') . '">Add New Article</a></div>';
?>

	

<?php
		if (array_key_exists('CategoryID', $_GET)) {
			echo '<ul class="ItemList">';
			foreach($Articles AS $key => $value){
				$ThisArticleID = $value->get('ArticleID');
				$ThisArticleTitle = $value->get('Title');
				echo '<li><a href="/modules/articles/admin/articles/index.php?ArticleID=' . $ThisArticleID . '">' . $ThisArticleTitle . '</a> - <a href="' . $_SERVER['PHP_SELF'] . '?CategoryID=' . $category->get('CategoryID') . '&DeleteArticleID=' . $ThisArticleID . '" onclick="return confirm(\'Are you sure you want to delete this article?\');">Delete</a></li>';
			}
			echo '</ul>';
		}
		$BodyContent = ob_get_contents();
	ob_end_clean();
?>

<?php
	include(SiteRoot . "/common/admintemplate.php");
?>