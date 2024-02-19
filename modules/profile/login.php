<?php
	include("/var/www/html/modules/AppInit.php");
	
	if (array_key_exists('Login', $_POST)) {
		try{
			$site->LoginUser($_POST['Email'],$_POST['Password'],false);
			header('Location: /modules/profile/index.php');
			exit;
		}catch(Exception $e){
			header('Location: /modules/profile/login.php?Message=' . urlencode($e) . '.');
		}
	}
	
	ob_start();
		echo '<div class="bodyContentSection">';
		echo '<h2>Profile</h2>';
		echo '<div class="BreadCrumb">&gt; <a href="/">Home</a> &gt; Login</div>';
?>
	<form method="post" enctype="multipart/form-data" name="LoginForm" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table align="center" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:10px;">
			<tr>
				<td>User Login</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Email:<br /><input type="text" name="Email" /></td>
			</tr>
			<tr>
				<td>Password:<br /><input type="password" name="Password" /></td>
			</tr>
			<tr>
				<td align="right"><input type="submit" name="Login" value="Login" /></td>
			</tr>
		</table>
	</form>
<?php
		echo '</div>';
		$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>