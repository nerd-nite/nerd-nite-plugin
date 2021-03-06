<?php
add_filter('the_content', 'insertNerdGallery');
add_filter('query_vars', 'addBossInfoQVar' );
add_action('wp_enqueue_scripts', 'register_styles_and_scripts');

function register_styles_and_scripts() {
    wp_register_style('nerd-gallery', plugins_url('/nerdpics.css', __FILE__), array(), '1.04');
    wp_enqueue_style( 'nerd-gallery' );
}

define("NN_BOSS_Q_VAR", 'bossInfo');

function insertNerdGallery($content) {
    if (preg_match('[nerd-gallery]', $content, $matches)) {
        return generateNerdGallery($content);
    }
    else {
        return $content;
    }
}

function generateNerdGallery() {
    global $wp_query;
    if (isset($wp_query->query_vars[NN_BOSS_Q_VAR])) {
        $soughtBoss = $wp_query->query_vars[NN_BOSS_Q_VAR];
    }
    $content = '<div id="nerd-gallery">';

    /**
     * Add chris manually
     */
    $content .= userphoto__get_userphoto(6,
                                        USERPHOTO_FULL_SIZE ,
                                        "<div class='nerdpic' >",
                                        "<div class='nerd-caption'>Chris Balakrishnan<br/>Nerd Nite Founder</div></div>","","");

    $wp_user_search = new WP_User_Query( array( 'role' => 'City_Boss' ) );
    $bosses = $wp_user_search->get_results();
    foreach($bosses as $boss) {
        $cities = get_blogs_of_user($boss->ID);
        $city = null;
        foreach($cities as $cityObject) {
            if($cityObject->userblog_id == 1) {
                continue;
            }
            else{
                $cityName = getCityName($cityObject);
                if(! isHiddenCity($cityObject)) {
                    if(isset($city)) {
                        $city ="$city & $cityName";
                    }
                    else {
                        $city = $cityName;
                    }
                }
            }
        }

        $boss_name = (isset($boss->display_name)?$boss->display_name:$boss->user_login);

        $user_photo = userphoto__get_userphoto($boss->ID,
                                               USERPHOTO_FULL_SIZE ,
                                               "<div class='nerdpic' >",
                                               "<div class='nerd-caption'>$boss_name<br/>Nerd Nite $city Boss</div></div>","","");
        $content .= $user_photo;
    }

    /**
     * Add dan manually
     */
    $content .=userphoto__get_userphoto(9,
                             USERPHOTO_FULL_SIZE ,
                             "<div class='nerdpic' >",
                             "<div class='nerd-caption'>Dan Rumney<br/> Webmaster and Podcaster</div></div>","","");


    $content .= '</div>';
    return $content;
}

function addBossInfoQVar( $qvars ) {
    $qvars[] = NN_BOSS_Q_VAR ;
    return $qvars;
}

function isHiddenCity($city) {
    return getCityName($city) == "Aimeeville" || $city->archived == 1 || $city->deleted ==1;
}

function getCityName($cityObject) {
    $city = "";
    if(preg_match("/[nN]erd [nN]ite (.*)/", $cityObject->blogname,$cityMatch)) {
        $city = trim($cityMatch[1]);
    }
    else {
        $city = $cityObject->blogname;
    }

    switch($city) {
        case "SF":
            $city = "San Francisco";
            break;
        case "NOLA":
            $city = "New Orleans";
            break;
        case "nyc":
            $city = "New York";
            break;
        default:
            break;
    }

    return $city;
}

?>
