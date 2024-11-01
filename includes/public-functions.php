<?php 
/**
 * Display the post content. Optinally allows post ID to be passed
 * @uses the_content()
 *
 * @param int $id Optional. Post ID.
 * @param string $more_link_text Optional. Content for when there is more text.
 * @param bool $stripteaser Optional. Strip teaser content before the more text. Default is false.
 */
function adapti_content_by_id( $post_id=0, $more_link_text = null, $stripteaser = false ){
    global $post;
    $post = &get_post($post_id);
    setup_postdata( $post, $more_link_text, $stripteaser );
    $content = get_the_content( $more_link_text, $stripteaser );
    wp_reset_postdata( $post );
    return $content;
}

function adapti_url(){
	global $wp;
	$scheme = 'http';
	if(isset($_SERVER['REQUEST_SCHEME'])) $scheme = $_SERVER['REQUEST_SCHEME'];
	return $scheme . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

function adapti_random_str($length){
    return bin2hex(openssl_random_pseudo_bytes($length));
}

function adapti_remove_js($input) {
    $output = preg_replace('~<\s*\bscript\b[^>]*>(.*?)<\s*\/\s*script\s*>~is', "", $input);
    return $output;
}

?>