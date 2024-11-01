( function( $ ) {
	function set_marker(address_id, map, geocoder) {
	  	var 
	  		address = document.getElementById(address_id).value,
	  		marker;
  	
	  	geocoder.geocode( { 'address': address }, function(results, status) {
	    	if ( status == google.maps.GeocoderStatus.OK ) {
	      		map.setCenter(results[0].geometry.location);
	      		marker = new google.maps.Marker({
	          		map: map,
	          		position: results[0].geometry.location
	      		});
	    	} else {
	      		alert('Geocode was not successful for the following reason: ' + status);
	    	}
	  	});			
	}
} )( jQuery );