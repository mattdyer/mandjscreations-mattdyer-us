<?php
	$RequireLogin = true;
	include("/home/matt/websites/mandjscreations.com/modules/AppInit.php");
	
	$Categories = $site->Modules['Articles']->GetCategories();
	
	$article = LoadClass(SiteRoot . '/modules/classes/articles/Article');
	$category = LoadClass(SiteRoot . '/modules/classes/articles/Category');
	if (array_key_exists('ArticleID', $_GET)){
		$article->load($_GET['ArticleID']);
		$category->load($article->get('CategoryID'));
		$TempCategoryID = $article->get('CategoryID');
		$HistoryArticles = $article->GetHistory();
	}
	
	if (array_key_exists('CategoryID', $_GET)){
		$category->load($_GET['CategoryID']);
		$TempCategoryID = $_GET['CategoryID'];
	}
	
	if (array_key_exists('DeleteHistoryArticleID', $_GET)) {
		$historyarticle = LoadClass(SiteRoot . '/modules/classes/articles/Article');
		$historyarticle->load($_GET['DeleteHistoryArticleID']);
		$historyarticle->delete();
		header('Location: ' . $_SERVER['PHP_SELF'] . '?ArticleID=' . $article->get('ArticleID') . '&CategoryID=' . $article->get('CategoryID'));
		exit;
	}
	
	if (array_key_exists('SaveArticle', $_POST)) {
		if($article->IsNewRecord() || strlen($article->get('ArticleHistoryID')) == 0){
			$articlehistory = LoadClass(SiteRoot . '/modules/classes/articles/ArticleHistory');
			$articlehistory->set('Name',$_POST['Title']);
			$articlehistory->save();
			$article->set('ArticleHistoryID',$articlehistory->get('ArticleHistoryID'));
		}
		$article->set('CategoryID',$_POST['CategoryID']);
		$article->set('Title',$_POST['Title']);
		$article->set('Content',$_POST['Content']);
		if($_POST['CreateHistory'] == 1){
			$article->copyrecord();
		}
		$article->save();
		//echo $_POST['Content'];
		header('Location: ' . $_SERVER['PHP_SELF'] . '?ArticleID=' . $article->get('ArticleID') . '&CategoryID=' . $article->get('CategoryID'));
		exit;
	}
	
	ob_start();
	echo '<a href="/modules/articles/admin/categories/index.php?CategoryID=' . $category->get('CategoryID') . '">Back to ' . $category->get('Name') . '</a><br />';
	echo $article->get('Title');
	
?>

<form method="post" enctype="multipart/form-data" name="" action="<?php echo $_SERVER['PHP_SELF']; echo '?'; echo $_SERVER['QUERY_STRING']; ?>">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td>
				Category:<br />
				<select name="CategoryID">
					<?php
						foreach($Categories AS $key => $value){
							$ThisCategoryID = $value->get('CategoryID');
							$ThisCategoryName = $value->get('Name');
							if($ThisCategoryID == $category->get('CategoryID'))
							{
								echo '<option value="' . $ThisCategoryID . '" selected="selected">' . $ThisCategoryName . '</option>';
							}else{
								echo '<option value="' . $ThisCategoryID . '">' . $ThisCategoryName . '</option>';
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<?php
			if (array_key_exists('ArticleID', $_GET)){
		?>
		<tr>
			<td><?php echo $article->FormatDate($article->get('DateEntered')) ?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<?php
			}
		?>
		<tr>
			<td>Title:<br /><input type="text" name="Title" style="width:100%;" value="<?php echo $article->get('Title'); ?>" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				Content:<br />
				<textarea name="Content" style="height:400px; width:600px;"><?php echo $article->get('Content'); ?></textarea>
		   	</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align="right">
				<span style="white-space:nowrap;"><label for="CreateHistoryYes">Yes</label> <input type="radio" name="CreateHistory" id="CreateHistoryYes" value="1" checked="checked" /></span>
				<span style="white-space:nowrap;"><label for="CreateHistoryNo">No</label> <input type="radio" name="CreateHistory" id="CreateHistoryNo" value="0" /></span>
				<input type="submit" name="SaveArticle" value="Save" />
			</td>
		</tr>
	</table>
</form>

<?php
	if (array_key_exists('ArticleID', $_GET)){
		if(count($HistoryArticles) > 0){
			echo '<h3>Article History</h3>';
			foreach($HistoryArticles AS $key => $value){
				echo '<div><a href="' . $_SERVER['PHP_SELF'] . '?ArticleID=' . $value->get('ArticleID') . '">' . $value->get('Title') . '</a> - <span class="articleDate">' . $value->FormatDate($value->get('DateEntered')) . '</span> <a href="' . $_SERVER['PHP_SELF'] . '?ArticleID=' . $article->get('ArticleID') . '&DeleteHistoryArticleID=' . $value->get('ArticleID') . '">Delete</a> </div>';
			}
		}
	}
	$BodyContent = ob_get_contents();
	ob_end_clean();
	include("/home/matt/websites/mandjscreations.com/common/admintemplate.php");
?>