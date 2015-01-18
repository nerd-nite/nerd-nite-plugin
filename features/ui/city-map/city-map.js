function HomeControl(controlDiv, map) {

    // Set CSS styles for the DIV containing the control
    // Setting padding to 5 px will offset the control
    // from the edge of the map.
    controlDiv.style.padding = '5px';

    // Set CSS for the control border.
    var controlUI = document.createElement('div');
    controlUI.style.backgroundColor = 'white';
    controlUI.style.borderStyle = 'solid';
    controlUI.style.borderWidth = '2px';
    controlUI.style.cursor = 'pointer';
    controlUI.style.textAlign = 'center';
    controlUI.title = 'Click to set the map to Home';
    controlDiv.appendChild(controlUI);

    // Set CSS for the control interior.
    var controlText = document.createElement('div');
    controlText.style.fontFamily = 'Arial,sans-serif';
    controlText.style.fontSize = '12px';
    controlText.style.paddingLeft = '4px';
    controlText.style.paddingRight = '4px';
    controlText.innerHTML = '<strong>Find me</strong>';
    controlUI.appendChild(controlText);

    var handleNoGeolocation = function(errorFlag) {
        var content = 'Error: Your browser doesn\'t support geolocation.';
        if (errorFlag) {
             content = 'Error: The Geolocation service failed.';
        }
        console.log(content);
    };

    // Setup the click event listeners: simply set the map to Chicago.
    google.maps.event.addDomListener(controlUI, 'click', function() {
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var pos = new google.maps.LatLng(position.coords.latitude,
                    position.coords.longitude);

                var infowindow = new google.maps.InfoWindow({
                    map: map,
                    position: pos,
                    content: 'You are here',
                    zIndex: 0
                });

                map.setCenter(pos);
            }, function() {
                handleNoGeolocation(true);
            });
        } else {
            // Browser doesn't support Geolocation
            handleNoGeolocation(false);
        }
    });
}

jQuery(jQuery("#nn-city-map-display").click(function() {
    "use strict";
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

    if(navigator.geolocation) {
        var homeControlDiv = document.createElement('div');
        var homeControl = new HomeControl(homeControlDiv, map);

        homeControlDiv.index = 1;
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(homeControlDiv);
    }

    jQuery.getJSON('/nn-api/cities', function(response) {
        jQuery.each(response.cities, function(__, city) {
            var location = city.location;

            if(!location.lat) {
                return;
            }
            var locationMarker = new google.maps.Marker({
                map: map
            });
            locationMarker.setPosition(new google.maps.LatLng(location.lat, location.lng));

            var cityInfo = "<div class='city-info'><b>"+city.name+"</b><br/>"
                    + "<a href='//"+city.domain+"'>Go to that city's web page</a>";
            var infowindow = new google.maps.InfoWindow({
                content: cityInfo
            });

            google.maps.event.addListener(locationMarker, 'click', function() {
                infowindow.open(map,locationMarker);
            });
        })
    })
}));
