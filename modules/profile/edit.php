<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	
	if(!$site->UserLoggedIn()){
		header('Location: /modules/profile/login.php');
		exit;
	}
	
	if (array_key_exists('SaveUser', $_POST)){
		$user->set('Email',$_POST['Email']);
		$user->set('FirstName',$_POST['FirstName']);
		$user->set('LastName',$_POST['LastName']);
		$user->save();
		if(strlen($_POST['NewPassword'])){
			$user->SetPassword($_POST['NewPassword']);
		}
		$_SESSION['User'] = serialize($user);
		header('Location: ' . $_SERVER['PHP_SELF'] . "?Message=Settings Updated");
		exit;
	}
	
	ob_start();
		echo '<h2>Edit Your Profile</h2>';
		echo '<div class="BreadCrumb">&gt; <a href="/">Home</a> &gt; <a href="/modules/profile/index.php">Profile</a> &gt; Edit Profile</div>';
		?>
			<form method="post" enctype="multipart/form-data" name="" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<table width="100%" cellpadding="0" cellspacing="0" border="0" class="profileTable">
					<?php
						if(array_key_exists('Message', $_GET)){
						echo '<tr><td><div class="Message">' . $_GET['Message'] . '</div></td></tr>';
						}
					?>
					<tr>
						<td>First Name:<br /><input type="text" name="FirstName" value="<?php echo $user->get('FirstName'); ?>" /></td>
					</tr>
					<tr>
						<td>Last Name:<br /><input type="text" name="LastName" value="<?php echo $user->get('LastName'); ?>" /></td>
					</tr>
					<tr>
						<td>Email:<br /><input type="text" name="Email" value="<?php echo $user->get('Email'); ?>" /></td>
					</tr>
					<tr>
						<td>Change Password:<br /><input type="password" name="NewPassword" autocomplete="off" /></td>
					</tr>
					<tr>
						<td><input type="submit" name="SaveUser" value="Save" /></td>
					</tr>
				</table>
			</form>
		<?php
		$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>