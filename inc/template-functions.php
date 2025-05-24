<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package My_News
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function mynews_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}
	
	// Add layout class
	$theme_layout = get_theme_mod('mynews_theme_layout', 'full-width');
	$classes[] = $theme_layout . '-layout';
	return $classes;
}
add_filter( 'body_class', 'mynews_body_classes' );

/**
 * Determine if post is featured
 * 
 * @param int|WP_Post $post Post ID or post object.
 * @return bool Whether the post is featured
 */
function mynews_is_post_featured( $post = null ) {
    $post = get_post( $post );
    if ( ! $post ) {
        return false;
    }

    // Option 1: Using sticky posts
    if ( is_sticky( $post->ID ) ) {
        return true;
    }
    
    // Option 2: Using post meta (if you've set up a custom field for featured posts)
    $featured = get_post_meta( $post->ID, 'mynews_featured_post', true );
    if ( $featured ) {
        return true;
    }
    
    // Option 3: Using a specific category
    if ( has_category( 'featured', $post ) ) {
        return true;
    }
    
    return false;
}

/**
 * Check if a page has child pages
 *
 * @param int|WP_Post $page Page ID or page object.
 * @return bool Whether the page has children.
 */
function mynews_has_children( $page = null ) {
    $page = get_post( $page );
    if ( ! $page || $page->post_type !== 'page' ) {
        return false;
    }
    
    $children = get_pages( array(
        'child_of' => $page->ID,
        'post_type' => 'page',
    ) );
    
    return ( count( $children ) > 0 ) ? true : false;
}

/**
 * Generate schema.org structured data JSON-LD for articles
 *
 * @param int|WP_Post $post Post ID or post object.
 * @param string $type The type of article schema to use (Article, BlogPosting, NewsArticle).
 * @return string JSON-LD script for schema.org Article markup
 */
