<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	
	ob_start();
?>
	<h2>Privacy Policy Statement</h2>
	<p>This is the web site of <strong>The Repository</strong>.<br></p>
	<p>
		Our postal address is <br />
		<strong>545 Cheery Lynn Rd.</strong><br />
		<strong>Kalispell, MT 59901</strong>
	</p>
	<p>
		We can be reached using the contact form here <a href="/service/contact.php">Contact Us</a>.
	</p>
	
	<p>For each visitor to our Web page, our Web server automatically recognizes no information regarding the domain or e-mail address.</p>
	<p>We collect the e-mail addresses of those who communicate with us via e-mail, aggregate information on what pages consumers access or visit, information volunteered by the consumer, such as survey information and/or site registrations.</p>
	<p>The information we collect is used to improve the content of our Web page, used to customize the content and/or layout of our page for each individual visitor, , disclosed when legally required to do so, at the request of governmental authorities conducting an investigation, to verify or enforce compliance with the policies governing our Website and applicable laws or to protect against misuse or unauthorized use of our Website.</p>
	<p>With respect to cookies: We use cookies to store visitors preferences, record session information, such as items that consumers add to their shopping cart, record user-specific information on what pages users access or visit.</p>
	
	<p>If you do not want to receive e-mail from us in the future, please let us know by sending us e-mail at the above address, calling us at the above telephone number, writing to us at the above address.</p>
	<p>If you supply us with your postal address on-line you will only receive the information for which you provided us your address.</p>
	<p>Persons who supply us with their telephone numbers on-line will only receive telephone contact from us with information regarding orders they have placed on-line.</p>
	<p>Please provide us with your name and phone number. We will be sure your name is removed from the list we share with other organizations</p>  
	<p>
		With respect to Ad Servers: To try and bring you offers that are of interest to you, we have relationships with other companies that we allow to place ads on our Web pages. As a result of your visit to our site, ad server companies may collect information such as your domain type, your IP address and clickstream information.  For further information, consult the privacy policies of:<br />
		http://www.google.com/privacy.html
	</p>
	<p>From time to time, we may use customer information for new, unanticipated uses not previously disclosed in our privacy notice.  If our information practices change at some time in the future we will post the policy changes to our Web site to notify you of these changes and we will use for these new purposes only data collected from the time of the policy change forward. If you are concerned about how your information is used, you should check back at our Web site periodically.</p>
	<p>Customers may prevent their information from being used for purposes other than those for which it was originally collected by e-mailing us at the above address, calling us at the above telephone number, writing to us at the above address.</p>
	<p>Upon request we provide site visitors with access to communications that the consumer/visitor has directed to our site (e.g., e-mails, customer inquiries), contact information (e.g., name, address, phone number) that we maintain about them , a description of information that we maintain about them.</p>
	<p>Consumers can access this information by e-mail us at the above address, write to us at the above address, writing to us at the above address.</p>
	
	<p>Consumers can have this information corrected by sending us e-mail at the above address, calling us at the above telephone number, writing to us at the above address.</p>
	<p>With respect to security: We have appropriate security measures in place in our physical facilities to protect against the loss, misuse or alteration of information that we have collected from you at our site.</p>
	<p>If you feel that this site is not following its stated information policy, you may contact us at the above addresses or phone number, The DMA's Committee on Ethical Business Practices, state or local chapters of the Better Business Bureau, The Federal Trade Commission by phone at 202.FTC-HELP (202.382.4357) or electronically at <a href="http://www.ftc.gov/ftc/complaint.htm">http://www.ftc.gov/ftc/complaint.htm</a>.</p>
<?php
	$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>