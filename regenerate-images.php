<?php
/**
 * Image Size Regeneration Script
 * Run this script to regenerate thumbnails with new image sizes
 * Access via: yoursite.com/wp-content/themes/mynews/regenerate-images.php
 */

// Include WordPress
require_once(dirname(__FILE__) . '/../../../wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    die('You do not have permission to run this script.');
}

// Get the action
$action = isset($_GET['action']) ? $_GET['action'] : 'info';

?>
<!DOCTYPE html>
<html>
<head>
    <title>MyNews - Regenerate Image Sizes</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
        .button { display: inline-block; padding: 10px 20px; background: #0073aa; color: white; text-decoration: none; border-radius: 3px; margin: 10px 0; }
        .success { color: green; padding: 10px; background: #f0fff0; border: 1px solid green; border-radius: 3px; margin: 10px 0; }
        .info { color: #0073aa; padding: 10px; background: #f0f8ff; border: 1px solid #0073aa; border-radius: 3px; margin: 10px 0; }
        .warning { color: #856404; padding: 10px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 3px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>MyNews Theme - Image Size Regeneration</h1>
    
    <?php if ($action === 'info'): ?>
        <div class="info">
            <h3>Updated Image Sizes:</h3>
            <ul>
                <li><strong>mynews-card:</strong> 400x250px (reduced from 600x400px)</li>
                <li><strong>mynews-card-mobile:</strong> 350x200px (new mobile size)</li>
                <li><strong>mynews-featured-large:</strong> 1200x628px (unchanged)</li>
                <li><strong>mynews-featured-medium:</strong> 800x500px (unchanged)</li>
                <li><strong>mynews-thumbnail:</strong> 300x300px (unchanged)</li>
            </ul>
            
            <h3>Improvements Made:</h3>
            <ul>
                <li>Reduced post card image height from 220px to 180px</li>
                <li>Added mobile-specific image heights (140px-160px)</li>
                <li>Improved aspect ratios to prevent elongated images</li>
                <li>Added responsive design for tablets (160px-200px)</li>
                <li>Enhanced card styling and hover effects</li>
            </ul>
        </div>
        
        <div class="warning">
            <strong>Note:</strong> To apply the new image sizes to existing images, you can regenerate thumbnails. 
            This process may take some time depending on the number of images.
        </div>
        
        <a href="?action=regenerate" class="button">Regenerate All Image Thumbnails</a>
        <a href="<?php echo admin_url(); ?>" class="button">Back to Admin</a>
        
    <?php elseif ($action === 'regenerate'): ?>
        <?php
        set_time_limit(300); // 5 minutes
        
        echo '<div class="info">Starting image regeneration process...</div>';
        
        // Get all image attachments
        $attachments = get_posts(array(
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'posts_per_page' => -1,
            'fields' => 'ids'
        ));
        
        $total = count($attachments);
        $processed = 0;
        $errors = 0;
        
        echo "<div class='info'>Found {$total} images to process...</div>";
        
        foreach ($attachments as $attachment_id) {
            $file = get_attached_file($attachment_id);
            if ($file && file_exists($file)) {
                $metadata = wp_generate_attachment_metadata($attachment_id, $file);
                if ($metadata) {
                    wp_update_attachment_metadata($attachment_id, $metadata);
                    $processed++;
                } else {
                    $errors++;
                }
            } else {
                $errors++;
            }
            
            // Show progress every 10 images
            if ($processed % 10 == 0) {
                echo "<div class='info'>Processed {$processed} of {$total} images...</div>";
                flush();
            }
        }
        
        echo "<div class='success'>
            <h3>Regeneration Complete!</h3>
            <p><strong>Total images:</strong> {$total}</p>
            <p><strong>Successfully processed:</strong> {$processed}</p>
            <p><strong>Errors:</strong> {$errors}</p>
        </div>";
        
        if ($errors > 0) {
            echo "<div class='warning'>Some images could not be processed. This is usually due to missing files or permission issues.</div>";
        }
        ?>
        
        <a href="?action=info" class="button">Back to Info</a>
        <a href="<?php echo home_url(); ?>" class="button">View Site</a>
        
    <?php endif; ?>
    
    <hr>
    <p><small>MyNews Theme - Image Optimization Tool</small></p>
</body>
</html>
