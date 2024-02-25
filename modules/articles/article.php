<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	$ArticleID = $_GET['ArticleID'];
	$article = LoadClass(SiteRoot . '/modules/classes/articles/Article');
	try{
		$article->load($ArticleID);
	}
	catch(Exception $e){
		header('Location: /modules/articles/index.php');
	}
	if (!array_key_exists('Redirected', $_GET)){
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' . $article->MakeLink());
	}
	/*$article->set('Views',$article->get('Views') + 1);
	$article->set('Content',addslashes($article->get('Content')));
	$article->save();
	$article->set('Content',stripslashes($article->get('Content')));*/
	$category = LoadClass(SiteRoot . '/modules/classes/articles/Category');
	$category->load($article->get('CategoryID'));
	$Comments = $article->GetComments();
	$HistoryArticles = $article->GetHistory();
	
	if(!isset($_SESSION['Articles'])){
		$_SESSION['Articles'] = array();
	}
	if(!isset($_SESSION['Articles']['CommentIDList'])){
		$_SESSION['Articles']['CommentIDList'] = array();
	}
	
	if (array_key_exists('DeleteCommentID', $_GET)){
		$CommentID = $_GET['DeleteCommentID'];
		if(in_array($CommentID,$_SESSION['Articles']['CommentIDList'])){
			$comment = LoadClass(SiteRoot . '/modules/classes/articles/Comment');
			$comment->load($CommentID);
			$comment->delete();
			header('Location: ' . $_SERVER['PHP_SELF'] . '?ArticleID=' . $article->get('ArticleID'));
		}
	}
	
	if (array_key_exists('AddComment', $_POST)) {
		if(array_key_exists('SpamCheck', $_POST) && $_POST['SpamCheck'] == 'abc123'){
			$comment = LoadClass(SiteRoot . '/modules/classes/articles/Comment');
			$comment->set('ArticleID',$_GET['ArticleID']);
			$comment->set('Name',$_POST['Name']);
			$comment->set('Content',$_POST['Content']);
			if($site->UserLoggedIn()){
				$comment->set('UserID',$user->get('UserID'));
			}
			$comment->save();
			$_SESSION['Articles']['CommentIDList'][] = $comment->get('CommentID');
			header('Location: ' . $_SERVER['PHP_SELF'] . '?ArticleID=' . $article->get('ArticleID'));
		}
	}
	
	ob_start();
		echo '<div class="articleMain bodyContentSection">';
		echo '<h2>' . $article->get('Title') . '</h2>';
		echo '<div class="BreadCrumb">&gt; <a href="/">Home</a> &gt; <a href="/modules/articles/index.php">Articles</a> &gt; ' . '<a href="' . $category->MakeLink() . '">' . $category->get('Name') . '</a> &gt; ' . $article->get('Title') . '</div>';
		echo '<div class="articleExtras">';
		echo '<p class="articleDate">' . $article->FormatDate($article->get('DateEntered')) . '</p>';
		echo '<p class="articleViews">' . $article->get('Views') . ' Views</p>';
		echo '<p><!-- AddThis Button BEGIN -->
				<a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;username=madmatt"><img src="http://s7.addthis.com/static/btn/sm-share-en.gif" width="83" height="16" alt="Bookmark and Share" style="border:0"/></a>
				<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=madmatt"></script>
				<!-- AddThis Button END --></p>';
		echo '</div>';
		echo '<div class="articleContent">' . $article->get('Content') . '</div>';
		echo '</div>';
		if(count($HistoryArticles) > 0){
			echo '<div class="articleHistoryMain bodyContentSection">';
			echo '<h3>Article History</h3>';
			echo '<div class="articleHistory">';
			foreach($HistoryArticles AS $key => $value){
				echo '<div><a href="' . $value->MakeLink() . '">' . $value->get('Title') . '</a> - <span class="articleDate">' . $value->get('DateEntered') . '</span></div>';
			}
			echo '</div>';
			echo '</div>';
		}
		echo '<div class="articleCommentsMain bodyContentSection">';
		echo '<h3>Comments</h3>';
		foreach($Comments AS $key => $value){
			$ThisName = $value->get('Name');
			$ThisContent = $value->get('Content');
			$ThisCommentID = $value->get('CommentID');
			$ThisDate = $value->get('DateEntered');
			echo '<div>';
			if(strlen($value->get('UserID')) > 0){
				echo 'Comment By: <a href="/modules/profile/index.php?UserID=' . $value->get('UserID') . '">' . htmlentities($ThisName) . '</a>';
			}else{
				echo 'Comment By: ' . htmlentities($ThisName);
			}
			echo ' - On: <span class="articleDate">' . $value->FormatDate($ThisDate) . '</span>';
			echo '</div>';
			if(in_array($ThisCommentID,$_SESSION['Articles']['CommentIDList'])){
				echo '<div class="CommentTools"><a href="' . $_SERVER['PHP_SELF'] . '?ArticleID=' . $article->get('ArticleID') . '&DeleteCommentID=' . $ThisCommentID . '">[Delete]</a></div>';
			}
			echo '<div class="ArticleComment">' . htmlentities($ThisContent) . '</div>';
		}
		?>
			<script type="text/javascript">
				$(document).ready(function(){
					$('input[name=SpamCheck]').val('abc123');
					$.get('/modules/articles/recordview.php',{ArticleID:<?php echo $article->get('ArticleID'); ?>});
				});
			</script>
			<form method="post" enctype="multipart/form-data" name="CommentForm" class="CommentForm" action="<?php echo htmlentities($_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']); ?>">
				<input type="hidden" name="SpamCheck" value="" />
				<div>Name:<br /><input type="text" name="Name" value="<?php if($site->UserLoggedIn()){echo $user->get('FirstName');} ?>" /></div>
				<div>Comment:<br /><textarea name="Content" style="width:300px; height:150px;" rows="2" cols="2"></textarea></div>
				<div><input type="submit" name="AddComment" value="Add Comment" /></div>
			</form>
		<?php
		echo '</div>';
		$BodyContent = ob_get_contents();
	ob_end_clean();

	include(SiteRoot . "/common/template.php");
?>