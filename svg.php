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
$width = strlen(strval($counter)) * $fontsize;
$height = $fontsize * 2;
$w2 = $width / 2;
$h2 = $height / 2;
if ($theme == 'light') {
  $bgColor = 'white';
  $fgColor = 'black';
} else {
  $bgColor = 'black';
  $fgColor = 'white';
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-type: image/svg+xml");
echo "
<svg viewBox='0 0 $width $height' width='$width' height='$height' xmlns='http://www.w3.org/2000/svg'>
  <style>
    svg {
      width: {$width}px;
      height: {$height}px;
    }
    text {
      font: {$fontsize}px sans-serif;
      font-weight: bold;
      fill: $fgColor;
      stroke: $bgColor;
      stroke-width: 2px;
      stroke-linejoin: round;
      paint-order: stroke;
      text-anchor: middle;
      alignment-baseline: central;
    }
  </style>
  <text x='$w2' y='$h2'>$counter</text>
</svg>
";
