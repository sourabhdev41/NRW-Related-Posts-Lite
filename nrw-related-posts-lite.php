<?php
/**
 * Plugin Name: NRW Related Posts Lite
 * Plugin URI: https://wp.nrwone.in
 * Description: Display related posts based on shared categories.
 * Version: 1.0.0
 * Author: NRW India
 * Author URI: https://nrwone.in
 * License: GPLv2 or later
 * Text Domain: nrw-related-posts-lite
 */

if (!defined('ABSPATH')) {
    exit;
}

function nrw_rpl_show_related_posts($content) {

    if (!is_single() || !in_the_loop() || !is_main_query()) {
        return $content;
    }

    global $post;

    $categories = wp_get_post_categories($post->ID);

    if (empty($categories)) {
        return $content;
    }

    $related_posts = new WP_Query(array(
        'category__in'   => $categories,
        'post__not_in'   => array($post->ID),
        'posts_per_page' => 5,
        'orderby'        => 'rand'
    ));

    if ($related_posts->have_posts()) {

        $related_html = '<div class="nrw-related-posts">';
        $related_html .= '<h3>Related Posts</h3>';
        $related_html .= '<ul>';

        while ($related_posts->have_posts()) {
            $related_posts->the_post();

            $related_html .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
        }

        $related_html .= '</ul>';
        $related_html .= '</div>';

        wp_reset_postdata();

        $content .= $related_html;
    }

    return $content;
}

add_filter('the_content', 'nrw_rpl_show_related_posts');
