<?php
/**
 * Mobile search modal template
 *
 * @package My_News
 */
?>
<!-- Mobile Search Modal -->
<div class="modal fade" id="search-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php esc_html_e( 'Search', 'mynews' ); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e( 'Close', 'mynews' ); ?>"></button>
            </div>
            <div class="modal-body">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="input-group">
                        <input type="search" class="form-control" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'mynews' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                            <span class="visually-hidden"><?php esc_html_e( 'Search', 'mynews' ); ?></span>
                        </button>
                    </div>
                </form>
                
                <?php if ( get_theme_mod( 'mynews_show_recent_searches', true ) ) : ?>
                    <div class="recent-searches mt-4">
                        <h6><?php esc_html_e( 'Recent Searches', 'mynews' ); ?></h6>
                        <div class="recent-searches-list">
                            <?php 
                            // Get recent searches from cookie or default to popular searches
                            $recent_searches = isset( $_COOKIE['mynews_recent_searches'] ) ? json_decode( stripslashes( $_COOKIE['mynews_recent_searches'] ), true ) : array();
                            
                            if ( empty( $recent_searches ) ) {
                                $popular_terms = array(
                                    __( 'News', 'mynews' ),
                                    __( 'Technology', 'mynews' ),
                                    __( 'Business', 'mynews' ),
                                    __( 'Sports', 'mynews' ),
                                    __( 'Entertainment', 'mynews' )
                                );
                                
                                foreach ( $popular_terms as $term ) {
                                    echo '<a href="' . esc_url( home_url( '/?s=' . urlencode( $term ) ) ) . '" class="badge bg-light text-dark me-2 mb-2">' . esc_html( $term ) . '</a>';
                                }
                            } else {
                                foreach ( $recent_searches as $term ) {
                                    echo '<a href="' . esc_url( home_url( '/?s=' . urlencode( $term ) ) ) . '" class="badge bg-light text-dark me-2 mb-2">' . esc_html( $term ) . '</a>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ( get_theme_mod( 'mynews_show_popular_topics', true ) ) : ?>
                    <div class="popular-topics mt-4">
                        <h6><?php esc_html_e( 'Popular Topics', 'mynews' ); ?></h6>
                        <div class="popular-topics-list">
                            <?php
                            // Get popular categories
                            $popular_categories = get_categories( array(
                                'orderby'    => 'count',
                                'order'      => 'DESC',
                                'number'     => 10,
                                'hide_empty' => true
                            ) );
                            
                            foreach ( $popular_categories as $category ) {
                                echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="badge bg-primary me-2 mb-2">' . esc_html( $category->name ) . '</a>';
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
