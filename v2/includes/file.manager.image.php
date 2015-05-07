<?php
createImage();
exit();

function createImage() {
	$imageString = $_GET['type'];
	$length = strlen($imageString);
	$max = 6;
	$x = ($max - $length)*7;

	$image = ImageCreateFromJpeg("../images/file_manager_file.jpg");

	$black = ImageColorAllocate($image, 0, 0, 0);

	ImageString($image, 6, $x, 45, $imageString, $black);

	header("Content-Type: image/jpeg");
	ImageJpeg($image);
	ImageDestroy($image);
}
?>