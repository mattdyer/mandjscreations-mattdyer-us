<?php
	include("/home/matt/websites/mandjscreations.com/modules/templateinit.php");

	$LeftNavCategories = $site->Modules['Articles']->GetCategories();
	$PopularArticles = $site->Modules['Articles']->GetPopularArticles();
	if(!isset($Template['Metatags']['description'])){
		$Template['Metatags']['description'] = 'MAD (M. A. D.) Computing can solve your problems in Kalispell MT, Montana, Whitefish, Columbia Falls, Big Fork, Northwest Montana with locking up, slow boot up, viruses, spyware, and any other computer problems you might be experiencing.';
	}
	if(!isset($Template['Metatags']['keywords'])){
		$Template['Metatags']['keywords'] = 'computer repair, Kalispell, computer tune-ups, Whitefish, spyware removal, Columbia Falls, virus removal, Big Fork, hardware replacement, Bigfork, solving computer problems, Northwest Montana, software instruction, Northwest MT, windows troubleshooting, network setup, wireless network setup';
	}
	if(strlen($Template['Title']) == 0){
		$Template['Title'] = 'MAD Computing - Exploring New Techniques in Web Design, Web Programming and Javascript Programming';
	}
	$Template['TopText'] = 'MAD Computing - ' . $Template['TopText'];
	
	if (array_key_exists('ContentOnly', $_GET)){
		echo $BodyContent;
	}else{
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
<meta name="y_key" content="3eedb0c9e86abaf2" />
<link rel="stylesheet" type="text/css" href="/common/styles.css"/>
<script type="text/javascript" src="/common/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="/common/jquery-ui-1.8.4.custom.min.js"></script>
<?php /*?><script type="text/javascript" src="/common/jquery.json-2.2.min.js"></script><?php */?>
<script type="text/javascript" src="/common/scripts.js"></script>
<script type="text/javascript">
	ChatFocused = false;
	currentPageString = '<?php echo htmlentities($_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']); ?>';
</script>
<?php
	if($site->UserLoggedIn()){ 
?>
<link rel="stylesheet" type="text/css" href="/common/userstyles.php"/>
<script type="text/javascript" src="/common/userscripts.php"></script>
<?php
	}
?>
</head>

<body>
	<div class="mainContainer">
		<?php /*?><div class="topText"><?php echo $Template['TopText']; ?></div>
		<div class="pageTop">
			<div class="logo">
				<div class="HTMLLogo">
					M.A.D.<br />
					COMPUTING
				</div>
			</div>
			<div class="contactTop">
				<div><a href="/service/contact.php">Contact MAD Computing</a></div>
				<div>Call us at 406-270-1483</div>
			</div>
		</div><?php */?>
		
			<?php
				if($_SERVER['PHP_SELF'] == '/modules/articles/article.php' || $_SERVER['PHP_SELF'] == '/modules/articles/category.php' || $_SERVER['PHP_SELF'] == '/modules/articles/index.php'){
			?>
			<div class="header2">
				<div class="logo">
					<div class="HTMLLogo">
						M.A.D.<br />
						COMPUTING
					</div>
				</div>
				<div class="headerAd2">
					<script type="text/javascript"><!--
					google_ad_client = "pub-2015207451088384";
					
					google_ad_slot = "1475179868";
					google_ad_width = 728;
					google_ad_height = 90;
					//-->
					</script>
					<script type="text/javascript"
					src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
					</script>
				</div>
			</div>
			<?php 
			}else{
			?>
			<div class="header">
				<div class="headerContent">
					<div class="logo">
						<div class="HTMLLogo">
							M.A.D.<br />
							COMPUTING
						</div>
					</div>
					<h3>Popular Articles</h3>
					<?php
						for($i=0;$i < 4;$i++){
							$ThisArticleLink = $PopularArticles[$i]->MakeLink();
							$ThisArticleTitle = $PopularArticles[$i]->get('Title');
							$ThisContent = $PopularArticles[$i]->get('Content');
							$ThisContent = substr(strip_tags($ThisContent),0,120);
							$ThisViews = $PopularArticles[$i]->get('Views');
							echo '<div><a href="' . $ThisArticleLink . '">' . $ThisArticleTitle . '</a> <span class="PopularArticleViews">' . $ThisViews . ' Views</span> ' . $ThisContent . '...</div>';
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
			</div>
			<?php
			}
			?>
		<div class="topNav">
			<div><a href="/">Home</a></div>
			<div><a href="/modules/articles/index.php">Articles</a></div>
			<div><a href="/projects/life/index.php">Life</a></div>
			<div><a href="/projects/fish/index.php">Fish</a></div>
			<div><a href="/service/contact.php">Contact Us</a></div>
			<?php
				if($site->UserLoggedIn()){
					echo '<div><a href="/modules/profile/index.php">My Profile</a></div>';
				}else{
					echo '<div><a href="/modules/profile/index.php">Login</a></div>';
				}
			?>
			<?php
				if($site->UserLoggedIn()){
					echo '<div><a href="/modules/profile/logout.php">Logout</a></div>';
				}else{
					echo '<div><a href="/modules/profile/signup.php">Signup</a></div>';
				}
			?>
			<div><a href="http://twitter.com/themadtw1tter" target="_blank"><img src="/images/twitter.png" style="border:none;" /></a></div>
		</div>
		<div class="mainBody">
			<div class="leftNav">
				<?php /*?><div></div><?php */?>
				<h3>Articles</h3>
				<ul>
					<?php
					foreach($LeftNavCategories AS $key => $value){
						$ThisCategoryLink = $value->MakeLink();
						$ThisCategoryName = $value->get('Name');
						echo '<li><a href="' . $ThisCategoryLink . '">' . $ThisCategoryName . '</a></li>';
					}
					?>
				</ul>
				<h3>Projects</h3>
				<ul>
					<li><a href="/projects/fish/index.php">Fish</a></li>
					<li><a href="/projects/life/index.php">Life</a></li>
					<li><a href="/projects/canvaspaint/index.php">Canvas Paint</a></li>
					<li><a href="/projects/transition/index.php">Transition CSS</a></li>
					<li><a href="/projects/htmledit/index.php">HTMLEdit</a></li>
					<li><a href="/projects/jsrts/index.php">JSRTS</a></li>
				</ul>
			</div>
			<div class="bodyContent"><?php echo $BodyContent ?></div>
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
		</div>
		<div class="footer">
			<div><a href="/service/about.php">About Us</a> | <a href="/service/contact.php">Contact Us</a> | <a href="/service/privacy.php">Privacy Policy</a> | <a href="/sitemap.php">Sitemap</a></div>
			<div>Copyright &copy; 2009 - <?php echo date('Y');?></div>
		</div>
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
<script type="text/javascript">
   var addthis_config = {
	  data_ga_tracker: pageTracker
   };
</script>
</body>
</html>

<?php
}
?>
