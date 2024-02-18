<?php
	include("/home/matt/websites/mandjscreations.com/modules/templateinit.php");
?>

<?php
	$LeftNavCategories = $site->Modules['Articles']->GetCategories();
	$PopularArticles = $site->Modules['Articles']->GetPopularArticles();
	if(!isset($Template['Metatags']['description'])){
		$Template['Metatags']['description'] = 'Information and discussion of web design, physics, astronomy and other interesting topics.';
	}
	if(!isset($Template['Metatags']['keywords'])){
		$Template['Metatags']['keywords'] = 'web design, physics, astronomy, science, space, technology, internet, knowledge, repository';
	}
	if(strlen($Template['Title']) == 0){
		$Template['Title'] = 'The Repository - The future of life, the internet and everything';
	}
	$Template['TopText'] = 'The Repository - ' . $Template['TopText'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $Template['Title']; ?></title>
<?php
	foreach($Template['Metatags'] AS $key => $value){
		echo "<meta name=\"" . $key . "\" content=\"" . $value . "\" />\n";
	}
?>
<?php /*?><meta name="description" content="Information and discussion of web design, physics, astronomy and other interesting topics." />
<meta name="keywords" content="web design, physics, astronomy, science, space, technology, internet, knowledge, repository" /><?php */?>
<meta name="google-site-verification" content="gZDkqLyakMyIc9KLTrnIdHNuFhvuN7p0gZ8CAN6zrCU" />
<!--<link rel="stylesheet" type="text/css" href="/common/styles.css"/>-->
<style type="text/css">
	body{
		overflow:hidden;
		padding:0px;
		margin:0px;
	}
	.headerContent{
		position:fixed;
		top:0px;
		left:160px;
		height:250px;
		width:60%;
		overflow:hidden;
	}
	.headerAd{
		position:fixed;
		top:0px;
		right:0px;
		width:25%;
	}
	.rightNav{
		position:fixed;
		top:0px;
		left:0px;
		width:15%
	}
	.logo{
		position:fixed;
		top:250px;
		left:160px;
	}
	.topNav{
		position:fixed;
		top:250px;
		left:429px;
		height:64px;
		background-color:#000;
	}
	.topNav div{
		display:inline-block;
		padding:18px 25px;
		vertical-align:middle;
	}
	.topNav a{
		color:#FFF;
		text-decoration:none;
		font-size:14px;
		font-weight:bold;
	}
	.topNav a:visited{
		color:#FFF;
		text-decoration:none;
	}
	.topNav a:hover{
		color:#FFF;
		text-decoration:underline;
	}
	.bodyContent{
		position:fixed;
		top:314px;
		left:160px;
		height:250px;
		overflow:scroll;
	}
</style>
</head>

<body>
	<div class="headerContent">
		<?php
			for($i=0;$i < 5;$i++){
				$ThisArticleID = $PopularArticles[$i]->get('ArticleID');
				$ThisArticleTitle = $PopularArticles[$i]->get('Title');
				$ThisContent = $PopularArticles[$i]->get('Content');
				$ThisContent = substr(strip_tags($ThisContent),0,150);
				echo '<div><a href="/modules/articles/article.php?ArticleID=' . $ThisArticleID . '">' . $ThisArticleTitle . '</a> ' . $ThisContent . '...</div>';
			}
		?>
	</div>
	<div class="headerAd">
		<script type="text/javascript">
			<!--
			google_ad_client = "pub-2015207451088384";
			/* Top right, created 11/4/09 */
			google_ad_slot = "1176299400";
			google_ad_width = 300;
			google_ad_height = 250;
			//-->
		</script>
		<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
	</div>
	<div class="rightNav">
		<script type="text/javascript">
			<!--
			google_ad_client = "pub-2015207451088384";
			/* Right Side, created 11/1/09 */
			google_ad_slot = "1544421503";
			google_ad_width = 160;
			google_ad_height = 600;
			//-->
		</script>
		<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
	</div>
	<div class="logo"><img src="/images/logo.jpg" alt="The Repository The future of life the internet and everything" /></div>
	<div class="topNav">
		<div><a href="/index.php">Home</a></div>
		<div><a href="/modules/articles/index.php">Articles</a></div>
		<div><a href="/projects/life/index.php">Life</a></div>
		<div><a href="/projects/fish/index.php">Fish</a></div>
		<div><a href="/modules/profile/index.php">My Profile</a></div>
		<?php
			if($site->UserLoggedIn()){
				echo '<div><a href="/modules/profile/logout.php">Logout</a></div>';
			}else{
				echo '<div><a href="/modules/profile/signup.php">Signup</a></div>';
			}
		?>
	</div>
	<div class="bodyContent"><?php echo $BodyContent ?></div>
		<div class="mainBody">
			<div class="leftNav">
				<div></div>
				<ul>
					<?php
					foreach($LeftNavCategories AS $key => $value){
						$ThisCategoryID = $value->get('CategoryID');
						$ThisCategoryName = $value->get('Name');
						echo '<li><a href="/modules/articles/category.php?CategoryID=' . $ThisCategoryID . '">' . $ThisCategoryName . '</a></li>';
					}
					?>
				</ul>
			</div>
			
			
		</div>
		<div class="footer">
			<div><a href="/service/about.php">About Us</a> | <a href="/service/contact.php">Contact Us</a> | <a href="/service/privacy.php">Privacy Policy</a> | <a href="/sitemap.php">Sitemap</a></div>
			<div>Copyright &copy; 2009 - <?php echo date('Y');?></div>
		</div>
	<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	try {
	var pageTracker = _gat._getTracker("UA-11477588-1");
	pageTracker._trackPageview();
	} catch(err) {}</script>
</body>
</html>