function mynews_generate_article_schema($post = null, $type = 'Article') {
    $post = get_post($post);
    if (!$post || $post->post_type !== 'post') {
        return '';
    }

    // Validate schema type - default to Article if invalid type is passed
    $valid_types = array('Article', 'BlogPosting', 'NewsArticle');
    if (!in_array($type, $valid_types)) {
        $type = 'Article';
    }
    
    // Determine the best schema type based on category if not specified
    if ($type === 'Article') {
        // Check if post is in news category - use NewsArticle
        if (has_category('news', $post) || has_category('headlines', $post)) {
            $type = 'NewsArticle';
        } 
        // Otherwise use BlogPosting for standard posts
        else {
            $type = 'BlogPosting';
        }
    }

    // Basic post data
    $post_url = get_permalink($post);
    $post_title = get_the_title($post);
    $post_content = wp_strip_all_tags(get_the_content('', false, $post));
    $post_excerpt = has_excerpt($post) ? wp_strip_all_tags(get_the_excerpt($post)) : wp_trim_words($post_content, 55, '...');
    $word_count = str_word_count($post_content);
    
    // Dates
    $date_published = get_the_date('c', $post);
    $date_modified = get_the_modified_date('c', $post);
    
    // Author info
    $author_id = $post->post_author;
    $author_name = get_the_author_meta('display_name', $author_id);
    $author_url = get_author_posts_url($author_id);
    $author_description = get_the_author_meta('description', $author_id);
    $author_avatar = get_avatar_url($author_id, array('size' => 96));
    
    // Featured image
    $image_url = '';
    $image_width = 1200;
    $image_height = 628;
    
    if (has_post_thumbnail($post)) {
        $image_id = get_post_thumbnail_id($post);
        $image_data = wp_get_attachment_image_src($image_id, 'full');
        
        if ($image_data) {
            $image_url = $image_data[0];
            $image_width = $image_data[1];
            $image_height = $image_data[2];
        }
    }
    
    // Publisher info (website/organization info)
    $site_name = get_bloginfo('name');
    $site_url = home_url('/');
    $site_description = get_bloginfo('description');
    $site_logo_url = '';
    
    // Try to get site logo
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $logo_data = wp_get_attachment_image_src($custom_logo_id, 'full');
        if ($logo_data) {
            $site_logo_url = $logo_data[0];
        }
    }
    
    // Categories and keywords
    $categories = get_the_category($post->ID);
    $category_names = array();
    
    if (!empty($categories)) {
        foreach ($categories as $category) {
            $category_names[] = $category->name;
        }
    }
    
    $tags = get_the_tags($post->ID);
    $tag_names = array();
    
    if (!empty($tags)) {
        foreach ($tags as $tag) {
            $tag_names[] = $tag->name;
        }
    }
    
    $keywords = array_merge($category_names, $tag_names);
    
    // Build the schema array
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => $type,
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id' => $post_url
        ),
        'headline' => $post_title,
        'description' => $post_excerpt,
        'datePublished' => $date_published,
        'dateModified' => $date_modified,
        'author' => array(
            '@type' => 'Person',
            'name' => $author_name,
            'url' => $author_url
        )
    );
    
    // Add comment count if available
    $comment_count = get_comment_count($post->ID);
    if (!empty($comment_count['approved'])) {
        $schema['commentCount'] = $comment_count['approved'];
    }
    
    // Add article body for improved schema completeness
    $schema['articleBody'] = wp_trim_words($post_content, 150, '...');
    
    // Add word count for blog posts
    if ($type === 'BlogPosting') {
        $schema['wordCount'] = $word_count;
    }
    
    // Add author additional info if available
    if (!empty($author_description)) {
        $schema['author']['description'] = $author_description;
    }
    
    if (!empty($author_avatar)) {
        $schema['author']['image'] = $author_avatar;
    }
    
    // Add image if available
    if (!empty($image_url)) {
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $image_url,
            'width' => $image_width,
            'height' => $image_height
        );
    }
    
    // Add publisher info
    $schema['publisher'] = array(
        '@type' => 'Organization',
        'name' => $site_name,
        'url' => $site_url,
        'description' => $site_description
    );
      // Add publisher logo if available
    if (!empty($site_logo_url)) {
        $schema['publisher']['logo'] = array(
            '@type' => 'ImageObject',
            'url' => $site_logo_url,
            'width' => 600,
            'height' => 60
        );
    }
    
    // Add keywords if available
    if (!empty($keywords)) {
        $schema['keywords'] = implode(', ', $keywords);
    }
    
    // Add article section from primary category
    if (!empty($categories)) {
        $schema['articleSection'] = $categories[0]->name;
    }
    
    // Add breadcrumb trail for improved SEO
    $schema['breadcrumb'] = array(
        '@type' => 'BreadcrumbList',
        'itemListElement' => array(
            array(
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => home_url('/')
            )
        )
    );
    
    // Add category to breadcrumb if available
    if (!empty($categories)) {
        $schema['breadcrumb']['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => 2,
            'name' => $categories[0]->name,
            'item' => get_category_link($categories[0]->term_id)
        );
        
        $schema['breadcrumb']['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $post_title,
            'item' => $post_url
        );
    } else {
        $schema['breadcrumb']['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => 2,
            'name' => $post_title,
            'item' => $post_url
        );
    }
    
    // Generate the JSON-LD script
    $json = wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    
    return '<script type="application/ld+json">' . $json . '</script>';
}

/**
 * Generate schema.org structured data JSON-LD for pages
 *
 * @param int|WP_Post $post Page ID or page object.
 * @return string JSON-LD script for schema.org WebPage markup
 */
