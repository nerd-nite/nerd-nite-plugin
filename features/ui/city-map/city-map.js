jQuery(jQuery("#nn-city-map-display").click(function() {
    "use strict";
    console.log("Opening Map");
    jQuery('#nn-map-of-cities').dialog({
        modal: true,width:800, height:500
    });
    var baryCenter = { lat:40.866667, lng: 34.566667 };
    var mapOptions = {
        center: baryCenter,
        zoom:  2,
        streetViewControl: false,
        mapTypeControl:false

    };

    var map = new google.maps.Map(document.getElementById('nn-map-of-cities'),
        mapOptions);

    jQuery.getJSON('/nn-api/cities', function(response) {
        console.log(response);
        jQuery.each(response.cities, function(__, city) {
            var location = city.location;

            if(!location.lat) {
                return;
            }
            var locationMarker = new google.maps.Marker({
                map: map
            });
            locationMarker.setPosition(new google.maps.LatLng(location.lat, location.lng));
        })
    })
}));
