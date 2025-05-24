/**
 * Screenshot placeholder generation script
 * This script creates a placeholder image for the theme.
 * 
 * In real-world usage, you would replace this with an actual screenshot image
 * that showcases your theme's design.
 */

// Basic theme info to display on the screenshot
$theme_name = 'My News';
$theme_description = 'A modern Bootstrap 5 WordPress theme for news websites and blogs';

// Create a 1200x900 image (WordPress recommended size)
$image = imagecreatetruecolor(1200, 900);

// Colors
$bg_color = imagecolorallocate($image, 33, 37, 41); // Dark background (#212529)
$text_color = imagecolorallocate($image, 255, 255, 255); // White text
$accent_color = imagecolorallocate($image, 13, 110, 253); // Primary blue (#0d6efd)
$light_color = imagecolorallocate($image, 240, 240, 240); // Light gray for text

// Fill background
imagefill($image, 0, 0, $bg_color);

// Draw header bar
imagefilledrectangle($image, 0, 0, 1200, 80, $accent_color);

// Add theme name text
$font = 5; // Built-in font
imagestring($image, $font, 50, 30, $theme_name, $text_color);

// Add mockup content areas
// Main content area
imagefilledrectangle($image, 50, 120, 800, 800, $light_color);

// Sidebar area
imagefilledrectangle($image, 850, 120, 1150, 600, $light_color);

// Footer widgets
imagefilledrectangle($image, 50, 830, 1150, 880, $accent_color);

// Add theme description
imagestring($image, 3, 60, 140, $theme_description, imagecolorallocate($image, 33, 37, 41));

// Add some mockup text blocks in the content area
for ($i = 1; $i <= 5; $i++) {
    imagefilledrectangle($image, 70, 180 + ($i * 70), 780, 210 + ($i * 70), imagecolorallocate($image, 200, 200, 200));
}

// Add some mockup text blocks in the sidebar
for ($i = 1; $i <= 4; $i++) {
    imagefilledrectangle($image, 870, 140 + ($i * 80), 1130, 180 + ($i * 80), imagecolorallocate($image, 200, 200, 200));
}

// Output the image
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
