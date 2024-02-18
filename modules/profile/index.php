<?php
	include("/home/matt/websites/mandjscreations.com/modules/AppInit.php");
	
	if($site->UserLoggedIn()){
		if(array_key_exists('ApproveFriendID', $_GET)){
			$Friend = LoadClass(SiteRoot . '/modules/classes/profile/Friend');
			$Friend->load($_GET['ApproveFriendID']);
			if($Friend->get('FriendUserID') == $user->get('UserID')){
				$Friend->set('Approved',1);
				$Friend->save();
				header('Location: /modules/profile/index.php?Message=Friend request approved.');
			}else{
				header('Location: /modules/profile/index.php?Message=This friend request cannot be approved by you.');
			}
		}
		
		if(array_key_exists('RemoveFriendID', $_GET)){
			$Friend = LoadClass(SiteRoot . '/modules/classes/profile/Friend');
			$Friend->load($_GET['RemoveFriendID']);
			if($Friend->get('FriendUserID') == $user->get('UserID') | $Friend->get('UserID') == $user->get('UserID')){
				$Friend->delete();
				if(array_key_exists('Deny', $_GET)){
					header('Location: /modules/profile/index.php?Message=Friend request denied');
				}else{
					header('Location: /modules/profile/index.php?Message=Friend removed');
				}
			}
		}
		
		if(array_key_exists('FriendUserID', $_GET)){
			$Friend = LoadClass(SiteRoot . '/modules/classes/profile/Friend');
			$Friend->set('UserID', $user->get('UserID'));
			$Friend->set('FriendUserID',$_GET['FriendUserID']);
			$Friend->set('Approved',0);
			$Friend->save();
			header('Location: /modules/profile/index.php?UserID=' . $_GET['FriendUserID'] . '&Message=Friend request sent');
		}
	}
	
	$ProfileUser = LoadClass(SiteRoot . '/modules/classes/User');
	if (array_key_exists('UserID', $_GET)){
		$ProfileUser->load($_GET['UserID']);
	}else{
		if($site->UserLoggedIn()){
			$ProfileUser->load($user->get('UserID'));
		}else{
			header('Location: /modules/profile/login.php');
			exit;
		}
	}
	
	/*if (!array_key_exists('Redirected', $_GET)){
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' . $ProfileUser->MakeLink());
	}*/
	
	ob_start();
		echo '<div class="bodyContentSection">';
		echo '<h2>Profile for ' . $ProfileUser->get('FirstName') . ' ' . $ProfileUser->get('LastName') . '</h2>';
		echo '<div class="BreadCrumb">&gt; <a href="/">Home</a> &gt; Profile</div>';
		if(array_key_exists('Message', $_GET)){
			echo '<div class="Message">' . $_GET['Message'] . '</div>';
		}
		if($site->UserLoggedIn() && $user->get('UserID') == $ProfileUser->get('UserID')){
			echo '<div class="editProfileLink"><a href="/modules/profile/edit.php">Edit Your Profile</a></div>';
		}
		if($site->UserLoggedIn() && $user->get('UserID') != $ProfileUser->get('UserID')){
			$FriendStatus = $user->checkFriendStatus($ProfileUser->get('UserID'));
			switch ($FriendStatus){
				case 'New':
					echo '<div class="addFriendLink"><a href="/modules/profile/index.php?FriendUserID=' . $ProfileUser->get('UserID') . '">Add as Friend</a></div>';
					break;
				case 'Unapproved':
					echo '<div class="addFriendLink">You have sent a friend request to ' . $ProfileUser->get('FirstName') . ' ' . $ProfileUser->get('LastName') . '</div>';
					break;
				case 'Requested':
					echo '<div class="addFriendLink">' . $ProfileUser->get('FirstName') . ' ' . $ProfileUser->get('LastName') . ' has sent a friend request to you</div>';
					break;
				case 'Approved':
					echo '<div class="addFriendLink">You are friends with ' . $ProfileUser->get('FirstName') . ' ' . $ProfileUser->get('LastName') . '</div>';
					break;
			}
		}
		echo '<div class="ProfileContainer">';
			echo '<div id="ProfileInformation" class="ProfileSection">';
				echo '<h3 id="ProfileInformation_Title" class="ProfileTitle">Profile Information</h3>';
				echo "<div><strong>Name: </strong>" . $ProfileUser->get('FirstName') . " " . $ProfileUser->get('LastName') . "</div>";
			echo '</div>';
				
			$Friends = $ProfileUser->getFriends();
			echo '<div id="ProfileFriends" class="ProfileSection">';
			echo '<h3 id="ProfileFriends_Title" class="ProfileTitle">Friends</h3>';
			if(count($Friends) > 0){
				
				foreach($Friends as $key => $NextFriend){
					$frienduser = LoadClass(SiteRoot . '/modules/classes/User');
					$frienduser->load($NextFriend['UserID']);
					echo '<div><a href="' . $frienduser->MakeLink() . '">' . $NextFriend['FirstName'] . ' ' . $NextFriend['LastName'] . '</a> <a href="/modules/profile/index.php?RemoveFriendID=' . $NextFriend['FriendID'] . '">Remove</a></div>';
				}
				
			}else{
				echo '<div>You haven\'t added any friends yet.</div>';
			}
			echo '</div>';
			
			if($site->UserLoggedIn() && $user->get('UserID') == $ProfileUser->get('UserID')){
				$FriendRequests = $ProfileUser->getFriendRequests();
				if(count($FriendRequests) > 0){
					echo '<div id="ProfileFriendRequests" class="ProfileSection">';
					echo '<h3 id="ProfileFriendRequests_Title" class="ProfileTitle">Friend Requests</h3>';
					foreach($FriendRequests as $key => $NextRequest){
						echo '<div>Friend request from <a href="/modules/profile/index.php?UserID=' . $NextRequest['UserID'] . '">' . $NextRequest['FirstName'] . ' ' . $NextRequest['LastName'] . '</a> <a href="/modules/profile/index.php?ApproveFriendID=' . $NextRequest['FriendID'] . '">Approve</a> <a href="/modules/profile/index.php?RemoveFriendID=' . $NextRequest['FriendID'] . '&Deny=1">Deny</a></div>';
					}
					echo '</div>';
				}
			}
			
			$ArticleComments = $ProfileUser->getArticleComments();
			if(count($ArticleComments) > 0){
				echo '<div id="ProfileArticleComments" class="ProfileSection">';
				echo '<h3 id="ProfileArticleComments_Title" class="ProfileTitle">Article Comments</h3>';
				foreach($ArticleComments as $key => $comment){
					$article = $comment->getArticle();
					echo '<div>';
					echo '<a href="' . $article->MakeLink() . '">' . $article->get('Title') . '</a> ' . substr(strip_tags($article->get('Content')),0,150) . '...';
					echo '<div><strong>Comment: </strong>' . substr(strip_tags($comment->get('Content')),0,150) . '...</div>';
					echo '</div>';
				}
				echo '</div>';
			}
				
			if($site->UserLoggedIn() && $user->get('UserID') == $ProfileUser->get('UserID')){
				//echo '<div id="ProfileMealPicker" class="ProfileSection">';
				//echo '<h3 id="ProfileMealPicker_Title" class="ProfileTitle">Meal Picker</h3>';
		?>
			<form method="post" enctype="multipart/form-data" name="MealPickerForm" action="<?php echo $_SERVER['PHP_SELF']; echo '?'; echo $_SERVER['QUERY_STRING']; ?>">
				
			</form>
		<?php
				//echo '</div>';
			}
		echo '</div>';
		?>
			<script type="text/javascript">
				$(function(){
					var ProfileSections = $('.ProfileContainer div');
					var TabTitles = $('.ProfileContainer h3');
					var TabContainer = $('<div></div>');
					TabContainer.css({'margin-left':'10px'});
					$('.ProfileContainer').prepend(TabContainer);
					TabTitles.each(function(){
						var NewTab = $('<div></div>');
						NewTab.html($(this).html());
						NewTab.addClass('ProfileTab');
						var TitleID = $(this).attr('id');
						var SectionName = TitleID.split('_')[0];
						NewTab.attr('id',SectionName + '_Tab');
						NewTab.click(function(){
							var SectionName = $(this).attr('id').split('_')[0];
							$('.ProfileTab').removeClass('ProfileTabActive');
							$('.ProfileSection').hide();
							$('#' + SectionName).show();
							$('#' + SectionName + '_Tab').addClass('ProfileTabActive');
						});
						TabContainer.append(NewTab);
					});
					$($('.ProfileTab')[0]).trigger('click');
				});
			</script>
		<?php
		echo '</div>';
		$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>