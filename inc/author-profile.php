<?php
/**
 * Enhanced Author Profile fields for MyNews theme
 * 
 * @package My_News
 */

if ( ! function_exists( 'mynews_author_profile_fields' ) ) :
    /**
     * Add custom author profile fields
     *
     * @param array $contactmethods Existing contact methods.
     * @return array Modified contact methods.
     */
    function mynews_author_profile_fields( $contactmethods ) {
        // Add social media profile fields
        $contactmethods['twitter_profile'] = __( 'Twitter/X Profile URL', 'mynews' );
        $contactmethods['facebook_profile'] = __( 'Facebook Profile URL', 'mynews' );
        $contactmethods['instagram_profile'] = __( 'Instagram Profile URL', 'mynews' );
        $contactmethods['linkedin_profile'] = __( 'LinkedIn Profile URL', 'mynews' );
        $contactmethods['youtube_profile'] = __( 'YouTube Channel URL', 'mynews' );
        $contactmethods['github_profile'] = __( 'GitHub Profile URL', 'mynews' );
        
        return $contactmethods;
    }
endif;
add_filter( 'user_contactmethods', 'mynews_author_profile_fields' );

if ( ! function_exists( 'mynews_author_additional_fields' ) ) :
    /**
     * Add custom author profile fields to user profile page
     */
    function mynews_author_additional_fields( $user ) {
        // Ensure the WordPress media uploader scripts are enqueued
        wp_enqueue_media();
        ?>
        <h3><?php _e( 'Additional Profile Information', 'mynews' ); ?></h3>
        <table class="form-table">
            <tr>
                <th><label for="author_featured_image"><?php _e( 'Featured Image', 'mynews' ); ?></label></th>
                <td>
                    <?php 
                    $author_featured_image = get_user_meta( $user->ID, 'author_featured_image', true );
                    ?>
                    <input type="text" name="author_featured_image" id="author_featured_image" 
                           value="<?php echo esc_attr( $author_featured_image ); ?>" class="regular-text" />
                    <input type="button" class="button button-secondary" value="<?php _e( 'Select Image', 'mynews' ); ?>" id="upload_author_image_button" />
                    <p class="description">
                        <?php _e( 'This image will be used as your author profile image instead of Gravatar.', 'mynews' ); ?>
                    </p>
                    
                    <div class="author-image-preview">
                        <?php if ( !empty( $author_featured_image ) ) : ?>
                            <img src="<?php echo esc_url( $author_featured_image ); ?>" style="max-width: 150px; height: auto; margin-top: 10px;" />
                        <?php endif; ?>
                    </div>
                    
                    <script type="text/javascript">
                    jQuery(document).ready(function($) {
                        $('#upload_author_image_button').click(function(e) {
                            e.preventDefault();
                            
                            var image_frame;
                            
                            if ( image_frame ) {
                                image_frame.open();
                                return;
                            }
                            
                            // Define image_frame as wp.media object
                            image_frame = wp.media({
                                title: '<?php _e( 'Select Media', 'mynews' ); ?>',
                                multiple: false,
                                library: {
                                    type: 'image'
                                }
                            });
                            
                            image_frame.on('select', function() {
                                // On select, get the selected attachment
                                var attachment = image_frame.state().get('selection').first().toJSON();
                                $('#author_featured_image').val(attachment.url);
                                
                                // Update preview
                                $('.author-image-preview').html('<img src="' + attachment.url + '" style="max-width: 150px; height: auto; margin-top: 10px;" />');
                            });
                            
                            image_frame.open();
                        });
                    });
                    </script>
                </td>
            </tr>
        </table>
        <?php
    }
endif;
add_action( 'show_user_profile', 'mynews_author_additional_fields' );
add_action( 'edit_user_profile', 'mynews_author_additional_fields' );

if ( ! function_exists( 'mynews_save_author_additional_fields' ) ) :
    /**
     * Save custom author profile fields
     */
    function mynews_save_author_additional_fields( $user_id ) {
        if ( !current_user_can( 'edit_user', $user_id ) ) {
            return false;
        }
        
        if ( isset( $_POST['author_featured_image'] ) ) {
            update_user_meta( $user_id, 'author_featured_image', esc_url_raw( $_POST['author_featured_image'] ) );
        }
    }
endif;
add_action( 'personal_options_update', 'mynews_save_author_additional_fields' );
add_action( 'edit_user_profile_update', 'mynews_save_author_additional_fields' );
