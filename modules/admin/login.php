<?php
	include("/var/www/html/modules/AppInit.php");
	
	if (array_key_exists('Login', $_POST)) {
		try{
			$site->LoginUser($_POST['Email'],$_POST['Password'],true);
			header('Location: /modules/admin/index.php');
			exit;
		}catch(Exception $e){
			header('Location: /modules/admin/login.php?Message=' . urlencode($e) . '.');
		}
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin Login</title>
</head>

<body>
	<form method="post" enctype="multipart/form-data" name="" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table align="center" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>Login to access the admin</td>
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
</body>
</html>