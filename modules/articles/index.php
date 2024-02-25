<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	
	$PopularArticles = $site->Modules['Articles']->GetPopularArticles();
	$Categories = $site->Modules['Articles']->GetCategories();
	
	ob_start();
		echo '<div class="bodyContentSection">';
		echo '<h2>Articles</h2>';
		echo '<div class="BreadCrumb">&gt; <a href="/">Home</a> &gt; Articles</div>';
		/*echo '<h3>Search By Category</h3>';
		echo '<ul class="articleIndexList">';
		foreach($Categories AS $key => $value){
			$ThisCategoryLink = $value->MakeLink();
			$ThisCategoryName = $value->get('Name');
			echo '<li><a href="' . $ThisCategoryLink . '">' . $ThisCategoryName . '</a></li>';
		}
		echo '</ul>';*/
		echo '<h3>Popular Articles</h3>';
		echo '<ul class="articleIndexList">';
		for($i=0;$i < 20;$i++){
			$ThisArticleLink = $PopularArticles[$i]->MakeLink();
			$ThisArticleTitle = $PopularArticles[$i]->get('Title');
			$ThisContent = $PopularArticles[$i]->get('Content');
			$ThisContent = substr(strip_tags($ThisContent),0,250);
			$ThisViews = $PopularArticles[$i]->get('Views');
			echo '<li><a href="' . $ThisArticleLink . '">' . $ThisArticleTitle . '</a> <span class="PopularArticleViews">' . $ThisViews . ' Views</span> ' . $ThisContent . '...</li>';
		}
		echo '</ul>';
		echo '</div>';
		$BodyContent = ob_get_contents();
	ob_end_clean();
 
	include(SiteRoot . "/common/template.php");
?>