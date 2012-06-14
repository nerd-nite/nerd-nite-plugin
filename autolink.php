<?php
add_action('wpmu_new_blog', 'nn_add_link', 10, 6);

function nn_add_link($blog_id, $user_id, $domain, $path, $site_id, $meta) {
    $blog_details = get_blog_details($blog_id);
    if (preg_match("/.*Test.*/i", $blog_details->blogname)) {
        /*
           * We don't create links to test sites
           */
        return;
    } elseif (link_exists($domain)) {
        return;
    } elseif (preg_match("/^Nerd Nite (.*)$/", $blog_details->blogname, $matches)) {
        $city_name = strtolower($matches[1]);
        $term = get_term_by('slug', 'nerdnite', 'link_category');
        $linkdata = array('link_name' => $city_name, 'link_url' => $domain, 'link_category' => $term->term_id,
                          'link_rel' => 'friend');
        wp_insert_link($linkdata);
    }
    return;
}

/*
 * Checks to see if a link for a given URL exists.
 * Returns 0 if it doesn't and the link_id if it does
 */
function link_exists($link_url) {
    $bookmarks = get_bookmarks();
    $matching_id = 0;
    foreach ($bookmarks as $bookmark) {
        if ($bookmark->link_url == $link_url) {
            $matching_id = $bookmark->link_id;
            break;
        }
    }
    return $matching_id;
}

?>