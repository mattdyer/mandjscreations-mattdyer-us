<?php
	include("/var/www/html/modules/admintemplateinit.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin</title>
</head>

<body>
	<table width="950" cellpadding="0" cellspacing="0" border="0" align="center">
		<tr>
			<td><h1>Admin</h1></td>
		</tr>
		<tr>
			<td>
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td valign="top" style="width:150px;">
							<h3>Left Nav</h3>
							<div><a href="/modules/admin/logout.php">Logout</a></div>
							<?php
								echo '<div><a href="/modules/admin/index.php">Home</a></div>';
								echo '<div><a href="/modules/admin/users/index.php">Users</a></div>';
								for($i = 0;$i<sizeof($AdminNavLinks);$i++){
									echo '<div><a href="'.$AdminNavLinks[$i]['Link'].'">'.$AdminNavLinks[$i]['Name'].'</a></div>';
								}
							?>
						</td>
						<td valign="top"><?php echo $BodyContent; ?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>