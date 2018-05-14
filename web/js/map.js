var geocoder, map, zoom, marker, latlng,
    lat = document.getElementById('location-latitude'),
    lng = document.getElementById('location-longtitude'),
    address = document.getElementById('location-address');

function initialize()
{
    geocoder = new google.maps.Geocoder();
    
    if(address.value.length != 0){
        latlng = codeAddress();
        zoom = 8;
    } else {
        zoom = 14;
        latlng = doDetectUserAddress();
    }
    
    var mapOptions = {
        zoom: zoom,
        center: latlng
    }
    
    map = new google.maps.Map(document.getElementById('map'), mapOptions);
    
    marker = new google.maps.Marker();
    
    google.maps.event.addListener(map, 'click', function(event) {
        placeMarker(map, event.latLng);
        console.log(event.latLng);
    });

    google.maps.event.addListener(marker, 'dragend', function(event){
        geocodePosition(marker.getPosition());
    });
    
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        
        map.setCenter(pos);
        map.setZoom(8);
      }, function() {
        // handleLocationError(true, infoWindow, map.getCenter());
      });
    } else {
      // Browser doesn't support Geolocation
      // handleLocationError(false, infoWindow, map.getCenter());
    }
}
google.maps.event.addDomListener(window, "load", initialize);

function doDetectUserAddress(){
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        
        map.setCenter(pos);
        map.setZoom(14);
        marker = new google.maps.Marker({
            position: pos,
            map: map,
            draggable: true,
        });
        
        geocodePosition(pos);
        
        return pos;
      }, function() {
        // handleLocationError(true, infoWindow, map.getCenter());
      });
    } else {
      // Browser doesn't support Geolocation
      // handleLocationError(false, infoWindow, map.getCenter());
    }
    
} 

function placeMarker(map, location)
{
    latlng = {lat: location.lat(), lng: location.lng()};
    
    marker.length = 0;
    marker.setMap(null);
    marker = new google.maps.Marker({
        position: location,
        map: map,
        draggable: true,
    });
    
    google.maps.event.addListener(marker, 'dragend', function(event){
        geocodePosition(marker.getPosition());
    });
    
    geocoder.geocode({'location': latlng}, function(results, status) {
        if (status === 'OK') {
          if (results[1]) {
            address.value = results[0].formatted_address;
            lat.value = location.lat();
            lng.value = location.lng();
          } else {
            window.alert('No results found');
          }
        } else {
          window.alert('Geocoder failed due to: ' + status);
        }
    });
}

function codeAddress()
{
    if(address.value.length != 0){
        geocoder.geocode( { 'address': address.value}, function(results, status) {
            
            latlng = {
                lat: lat.value,
                lng: lng.value
            };
        
            // lat.value = latlng.lat;
            // lng.value = latlng.lng;
            
            if (status == 'OK') {
                map.setCenter(results[0].geometry.location);
                marker.length = 0;
                marker.setMap(null);
                marker = new google.maps.Marker({
                    map: map,
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                    position: results[0].geometry.location
                });
                
                google.maps.event.addListener(marker, 'dragend', function(event){
                    geocodePosition(marker.getPosition());
                });
            } else {
                alert('Geocode was not successful for the following reason: ' + status);
            }
        });
        
        return latlng;
    }
}

function geocodePosition(pos) 
{
   geocoder.geocode
    ({
        latLng: pos
    }, 
        function(results, status) 
        {
            if (status == google.maps.GeocoderStatus.OK) 
            {
                lat.value = results[0].geometry.location.lat();
                lng.value = results[0].geometry.location.lng();
                address.value = results[0].formatted_address;
            } 
            else 
            {
                alert('Cannot determine address at this location.'+status);
            }
        }
    );
}