<?php
	include("/var/www/html/modules/AppInit.php");
	
	$RecentArticles = $site->Modules['Articles']->GetRecentArticles();
	
	//$foo = 5 / 0;
	
	ob_start();
		?>
			<div class="bodyContentSection">
			<h2>Home</h2>
			<div class="BreadCrumb">&gt; Home</div>
			<p class="HomeParagraph">Welcome to MAD Computing. Contact us for help with any computer needs. We can come to your location anywhere in the Flathead valley and many other locations in Northwest Montana. See our <a href="/service/about.php">about us</a> page to learn more about MAD Computing and how we got started in the computer repair and troubleshooting industry. For something fun try our <a href="/projects/life/index.php">Life Game</a> or <a href="/projects/fish/index.php">Fish Game</a>.</p>
			<?php /*?><p style="font-size:16px; text-align:center; font-weight:bold;"><a href="/service/contact.php">Contact MAD Computing</a></p><?php */?>
			<h3>Recent Articles</h3>
		<?php
		echo '<div class="homeArticles">';
		foreach($RecentArticles AS $key => $value){
			$ThisArticleTitle = $value->get('Title');
			$ThisDate = $value->FormatDate($value->get('DateEntered'));
			$ThisContent = $value->get('Content');
			$ThisContent = substr(strip_tags($ThisContent),0,300);
			echo '<div><a href="' . $value->MakeLink() . '">' . $ThisArticleTitle . '</a> <span>' . $ThisDate . '</span> ' . $ThisContent . '...</div>';
		}
		echo '</div>';
		echo '</div>';
		$BodyContent = ob_get_contents();
	ob_end_clean();

	include(SiteRoot . "/common/template.php");
?>