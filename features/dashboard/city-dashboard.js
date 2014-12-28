/* global cityLocation:false; */
(function() {
    var locationMarker;
    function locationIsSet() {
        return cityLocation.lat !== 0 && cityLocation.lng !== 0;
    }

    function placeMarker(location, map) {
        if(!locationMarker) {
            locationMarker = new google.maps.Marker({
                map: map
            });
        }
        locationMarker.setPosition(location);
        jQuery('#nn-city-dashboard-lat').val(location.lat());
        jQuery('#nn-city-dashboard-lng').val(location.lng());
    }


    google.maps.event.addDomListener(window, 'load', function initialize() {
        var mapOptions = {
            center: cityLocation,
            zoom: locationIsSet() ? 7 : 2
        };

        var map = new google.maps.Map(document.getElementById('nn-city-map-canvas'),
            mapOptions);

        if(locationIsSet()) {
            placeMarker(new google.maps.LatLng(cityLocation.lat, cityLocation.lng), map);
        }

        if(dashboardConfig) {
            google.maps.event.addListener(map, 'click', function (event) {
                placeMarker(event.latLng, map);
            });
        }
    });
}());