<?php
	$RequireLogin = true;
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	$category = LoadClass(SiteRoot . '/modules/classes/articles/Category');
	
	if (array_key_exists('CategoryID', $_GET)) {
		$CategoryID = $_GET['CategoryID'];
		$category->load($CategoryID);
	}
	
	if (array_key_exists('SaveCategory', $_POST)) {
		$category->set('SiteID',$site->get('SiteID'));
		$category->set('Name',$_POST['Name']);
		$category->save();
		header('Location: ' . $_SERVER['PHP_SELF'] . '?CategoryID=' . $category->get('CategoryID'));
	}
	
	ob_start();
?>
	<div><a href="/modules/articles/admin/index.php">Back</a></div>
	<form method="post" enctype="multipart/form-data" name="" action="<?php echo $_SERVER['PHP_SELF']; echo '?'; echo $_SERVER['QUERY_STRING']; ?>">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>Name:<br /><input type="text" name="Name" style="width:100%;" value="<?php echo $category->get('Name'); ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="right"><input type="submit" name="SaveCategory" value="Save" /></td>
			</tr>
		</table>
	</form>

<?php
		$BodyContent = ob_get_contents();
	ob_end_clean();
?>

<?php
	include(SiteRoot . "/common/admintemplate.php");
?>