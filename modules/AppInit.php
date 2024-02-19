<?php 
	
	function customErrorHandler($errno, $errstr, $errfile, $errline, $errcontext){
		if($errno <= 128){
			ob_start();
			
					echo "Error Number: " . $errno . "\n\n";
					echo "Error String: " . $errstr . "\n\n";
					echo "Error File: " . $errfile . "\n\n";
					echo "Error Line: " . $errline . "\n\n";
					var_dump($errcontext['_POST']);
					var_dump($errcontext['_GET']);
					var_dump($errcontext['_SERVER']);
					var_dump($errcontext['_SESSION']);
					foreach($errcontext as $key => $value){
						if(is_string($value)|is_numeric($value)){
							echo '[' . $key . ': ' . $value . ']';
						}
					}
					
					print_r(debug_backtrace());
					
					$ErrorContent = ob_get_contents();
			ob_end_clean();
			
			print('<pre>');
			print_r($ErrorContent);
			print('</pre>');
			
			
			
			die('Error Occured');
			
			/*
			mail('matt@mandjscreations.com',$errstr,$ErrorContent,'From: matt@mandjscreations.com');
			if($errno <= 8){
				mail('madmatt1220@gmail.com',$errstr,$ErrorContent,'From: matt@mandjscreations.com');
			}
			//header('HTTP/1.1 500 Internal Server Error');
			die('Error Occured');
			*/
		}
		return false;
	}
	
	set_error_handler("customErrorHandler");
	
	define('SiteRoot','/var/www/html');
	
	//$SiteRoot = '/var/www/html';
	
	session_start();
	
	if(!isset($RequireLogin)){
		$RequireLogin = false;
	}
	
	if($RequireLogin && !isset($_SESSION['User'])){
		header('Location: /modules/admin/login.php');
		exit;
	}
	
	if(isset($_SESSION['User'])){
		$user = unserialize($_SESSION['User']);
		if($RequireLogin && $user->get('Admin') == 0){
			header('Location: /index.php');
			exit;
		}
	}
	function __autoload($class_name){
		require_once(SiteRoot . '/modules/classes/User.php');
	}
	
	function LoadClass($ClassPath){
		require_once($ClassPath . '.php');
		$ClassName = basename($ClassPath);
		return new $ClassName;
	}
	
	function RandomString($length){
		$randstring = '';
		for($i=1;$i<=$length;$i++){
			$randstring.=chr(rand(48,126));
		}
		return $randstring;
	}
	
	$site = LoadClass(SiteRoot . '/modules/classes/Site');
	
	switch ($_SERVER['HTTP_HOST']){
		case 'www.mandjscreations.com':
			$site->load(1);
			$SiteRoot = '/var/www/html';
			break;
		case 'www.cantercreekmontana.com':
			$site->load(2);
			$SiteRoot = '/home/matt/websites/cantercreekmontana.com';
			break;
		default:
			$site->load(1);
			$SiteRoot = '/var/www/html';
	}
	
	
	$site->LoadModules();
?>