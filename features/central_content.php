<?php
/**
 * Created by IntelliJ IDEA.
 * User: dancrumb
 * Date: 6/9/12
 * Time: 6:31 PM
 * Adds centralized content... huzzah!
 */

add_filter('the_content', 'centralContent');

function centralContent($content) {
    global $post;
    if (preg_match('[central-content([^\]]*)]', $content, $matches)) {
        $page_path = trim($matches[1]);
        $page_path or $page_path = $post->post_name;
        switch_to_blog(1);
        $central_page = get_page_by_path($page_path);
        if ($central_page) {
            $content = apply_filters('the_content', $central_page->post_content);
        } else {
            $content = "<pre>No centralized content found from http://nerdnite.com/" . $page_path . "</pre>";
        }
        restore_current_blog();
    }

    return $content;
}