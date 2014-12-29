/* global cityLocation:false; */
(function() {
    var locationMarker;
    var geocoder;
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
        geocoder.geocode({latLng: location}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
locality = _.find(results,function(a) {  return _.contains(a.types, 'locality')});
        if (locality) {
		jQuery('#location-id').text(locality.formatted_address);	
        }
      } else {
        alert("Geocoder failed due to: " + status);
      }

	});
    }


    google.maps.event.addDomListener(window, 'load', function initialize() {
        geocoder = new google.maps.Geocoder();
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
