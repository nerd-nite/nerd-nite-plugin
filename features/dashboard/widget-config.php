<?php
    $location = get_option('city_location');
    if(!$location) {
        $location = '{ lat:0, lng: 0 }';
    }
    echo "<script>cityLocation = $location; dashboardConfig=true;</script>";
    error_log(print_r($_POST,true));

    if(isset($_POST['latitude']) && isset($_POST['longitude'])) {
        $location = "{ lat: $_POST[latitude], lng: $_POST[longitude] }";
        error_log($location);
        update_option('city_location', $location);
    }
?>

<div id="nn-city-details-dashboard-config">
    <p>Right now there's only one thing that you can configure, and that's the location of your Nerd Nite.</p>
    <p>It doesn't have to be the venue (although that would be kind of cool). At the very least, it should
    be the city, so that it appears on the right place in a map.</p>

    Latitude: <input type="text" name="latitude" id="nn-city-dashboard-lat" readonly><br/>
    Longitude: <input type="text" name="longitude" id="nn-city-dashboard-lng" readonly>

    <p>Location is identified as <span id="location-id">{uknown}</span>.</p>
<p>Don't worry if this isn't quite right; there will be the option to change it later and it won't be exposed to anyone until then</p>

    <div id="nn-city-map-canvas"></div>


</div>
