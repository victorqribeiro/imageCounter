<?php
$theme = isset($_GET['theme']) ? $_GET['theme'] : 'light';
if ($theme !== 'light' && $theme !== 'dark') {
  $theme = 'light';
}
$file = "counter.txt";
if (!file_exists($file)) {
  touch($file);
}
$handle = fopen($file, "r+");
if (flock($handle, LOCK_EX)) {
  $counter = fread($handle, filesize($file)) ?? 0;
  $counter = intval($counter) + 1;
  ftruncate($handle, 0);
  rewind($handle);
  fwrite($handle, $counter);
  fflush($handle);
  flock($handle, LOCK_UN);
} else {
  die('Could not open file');
}
fclose($handle);
$fontsize = 24;
$width = strlen(strval($counter)) * $fontsize * 0.8;
$height = $fontsize * 2;
$image = imagecreate($width, $height);
$bgValue = $theme === 'light' ? 255 : 0;
$bg = imagecolorallocatealpha($image, $bgValue, $bgValue, $bgValue, 127);
$colorValue = 255 - $bgValue;
$black = imagecolorallocate($image, $colorValue, $colorValue, $colorValue);
$font = "/var/www/html/imageCounter/arial.ttf";
$posx = $width / $fontsize;
$posy = $height / 2 + $fontsize / 2;
$angle = 0;
imagettftext($image, $fontsize, $angle, $posx, $posy, $black, $font, $counter);
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: image/png');
imagepng($image);
?>
