<?php
/**
 * Social Share Buttons for Single Posts
 *
 * @package My_News
 */
if ( ! is_singular( 'post' ) ) {
    return;
}

$post_url   = urlencode( get_permalink() );
$post_title = urlencode( get_the_title() );
$site_title = urlencode( get_bloginfo( 'name' ) );
?>
<div class="mynews-share-buttons">
    <span class="mynews-share-label">Share:</span>
    <a class="mynews-share-btn facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>" target="_blank" rel="noopener" title="Share on Facebook">
        <i class="fab fa-facebook-f"></i> Facebook
    </a>
    <a class="mynews-share-btn twitter" href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>" target="_blank" rel="noopener" title="Share on Twitter">
        <i class="fab fa-twitter"></i> Twitter
    </a>
    <a class="mynews-share-btn linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $post_url; ?>&title=<?php echo $post_title; ?>&source=<?php echo $site_title; ?>" target="_blank" rel="noopener" title="Share on LinkedIn">
        <i class="fab fa-linkedin-in"></i> LinkedIn
    </a>
    <a class="mynews-share-btn whatsapp" href="https://wa.me/?text=<?php echo $post_title . '%20' . $post_url; ?>" target="_blank" rel="noopener" title="Share on WhatsApp">
        <i class="fab fa-whatsapp"></i> WhatsApp
    </a>
</div>
