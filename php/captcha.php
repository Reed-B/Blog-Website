<?php

  // Set some important CAPTCHA constants
  define('CAPTCHA_NUMCHARS', 6);  // number of characters in pass-phrase
  define('CAPTCHA_WIDTH', 300);   // width of image
  define('CAPTCHA_HEIGHT', 75);   // height of image

  // Generate the random pass-phrase
  $pass_phrase = "";
  for ($i = 0; $i < CAPTCHA_NUMCHARS; $i++) {
    $pass_phrase .= chr(rand(97, 122));
  }

  // Store the encrypted pass-phrase in a session variable
  $_SESSION['pass_phrase'] = $pass_phrase;

  // Create the image
  $img = imagecreatetruecolor(CAPTCHA_WIDTH, CAPTCHA_HEIGHT);

  // Set a white background with black text and gray graphics
  $bg_color = imagecolorallocate($img, 255, 255, 255);     // white
  $text_color = imagecolorallocate($img, 0, 0, 0);         // black
  $graphic_color = imagecolorallocate($img, 0, 0, 0);   // dark gray

  // Fill the background

  imagefilledrectangle($img, 0, 0, CAPTCHA_WIDTH, CAPTCHA_HEIGHT, $bg_color);

  // Draw some random lines
  for ($i = 0; $i < 20; $i++) {
    imageline($img, 0, rand() % CAPTCHA_HEIGHT, CAPTCHA_WIDTH, rand() % CAPTCHA_HEIGHT, $graphic_color);
  }

  // Sprinkle in some random dots
  for ($i = 0; $i < 500; $i++) {
    imagesetpixel($img, rand() % CAPTCHA_WIDTH, rand() % CAPTCHA_HEIGHT, $graphic_color);
  }

  // Draw the pass-phrase string

  imagettftext($img, 30, 0, 50, 60, $text_color, 'elvifrance.ttf', $pass_phrase);

  // Output the image as a PNG using a header
  header("Content-type: image/png");
  imagepng($img);

  // Clean up
  imagedestroy($img);
?>

