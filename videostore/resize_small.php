<?php
ini_set("memory_limit","128M");

// File and new size
$filename = 'images/TMT_DVD_105.jpg';

// Content type
header('Content-type: image/jpeg');

// Get new sizes
list($width, $height) = getimagesize($filename);
$newwidth = '43';
$newheight = '60';

// Load
$thumb = imagecreatetruecolor($newwidth, $newheight);
$source = imagecreatefromjpeg($filename);

// Resize
imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

// Output
imagejpeg($thumb);
?> 