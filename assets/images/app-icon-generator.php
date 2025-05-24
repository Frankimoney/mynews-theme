<?php
/**
 * App Icon Generator Script
 * Generates app icons for web app manifest and Apple touch icons
 * 
 * This script creates placeholder app icons in various sizes
 */

// Set default color to match theme primary color
$primary_color = '#0d6efd';

// Icon sizes to generate
$icon_sizes = [
    192, // Android standard
    512, // Android large
    180, // Apple touch icon
    144, // Windows tile
    96,  // Favicon large
    48,  // Favicon small
];

// Check for GD library
if (!extension_loaded('gd')) {
    die('GD library is required to generate icons');
}

// Generate icons
foreach ($icon_sizes as $size) {
    // Create a square image
    $image = imagecreatetruecolor($size, $size);
    
    // Enable alpha channel
    imagesavealpha($image, true);
    $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
    imagefill($image, 0, 0, $transparent);
    
    // Convert hex color to RGB
    list($r, $g, $b) = sscanf($primary_color, "#%02x%02x%02x");
    $color = imagecolorallocate($image, $r, $g, $b);
    
    // Draw a filled circle
    imagefilledellipse($image, $size/2, $size/2, $size*0.8, $size*0.8, $color);
    
    // Add "M" letter in the center
    $text_color = imagecolorallocate($image, 255, 255, 255);
    $font_size = $size / 3;
    $letter = "M";
    
    // Use default font since a TTF font might not be available
    $font_width = imagefontwidth(5);
    $font_height = imagefontheight(5);
    
    // Calculate position for center alignment
    $x = ($size / 2) - ($font_width * strlen($letter) / 2);
    $y = ($size / 2) - ($font_height / 2);
    
    imagestring($image, 5, $x, $y, $letter, $text_color);
    
    // Save the image
    $filename = __DIR__ . '/app-icon-' . $size . '.png';
    imagepng($image, $filename);
    imagedestroy($image);
    
    echo "Generated: $filename\n";
}

echo "All app icons have been generated successfully!\n";
