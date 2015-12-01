<?php
header('Content-Type: application/hal+json');
$apiRequest = $wp_query->query_vars["nn-api"];

$requestParts = preg_split("/\//", $apiRequest);
error_log(print_r($requestParts, true));

function buildCityResponseObject($city)
{
    $blogname = $city->blogname;
    if (preg_match("/.*Test.*/i", $blogname)) {
        return null;
    } elseif (preg_match("/^[Nn]erd [Nn]ite (.*)$/", $blogname, $matches)) {
        $city_name = ucfirst($matches[1]);
        if (in_array($city_name, ["Template", "Aimeeville", "Podcast"])) {
            return null;
        }
        if ($city->public != "1" || $city->archived == "1" || $city->deleted == "1") {
            return null;
        }
        $blogId = $city->blog_id;
        $location = get_blog_option($blogId, 'city_location', '{}');
        $location = str_replace(array('lat:', 'lng:'), array('"lat":', '"lng":'), $location);

        $users = get_users(array('blog_id' => $blogId));
        $cityBosses = array();
        foreach ($users as $user) {
            array_push($cityBosses, array(name => $user->display_name));
        }

        return array(
            "id" => $blogId,
            "domain" => $city->domain,
            "name" => $city_name,
            "location" => json_decode($location),
            "_links" => array(
                "self" => "/nn-api/cities/$blogId",
                "bosses" => "/nn-api/cities/$blogId/bosses"
            ),
            "_embedded" => array(
                "bosses" => $cityBosses
            )
        );
    }
}

if ($requestParts[0] == "cities" && count($requestParts) == 1) {
    $cities = wp_get_sites();
    $cityList = array();
    foreach ($cities as $city) {
        $blog_details = get_blog_details($city[blog_id]);
        $responseObject = buildCityResponseObject($blog_details);
        if ($responseObject != null) {
            array_push($cityList, $responseObject);
        }
    }

    echo json_encode([cities => $cityList]);
} else if ($requestParts[0] == "cities" && count($requestParts) == 2) {
    $city = get_blog_details($requestParts[1]);
    if ($city != null) {
        echo json_encode([city => buildCityResponseObject($city)]);
    } else {
        http_response_code(404);
    }
} else if ($requestParts[0] == "cities" && $requestParts[2] == "bosses") {
    $city = get_blog_details($requestParts[1]);
    if ($city != null) {
        echo json_encode([bosses => buildCityResponseObject($city)[_embedded][bosses]]);
    } else {
        http_response_code(404);
    }
} else {
    ?>

    {
    "api": {
    "unknown": "<?php echo $wp_query->query_vars["nn-api"] ?>"
    }
    }

<?php } ?>