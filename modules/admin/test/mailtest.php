<?php
	$RequireLogin = true;
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	
	//define the receiver of the email
	$to = 'madmatt1220@gmail.com';
	//define the subject of the email
	$subject = 'Test email';
	//define the message to be sent. Each line should be separated with \n
	$message = "Hello World!\n\nThis is my first mail.";
	//define the headers we want passed. Note that they are separated with \r\n
	$headers = "From: matt@mandjscreations.com";
	//send the email
	$mail_sent = @mail( $to, $subject, $message, $headers );
	//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
	echo $mail_sent ? "Mail sent" : "Mail failed";
	
 ?>