function mynews_generate_page_schema($post = null) {
    $post = get_post($post);
    if (!$post || $post->post_type !== 'page') {
        return '';
    }

    // Basic page data
    $page_url = get_permalink($post);
    $page_title = get_the_title($post);
    $page_content = wp_strip_all_tags(get_the_content('', false, $post));
    $page_excerpt = has_excerpt($post) ? wp_strip_all_tags(get_the_excerpt($post)) : wp_trim_words($page_content, 55, '...');
    
    // Dates
    $date_published = get_the_date('c', $post);
    $date_modified = get_the_modified_date('c', $post);
    
    // Featured image
    $image_url = '';
    $image_width = 1200;
    $image_height = 628;
    
    if (has_post_thumbnail($post)) {
        $image_id = get_post_thumbnail_id($post);
        $image_data = wp_get_attachment_image_src($image_id, 'full');
        
        if ($image_data) {
            $image_url = $image_data[0];
            $image_width = $image_data[1];
            $image_height = $image_data[2];
        }
    }
    
    // Website info
    $site_name = get_bloginfo('name');
    $site_url = home_url('/');
    
    // Determine the WebPage type based on template or page name
    $webpage_type = 'WebPage';
    
    // Check for specific page templates or names to set correct schema type
    if (is_front_page()) {
        $webpage_type = 'WebPage';
    } elseif (is_page('about') || is_page('about-us')) {
        $webpage_type = 'AboutPage';
    } elseif (is_page('contact') || is_page('contact-us')) {
        $webpage_type = 'ContactPage';
    } elseif (is_page('faq')) {
        $webpage_type = 'FAQPage';
    } elseif (basename(get_page_template()) === 'full-width.php') {
        $webpage_type = 'WebPage';
    }
    
    // Build the schema array
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => $webpage_type,
        '@id' => $page_url . '#webpage',
        'url' => $page_url,
        'name' => $page_title,
        'isPartOf' => array(
            '@type' => 'WebSite',
            '@id' => $site_url . '#website',
            'name' => $site_name,
            'url' => $site_url
        ),
        'datePublished' => $date_published,
        'dateModified' => $date_modified,
        'description' => $page_excerpt
    );
    
    // Add image if available
    if (!empty($image_url)) {
        $schema['primaryImageOfPage'] = array(
            '@type' => 'ImageObject',
            '@id' => $page_url . '#primaryimage',
            'url' => $image_url,
            'width' => $image_width,
            'height' => $image_height
        );
    }
    
    // Add breadcrumb trail
    $schema['breadcrumb'] = array(
        '@type' => 'BreadcrumbList',
        'itemListElement' => array(
            array(
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => home_url('/')
            )
        )
    );
    
    // Add parent page to breadcrumb if available
    $parent_id = wp_get_post_parent_id($post->ID);
    if ($parent_id) {
        $parent_title = get_the_title($parent_id);
        $parent_url = get_permalink($parent_id);
        
        $schema['breadcrumb']['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => 2,
            'name' => $parent_title,
            'item' => $parent_url
        );
        
        $schema['breadcrumb']['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $page_title,
            'item' => $page_url
        );
    } else {
        $schema['breadcrumb']['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => 2,
            'name' => $page_title,
            'item' => $page_url
        );
    }
    
    // Generate the JSON-LD script
    $json = wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    
    return '<script type="application/ld+json">' . $json . '</script>';
}

/**
 * Display child pages navigation for hierarchical pages
 *
 * @param int|WP_Post $page Page ID or page object.
 * @return string HTML output of child pages navigation.
 */
function mynews_child_pages_nav( $page = null ) {
    $page = get_post( $page );
    if ( ! $page || $page->post_type !== 'page' ) {
        return '';
    }
    
    // Get parent page if this is a child page
    $parent_id = wp_get_post_parent_id( $page->ID );
    $top_level_parent = $parent_id ? $parent_id : $page->ID;
    
    $child_pages = get_pages( array(
        'child_of' => $top_level_parent,
        'parent' => $top_level_parent,
        'sort_column' => 'menu_order,post_title',
    ) );
    
    if ( empty( $child_pages ) ) {
        return '';
    }
    
    $output = '<div class="card page-children-nav mb-4 shadow-sm">';
    $output .= '<div class="card-header bg-light">';
    $output .= '<h3 class="h5 mb-0">' . esc_html__( 'Related Pages', 'mynews' ) . '</h3>';
    $output .= '</div>';
    $output .= '<ul class="list-group list-group-flush">';
    
    foreach ( $child_pages as $child ) {
        $active = ( $page->ID == $child->ID ) ? ' active fw-bold' : '';
        $output .= '<li class="list-group-item' . $active . '">';
        $output .= '<a href="' . get_permalink( $child->ID ) . '" class="text-decoration-none d-block">';
        
        if ( has_post_thumbnail( $child->ID ) ) {
            $output .= '<div class="row align-items-center">';
            $output .= '<div class="col-3">';
            $output .= get_the_post_thumbnail( $child->ID, 'thumbnail', array( 'class' => 'img-fluid rounded' ) );
            $output .= '</div>';
            $output .= '<div class="col-9">';
            $output .= esc_html( $child->post_title );
            $output .= '</div>';
            $output .= '</div>';
        } else {
            $output .= '<i class="bi bi-file-earmark-text me-2"></i>' . esc_html( $child->post_title );
        }
        
        $output .= '</a>';
        $output .= '</li>';
    }
    
    $output .= '</ul>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function mynews_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'mynews_pingback_header' );

/**
 * Change excerpt length
 */
function mynews_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'mynews_excerpt_length' );

