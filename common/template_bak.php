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
<META name="y_key" content="3eedb0c9e86abaf2">
<link rel="stylesheet" type="text/css" href="/common/styles.css"/>
<script type="text/javascript" src="/common/jquery-1.4.2.min.js"></script>
<?php /*?><script type="text/javascript" src="/common/jquery.json-2.2.min.js"></script><?php */?>
<script type="text/javascript" src="/common/scripts.js"></script>
<script type="text/javascript">
	ChatFocused = false;
	currentPageString = '<?php echo $_SERVER['PHP_SELF']; echo '?'; echo $_SERVER['QUERY_STRING']; ?>';
</script>
</head>

<body>
	<div class="mainContainer">
		<?php /*?><div class="topText"><?php echo $Template['TopText']; ?></div><?php */?>
		<div class="logo"><img src="/images/logo.jpg" alt="The Repository The future of life the internet and everything" /></div>
		<div class="header">
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
		</div>
		<div class="topNav">
			<div><a href="/index.php">Home</a></div>
			<div><a href="/modules/articles/index.php">Articles</a></div>
			<div><a href="/projects/life/index.php">Life</a></div>
			<div><a href="/projects/fish/index.php">Fish</a></div>
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
		</div>
		<div class="mainBody">
			<div class="leftNav">
				<!--<div></div>-->
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
	<?php /*?><div style="position:fixed; top:5px; right:5px; width:135px;">
		<div style="background-color:#FFF; font-weight:bold; text-align:center; padding:3px;">User Chat</div>
		<div id="chatContainer" style="background-color:#EEE; height:350px; overflow:auto;"></div>
		<div style="margin:1px 0px 5px 0px; text-align:center;"><textarea name="newMessage" id="newMessage" onfocus="this.value = ''; this.style.color = '#000';" onkeypress="return checkKey(event);" style="width:129px; height:30px; color:#999;">Click here to start chatting</textarea><!--<input type="text" name="newMessage" id="newMessage" onkeypress="checkKey(event);" style="width:131px; font-size:11px;" /> <input type="button" value="Chat" style="font-size:11px;" onclick="sendMessage();" />--></div>
		<div style="background-color:#FFF; font-weight:bold; text-align:center; padding:3px;">Users On This Page</div>
		<div id="chatUsers" style="background-color:#EEE; height:50px; overflow:auto;"></div>
		<div style="background-color:#FFF; font-weight:bold; text-align:center; margin-top:5px; padding:3px;">Click below to talk to other users</div>
		<div id="Conversations" style="background-color:#EEE; height:50px; overflow:auto;"></div>
	</div><?php */?>
<div class="notificationBar">
	<?php
		if($site->UserLoggedIn()){
			echo "<div>Welcome Back " . $user->get('FirstName') . " <a href=\"/modules/profile/logout.php\">Logout</a></div>";
		}else{
			echo '<div><a href="/modules/profile/signup.php">Signup</a> or <a href="/modules/profile/index.php">Login</a></div>';
		}
	?>
	<div onclick="toggleChat();" id="chatButton">
		Chat
		<div id="chatContainer" class="chatCorners" style="display:none;"></div>
		<div id="chatMessageBox" style="display:none;"><textarea name="newMessage" id="generalMessage" class="newMessage" onfocus="this.value = ''; this.style.color = '#000';" onkeypress="return checkKey(event,this);" style="">Click here to start chatting</textarea></div>
	</div>
	<div onclick="toggleChatUsers();">
		Other Users
		<div id="chatUsers" class="chatCorners" style="display:none;"></div>
	</div>
	<div onclick="toggleConversations();">
		Users On Other Pages
		<div id="Conversations" class="chatCorners" style="display:none;"></div>
	</div>
	<?php
		if($site->UserLoggedIn()){
	?>
	<div onclick="toggleFriends();" id="friendButton">
		Friends
		<div id="Friends" class="chatCorners" style="display:none;"></div>
	</div>
	<?php
		}
	?>
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