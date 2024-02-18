<?php
	$RequireLogin = true;
	include("/home/matt/websites/mandjscreations.com/modules/AppInit.php");
	
	$page = LoadClass(SiteRoot . '/modules/classes/seo/Page');
	
	if (array_key_exists('PageID', $_GET)) {
		$page->load($_GET['PageID']);
	}
	
	if (array_key_exists('SavePage', $_POST)) {
		$page->set('SiteID',$site->get('SiteID'));
		$page->updateAttributes($_POST);
		$page->save();
		header('Location: ' . $_SERVER['PHP_SELF'] . '?PageID=' . $page->get('PageID'));
	}
	
	$PageTypes = array('Articles','Articles_Article','Articles_Category','Profile_Profile','Project','Custom');
	
	ob_start();
?>
	<div><a href="/modules/seo/admin/index.php">Back</a></div>
	<form method="post" enctype="multipart/form-data" name="PageForm" action="<?php echo $_SERVER['PHP_SELF']; echo '?'; echo $_SERVER['QUERY_STRING']; ?>">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>
					Page Type:<br />
					<select name="PageType">
						<?php
							foreach($PageTypes as $key => $PageType){
								echo '<option value="' . $PageType . '"' . ($PageType == $page->get('PageType') ? ' selected="selected"' : '') . '>' . $PageType . '</option>';
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Name:<br /><input type="text" name="Name" style="width:90%;" value="<?php echo $page->get('Name'); ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>SEO URL:<br /><input type="text" name="SEOURL" style="width:90%;" value="<?php echo $page->get('SEOURL'); ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Script Name:<br /><input type="text" name="ScriptName" style="width:90%;" value="<?php echo $page->get('ScriptName'); ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Title:<br /><input type="text" name="Title" style="width:90%;" value="<?php echo $page->get('Title'); ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Description:<br /><input type="text" name="Description" style="width:90%;" value="<?php echo $page->get('Description'); ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Keywords:<br /><input type="text" name="Keywords" style="width:90%;" value="<?php echo $page->get('Keywords'); ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="right"><input type="submit" name="SavePage" value="Save" /></td>
			</tr>
		</table>
	</form>
<?php
		
		$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>
 
<?php
	include("/home/matt/websites/mandjscreations.com/common/admintemplate.php");
?>