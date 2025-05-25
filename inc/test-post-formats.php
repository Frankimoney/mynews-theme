<?php
/**
 * Template for testing post formats
 * 
 * This is a test file to demonstrate how post formats work
 * in the MyNews theme. It creates example posts with audio and video formats.
 *
 * Usage: Include this file from a template or run once to create test posts
 *
 * @package My_News
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Function to create test posts for post formats
function mynews_create_post_format_test_posts() {
    // Check if test posts already exist
    $existing_posts = get_posts(array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'meta_key' => '_mynews_test_post',
        'meta_value' => 'yes'
    ));

    if (!empty($existing_posts)) {
        return '<p>Test posts already exist. Delete posts with meta key <code>_mynews_test_post</code> if you want to recreate them.</p>';
    }

    // Create a test audio post
    $audio_post_id = wp_insert_post(array(
        'post_title' => 'Test Audio Post Format',
        'post_content' => 'This is a test audio post to demonstrate the audio post format in the MyNews theme.

<audio controls>
  <source src="https://example.com/audio-sample.mp3" type="audio/mpeg">
  Your browser does not support the audio element.
</audio>

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sed mauris nec lorem lacinia ullamcorper. 
Nulla facilisi. Donec euismod, velit sed consectetur fermentum, nunc neque dignissim libero, 
a pulvinar magna justo vel ipsum.

https://example.com/audio-sample.mp3
',
        'post_status' => 'publish',
        'post_type' => 'post',
        'post_author' => get_current_user_id(),
    ));

    if ($audio_post_id) {
        // Set post format
        set_post_format($audio_post_id, 'audio');
        
        // Mark as test post
        update_post_meta($audio_post_id, '_mynews_test_post', 'yes');
        
        // Set featured image if available
        $image_url = get_template_directory_uri() . '/assets/images/audio-sample-thumbnail.jpg';
        if (file_exists(get_template_directory() . '/assets/images/audio-sample-thumbnail.jpg')) {
            // Attachment doesn't exist, you'd need to create it
            // This is simplified for demonstration purposes
            // In a real scenario, you'd need to use media_sideload_image() or similar
        }
    }

    // Create a test video post
    $video_post_id = wp_insert_post(array(
        'post_title' => 'Test Video Post Format',
        'post_content' => 'This is a test video post to demonstrate the video post format in the MyNews theme.

https://www.youtube.com/watch?v=dQw4w9WgXcQ

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sed mauris nec lorem lacinia ullamcorper. 
Nulla facilisi. Donec euismod, velit sed consectetur fermentum, nunc neque dignissim libero, 
a pulvinar magna justo vel ipsum.
',
        'post_status' => 'publish',
        'post_type' => 'post',
        'post_author' => get_current_user_id(),
    ));

    if ($video_post_id) {
        // Set post format
        set_post_format($video_post_id, 'video');
        
        // Mark as test post
        update_post_meta($video_post_id, '_mynews_test_post', 'yes');
        
        // Set featured image if available
        $image_url = get_template_directory_uri() . '/assets/images/video-sample-thumbnail.jpg';
        if (file_exists(get_template_directory() . '/assets/images/video-sample-thumbnail.jpg')) {
            // Attachment doesn't exist, you'd need to create it
            // This is simplified for demonstration purposes
        }
    }

    return '<p>Test posts for audio and video formats have been created successfully!</p>';
}

// Uncomment the line below to run this file directly and create test posts
// echo mynews_create_post_format_test_posts();
