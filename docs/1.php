<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/29/15
 * Time: 10:52 PM
 */

/** WPT related post widget */
function related_posts_categories()
{
    if (is_single()) {
        global $post;
        $count = 0;
        $postIDs = array($post->ID);
        $related = '';
        $cats = wp_get_post_categories($post->ID);
        $catIDs = array();
        {
            foreach ($cats as $cat) {
                $catIDs[] = $cat;
            }
            $args = array(
                'category__in' => $catIDs,
                'post__not_in' => $postIDs,
                'showposts' => 4,
                'ignore_sticky_posts' => 0,
                'orderby' => 'rand',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'post_format',
                        'field' => 'slug',
                        'terms' => array(
                            'post-format-link',
                            'post-format-status',
                            'post-format-aside',
                            'post-format-quote'),
                        'operator' => 'NOT IN'
                    )
                )
            );
            $cat_query = new WP_Query($args);
            if ($cat_query->have_posts()) {
                while ($cat_query->have_posts()) {
                    $cat_query->the_post();
                    $related .= '<li><a href="' . get_permalink() . '" rel="bookmark" title="Permanent Link to' . get_the_title() . '">' . get_the_title() . '</a></li>';
                }
            }
        }
        if ($related) {
            printf('<div><h3>You may like:</h3><ul>%s</ul></div>', $related);
        }
        wp_reset_query();
    }
}

add_action('genesis_after_entry_content', 'related_posts_categories');