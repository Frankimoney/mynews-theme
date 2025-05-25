<?php
/**
 * Post Formats Admin Help
 *
 * Adds a help page for using post formats in the admin area
 *
 * @package My_News
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Post Formats Help page to admin menu
 */
function mynews_post_formats_admin_menu() {
    add_submenu_page(
        'themes.php',
        'Post Formats Guide',
        'Post Formats Guide',
        'edit_posts',
        'mynews-post-formats',
        'mynews_post_formats_admin_page'
    );
}
add_action('admin_menu', 'mynews_post_formats_admin_menu');

/**
 * Render the post formats admin help page
 */
function mynews_post_formats_admin_page() {
    ?>
    <div class="wrap">
        <h1>MyNews Post Formats Guide</h1>
        <div class="card">
            <h2>Using Post Formats</h2>
            <p>The MyNews theme supports special formatting for audio and video content. Use these post formats to showcase your multimedia content in an eye-catching and responsive way.</p>
        </div>
        
        <div class="card" style="margin-top: 20px;">
            <h2><span class="dashicons dashicons-format-audio"></span> Audio Post Format</h2>
            <p>The audio post format is perfect for podcasts, music, interviews, and any other audio content.</p>
            
            <h3>How to Create an Audio Post:</h3>
            <ol>
                <li>Go to <strong>Posts</strong> > <strong>Add New</strong></li>
                <li>On the right sidebar under <strong>Format</strong>, select <strong>Audio</strong></li>
                <li>Add your post title and content</li>
                <li>Include your audio content using one of these methods:
                    <ul>
                        <li>Paste an embed code from services like SoundCloud, Spotify, etc.</li>
                        <li>Upload an audio file using the Add Media button</li>
                        <li>Simply paste the URL to an MP3, OGG, WAV or M4A file in your content</li>
                        <li>Add a custom field named <code>audio_url</code> with the URL to your audio file</li>
                    </ul>
                </li>
                <li>Add a featured image for best appearance in archive pages</li>
                <li>Publish your post</li>
            </ol>
            <p><strong>Note:</strong> The theme will automatically display your audio with a responsive player in single posts, and with audio indicators in archive pages.</p>
        </div>
        
        <div class="card" style="margin-top: 20px;">
            <h2><span class="dashicons dashicons-format-video"></span> Video Post Format</h2>
            <p>The video post format is perfect for video blogs, tutorials, interviews, and any other video content.</p>
            
            <h3>How to Create a Video Post:</h3>
            <ol>
                <li>Go to <strong>Posts</strong> > <strong>Add New</strong></li>
                <li>On the right sidebar under <strong>Format</strong>, select <strong>Video</strong></li>
                <li>Add your post title and content</li>
                <li>Include your video content using one of these methods:
                    <ul>
                        <li>Simply paste the URL to a YouTube or Vimeo video in your content</li>
                        <li>Upload a video file using the Add Media button</li>
                        <li>Paste an embed code from a video service</li>
                        <li>Add a custom field named <code>video_url</code> with the URL to your video</li>
                    </ul>
                </li>
                <li>Add a featured image for best appearance in archive pages</li>
                <li>Publish your post</li>
            </ol>
            <p><strong>Note:</strong> The theme will automatically make your videos responsive and they will resize to fit any screen size.</p>
        </div>
        
        <div class="card" style="margin-top: 20px;">
            <h2>Test Post Formats</h2>
            <p>Want to see how post formats work in your theme? Click the button below to create test posts with audio and video formats.</p>
            <form method="post" action="">
                <?php wp_nonce_field('mynews_create_test_posts', 'mynews_test_posts_nonce'); ?>
                <input type="hidden" name="mynews_create_test_posts" value="1">
                <input type="submit" class="button button-primary" value="Create Test Posts">
            </form>
            
            <?php
            // Handle test post creation
            if (isset($_POST['mynews_create_test_posts']) && 
                check_admin_referer('mynews_create_test_posts', 'mynews_test_posts_nonce')) {
                
                // Include the test post creation function
                require_once get_template_directory() . '/inc/test-post-formats.php';
                echo mynews_create_post_format_test_posts();
            }
            ?>
        </div>
    </div>
    <?php
}
