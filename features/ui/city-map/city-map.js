jQuery(jQuery("#nn-city-map-display").click(function() {
    "use strict";
    console.log("Opening Map");
    jQuery('#nn-map-of-cities').dialog({
        modal: true
    });
    var cityLocation = { lat:0, lng: 0 };
    var mapOptions = {
        center: cityLocation,
        zoom:  2
    };

    var map = new google.maps.Map(document.getElementById('nn-map-of-cities'),
        mapOptions);
}));