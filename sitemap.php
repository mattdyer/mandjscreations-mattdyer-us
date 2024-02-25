<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	
	ob_start();
?>
	<h2>Sitemap</h2>
	<ul>
		<li><a href="http://www.mandjscreations.com/">Home</a></li>
		<li><a href="http://www.mandjscreations.com/service/contact.php">Contact Us</a></li>
		<li><a href="http://www.mandjscreations.com/index.php">Home</a></li>
		<li><a href="http://www.mandjscreations.com/modules/articles/index.php">Articles</a></li>
		<li><a href="http://www.mandjscreations.com/projects/life/index.php">Life Game</a></li>
		<li><a href="http://www.mandjscreations.com/projects/fish/index.php">Fish Game</a></li>
		<li><a href="http://www.mandjscreations.com/projects/canvaspaint/index.php">Canvas Paint Emulator</a></li>
		<li><a href="http://www.mandjscreations.com/modules/profile/login.php">Profile Login</a></li>
		<li><a href="http://www.mandjscreations.com/modules/profile/signup.php">Profile Signup</a></li>
		<li><a href="http://www.mandjscreations.com/projects/transition/index.php">Transitions Project</a></li>
		<li><a href="http://www.mandjscreations.com/projects/htmledit/index.php">Javascript HTML Editor Newest Version</a></li>
		<li><a href="http://www.mandjscreations.com/projects/jsrts/index.php">Javascript Real Time Strategy Game</a></li>
		<li><a href="http://www.mandjscreations.com/service/about.php">About Us</a></li>
		<li><a href="http://www.mandjscreations.com/service/privacy.php">Privacy Policy</a></li>
		<li><a href="http://www.mandjscreations.com/sitemap.php">Sitemap</a></li>
		<li><a href="http://www.mandjscreations.com/projects/transition/index2.php">CSS Transitions</a></li>
		<li><a href="http://www.mandjscreations.com/projects/htmledit/alpha1/index.php">Javascript HTML Editor Alpha 1</a></li>
<?php
	$Categories = $site->Modules['Articles']->GetCategories();
	$Articles = $site->Modules['Articles']->GetArticles();
	$Users = $site->GetUsers();
	foreach($Articles AS $key => $value){
		echo '<li><a href="' . $value->MakeLink() . '">Article: ' . $value->get('Title') . '</a></li>';
	}
	foreach($Categories AS $key => $value){
		echo '<li><a href="' . $value->MakeLink() . '">Category: ' . $value->get('Name') . '</a></li>';
	}
	foreach($Users AS $key => $value){
		echo '<li><a href="' . $value->MakeLink() . '">User Profile: ' . $value->get('FirstName') . ' ' . $value->get('LastName') . '</a></li>';
	}
?>
	</ul>
<?php
	$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>