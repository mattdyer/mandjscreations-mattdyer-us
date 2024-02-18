<?php
	include("/home/matt/websites/mandjscreations.com/modules/AppInit.php");
	
	if (array_key_exists('Signup', $_POST)){
		if($_POST['Password'] == $_POST['ConfirmPassword'] && strlen($_POST['Email']) > 0 && strlen($_POST['Password']) > 0){
			$user = LoadClass(SiteRoot . '/modules/classes/User');
			$user->set('SiteID',$site->get('SiteID'));
			$user->set('Admin',0);
			$user->set('Email',$_POST['Email']);
			$user->set('FirstName',$_POST['FirstName']);
			$user->set('LastName',$_POST['LastName']);
			$user->save();
			ob_start();
				echo "First Name: " . $user->get('FirstName') . "\n\n";
				echo "Last Name: " . $user->get('LastName') . "\n\n";
				echo "Email: " . $user->get('Email') . "\n\n";
				
				$UserInfo = ob_get_contents();
			ob_end_clean();
			
			mail('madmatt1220@gmail.com','New User Signed Up',$UserInfo,'From: matt@mandjscreations.com');
			
			if(strlen($_POST['Password'])){
				$user->SetPassword($_POST['Password']);
			}
			$site->LoginUser($_POST['Email'],$_POST['Password'],false);
			header('Location: /modules/profile/index.php');
		}else{
			header('Location: /modules/profile/signup.php?Message=Please make sure your password and confirm password are equal and that you entered an email address.');
		}
	}
	
	ob_start();
		echo '<div class="bodyContentSection">';
		echo '<h2>Profile</h2>';
		echo '<div class="BreadCrumb">&gt; <a href="/">Home</a> &gt; Signup</div>';
?>
	<form method="post" enctype="multipart/form-data" name="" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table align="center" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:10px;">
			<tr>
				<td>Create a user account</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>First Name:<br /><input type="text" name="FirstName" /></td>
			</tr>
			<tr>
				<td>Last Name:<br /><input type="text" name="LastName" /></td>
			</tr>
			<tr>
				<td>Email:<br /><input type="text" name="Email" /></td>
			</tr>
			<tr>
				<td>Password:<br /><input type="password" name="Password" /></td>
			</tr>
			<tr>
				<td>Confirm Password:<br /><input type="password" name="ConfirmPassword" /></td>
			</tr>
			<tr>
				<td align="right"><input type="submit" name="Signup" value="Signup" /></td>
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