/**
 * Change excerpt more string
 */
function mynews_excerpt_more( $more ) {
    return '&hellip; <a href="' . esc_url( get_permalink() ) . '">' . __( 'Read More', 'mynews' ) . '</a>';
}
add_filter( 'excerpt_more', 'mynews_excerpt_more' );

/**
 * Generate schema.org structured data JSON-LD for the website
 *
 * @return string JSON-LD script for schema.org WebSite markup
 */
function mynews_generate_website_schema() {
    // Website data
    $site_name = get_bloginfo('name');
    $site_url = home_url('/');
    $site_description = get_bloginfo('description');
    
    // Build the schema array
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        '@id' => $site_url . '#website',
        'url' => $site_url,
        'name' => $site_name,
        'description' => $site_description,
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => array(
                '@type' => 'EntryPoint',
                'urlTemplate' => $site_url . '?s={search_term_string}'
            ),
            'query-input' => 'required name=search_term_string'
        )
    );
    
    // Generate the JSON-LD script
    $json = wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    
    return '<script type="application/ld+json">' . $json . '</script>';
}

/**
 * Generate schema.org structured data JSON-LD for the organization
 *
 * @return string JSON-LD script for schema.org Organization markup
 */
function mynews_generate_organization_schema() {
    // Organization data
    $site_name = get_bloginfo('name');
    $site_url = home_url('/');
    $site_description = get_bloginfo('description');
    $site_logo_url = '';
    
    // Try to get site logo
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $logo_data = wp_get_attachment_image_src($custom_logo_id, 'full');
        if ($logo_data) {
            $site_logo_url = $logo_data[0];
        }
    }
    
    // Build the schema array
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        '@id' => $site_url . '#organization',
        'url' => $site_url,
        'name' => $site_name,
        'description' => $site_description
    );
    
    // Add logo if available
    if (!empty($site_logo_url)) {
        $schema['logo'] = array(
            '@type' => 'ImageObject',
            'url' => $site_logo_url,
            'width' => 600,
            'height' => 60
        );
        $schema['image'] = $site_logo_url;
    }
    
    // Try to get social profiles from customizer
    $social_profiles = array();
    $potential_social = array(
        'facebook' => 'https://www.facebook.com/',
        'twitter' => 'https://twitter.com/',
        'instagram' => 'https://www.instagram.com/',
        'linkedin' => 'https://www.linkedin.com/company/',
        'youtube' => 'https://www.youtube.com/',
        'pinterest' => 'https://www.pinterest.com/'
    );
    
    foreach ($potential_social as $network => $base_url) {
        $handle = get_theme_mod('mynews_' . $network . '_handle', '');
        if (!empty($handle)) {
            $social_profiles[] = $base_url . $handle;
        }
    }
    
    // Add social profiles if available
    if (!empty($social_profiles)) {
        $schema['sameAs'] = $social_profiles;
    }
    
    // Generate the JSON-LD script
    $json = wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    
    return '<script type="application/ld+json">' . $json . '</script>';
}

/**
 * Generate schema.org structured data JSON-LD for FAQ pages
 * This function looks for h3 and following p elements in a page's content
 * to build FAQ schema markup.
 *
 * @param int|WP_Post $post Page ID or page object.
 * @return string JSON-LD script for schema.org FAQPage markup
 */
function mynews_generate_faq_schema($post = null) {
    $post = get_post($post);
    if (!$post) {
        return '';
    }
    
    // Check if this is a FAQ page by title or slug
    $is_faq_page = false;
    if (stripos($post->post_title, 'faq') !== false || 
        stripos($post->post_title, 'frequently asked') !== false || 
        stripos($post->post_name, 'faq') !== false) {
        $is_faq_page = true;
    }
    
    if (!$is_faq_page) {
        return '';
    }
    
    // Get the post content
    $content = $post->post_content;
    
    // Use regular expressions to identify question-answer pairs 
    // This is a simpler approach that avoids using DOM parsing
    $faq_items = array();
    
    // Look for heading elements followed by paragraphs using regex
    $pattern = '/<h[2-4][^>]*>(.*?)<\/h[2-4]>\s*<p[^>]*>(.*?)<\/p>/is';
    preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
    
    foreach ($matches as $match) {
        $question_text = trim(strip_tags($match[1]));
        $answer_text = trim(strip_tags($match[2]));
        
        // Only add if both question and answer have content
        if (!empty($question_text) && !empty($answer_text)) {
            $faq_items[] = array(
                '@type' => 'Question',
                'name' => $question_text,
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text' => $answer_text
                )
            );
        }
    }
    
    // If we found FAQ items, create the schema
    if (!empty($faq_items)) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faq_items
        );
        
        // Generate the JSON-LD script
        $json = wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        
        return '<script type="application/ld+json">' . $json . '</script>';
    }
    
    return '';
}

