<?php
    header( 'Content-Type: application/hal+json' );
    $apiRequest = $wp_query->query_vars["nn-api"];

    if($apiRequest = "cities") {
        $cities = wp_get_sites();
        $cityList = array();
        foreach ($cities as $city) {
            $blog_details = get_blog_details($city[blog_id]);
            if (preg_match("/.*Test.*/i", $blog_details->blogname)) {
                continue;
            } elseif (preg_match("/^[Nn]erd [Nn]ite (.*)$/", $blog_details->blogname, $matches)) {
                $city_name = ucfirst($matches[1]);
                if(in_array($city_name, ["Template","Aimeeville", "Podcast"])) {
                    continue;
                }
                if($city['public'] != "1" || $city['archived'] == "1" || $city['deleted'] == "1") {
                    continue;
                }
                $location = get_blog_option($city[blog_id], 'city_location', '{}');
                $location =  str_replace(array('lat:','lng:'), array('"lat":', '"lng":'), $location);

                $users = get_users(array('blog_id' => $city[blog_id]));
                $cityBosses = array();
                foreach ($users as $user) {
                    array_push($cityBosses, array(name => $user->display_name));
                }

                array_push($cityList,array(
                    "id" => $city[blog_id],
                    "domain" => $city[domain],
                    "name" => $city_name,
                    "location" => json_decode($location),
                    "_links" => array(
                        "self" => "/nn-api/cities/$city[blog_id]",
                        "bosses" => "/nn-api/cities/$city[blog_id]/bosses"
                    ),
                    "_embedded" => array(
                        "bosses" => $cityBosses
                    )
                ));
            }
        }
        echo json_encode([ cities => $cityList]);
    }

    else {
?>

{
    "api": {
        "unknown": "<?php echo $wp_query->query_vars["nn-api"]?>"
    }
}

<?php } ?>