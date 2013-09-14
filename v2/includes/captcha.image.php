<?php 

createImage();
exit();

function createImage() {
	$firstNumber = rand(0,9);
	$secondNumber = rand(0,9);
	$thirdNumber = rand(0,9);
	
	$securityCode = $firstNumber + $secondNumber + $thirdNumber;
	$hashedCode = hash("ripemd160",$securityCode);
	
	$imageString = $_GET['test'].$firstNumber." + ".$secondNumber." - ".$thirdNumber." = ";
	
	$_SESSION['captchaCode'] = $hashedCode;
	
	$width = 120;
	$height = 20;
	
	$image = ImageCreate($width, $height); 
	 
	$white = ImageColorAllocate($image, 255, 255, 255);
	$black = ImageColorAllocate($image, 0, 0, 0);
	
	ImageFill($image, 0, 0, $white); 
	ImageString($image, 5, 10, 3, $imageString, $black); 
	
	header("Content-Type: image/jpeg"); 
	ImageJpeg($image);
	ImageDestroy($image);
}
?>