/**
 * Add schema testing links in admin bar
 */
function mynews_schema_testing_links($wp_admin_bar) {
    if (!current_user_can('manage_options') || !is_singular()) {
        return;
    }
    
    $current_url = urlencode(get_permalink());
    
    $wp_admin_bar->add_node(array(
        'id' => 'schema-testing',
        'title' => __('Test Schema', 'mynews'),
        'parent' => 'top-secondary',
    ));
    
    $wp_admin_bar->add_node(array(
        'id' => 'schema-testing-google',
        'title' => __('Google Rich Results', 'mynews'),
        'href' => 'https://search.google.com/test/rich-results?url=' . $current_url,
        'parent' => 'schema-testing',
        'meta' => array(
            'target' => '_blank'
        )
    ));
    
    $wp_admin_bar->add_node(array(
        'id' => 'schema-testing-validator',
        'title' => __('Schema Validator', 'mynews'),
        'href' => 'https://validator.schema.org/#url=' . $current_url,
        'parent' => 'schema-testing',
        'meta' => array(
            'target' => '_blank'
        )
    ));
}
add_action('admin_bar_menu', 'mynews_schema_testing_links', 99);

/**
 * Display top reactions for a post
 *
 * @param int $post_id Post ID
 * @param int $limit Number of top reactions to show (default: 3)
 * @return string HTML output of top reactions
 */
function mynews_display_top_reactions($post_id = null, $limit = 3) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    if (!function_exists('mynews_get_post_reactions')) {
        return '';
    }
    
    $reactions = mynews_get_post_reactions($post_id);
    if (empty($reactions)) {
        return '';
    }
    
    // Sort reactions by count (descending)
    arsort($reactions);
    
    // Get total reaction count
    $total_count = 0;
    foreach ($reactions as $count) {
        $total_count += intval($count);
    }
    
    // If no reactions, return empty
    if ($total_count === 0) {
        return '';
    }
    
    // Get reaction emoji mapping
    $emojis = array(
        'like' => '👍',
        'love' => '❤️',
        'haha' => '😂',
        'wow' => '😮',
        'sad' => '😢'
    );
    
    // Limit to requested number of reactions
    $top_reactions = array_slice($reactions, 0, $limit, true);
    
    // Build output
    $output = '<div class="mynews-top-reactions">';
    $output .= '<span class="top-reactions-label">' . __('Top Reactions:', 'mynews') . '</span>';
    $output .= '<span class="top-reactions-icons">';
    
    foreach ($top_reactions as $type => $count) {
        if ($count > 0) {
            $emoji = isset($emojis[$type]) ? $emojis[$type] : '';
            $output .= '<span class="top-reaction" title="' . $count . ' ' . ucfirst($type) . '">';
            $output .= $emoji . ' <span class="count">' . $count . '</span>';
            $output .= '</span>';
        }
    }
    
    $output .= '</span>'; // .top-reactions-icons
    $output .= '</div>'; // .mynews-top-reactions
    
    return $output;
}

/**
 * Display post views count with icon
 * 
 * @param int|null $post_id Post ID or null to use current post
 * @param bool $echo Whether to echo or return the output
 * @return string|void HTML output if $echo is false
 */
function mynews_display_post_views($post_id = null, $echo = true) {
    if (!function_exists('mynews_get_post_views')) {
        return '';
    }
    
    if (null === $post_id) {
        $post_id = get_the_ID();
    }
    
    $view_count = mynews_get_post_views($post_id);
    $output = '<span class="post-views"><i class="bi bi-eye"></i> ' . 
        sprintf(
            _n('%s view', '%s views', $view_count, 'mynews'),
            number_format($view_count)
        ) . 
    '</span>';
    
    if ($echo) {
        echo $output;
    } else {
        return $output;
    }
}
