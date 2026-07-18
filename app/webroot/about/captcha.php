<?php
	session_start();
	
	$random = rand(1, 9).rand(1, 9).rand(1, 9);
	
	$_SESSION['captcha'] = $random;
	
	$captcha = imagecreatefromjpeg("/home/psoz6j4lwzma/public_html/easylogicstechnology.com/app/webroot/about/images/captcha.JPG");
	//$captcha = imagecreatefromjpeg(WWW_ROOT.'img'.DS."captcha.jpg");
	$color = imagecolorallocate($captcha, 0, 0, 0);
	//$font = './code.otf';
	$font ="/home/psoz6j4lwzma/public_html/easylogicstechnology.com/app/webroot/about/code.otf"; 
	imagettftext($captcha, 20, 0, rand(30, 200), rand(20, 70), $color, $font, $random );
	imagepng($captcha);
	imagedestroy($captcha);
?>