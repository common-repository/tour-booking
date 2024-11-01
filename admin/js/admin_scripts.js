/**
 * Calc
 *
 * @package Tour_booking
 * @subpackage JavaScript
 */
( function( $ ) {
	$(document).ready(function(){

		$('.tourbooking_options_tab').click( changeTab );
		$('.tourbooking_color').wpColorPicker();

		if ( $('#tour_start_address').length ) {
			admin_map_initialize('tour_start_address', 'tour_start_map');
			admin_map_initialize('tour_finish_address', 'tour_finish_map');
		}

	});

	function admin_map_initialize(address_id, map_id) {

	    var 
	    	autocomplete = new google.maps.places.Autocomplete(
		    /** @type {HTMLInputElement} */ ( document.getElementById(address_id) ), 
			    {
		        	types: ['geocode']
			    }
		    ),
		  	geocoder = new google.maps.Geocoder(),
		  	latlng = new google.maps.LatLng(50.4480, 30.5253),
		  	mapOptions = {
		    	zoom: 15,
		    	center: latlng
		  	},
		  	map = new google.maps.Map(document.getElementById(map_id), mapOptions);

	  	if ( $('#' + address_id).val().length > 0 ) {
	  		admin_set_marker(address_id, map, geocoder);
	  	}

		$('#' + address_id).focusout(function() {
	  		admin_set_marker(address_id, map, geocoder);
		});
	}

	function admin_set_marker(address_id, map, geocoder) {
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

	function changeTab(e) {
		var 
			currentTab = $(e.target),
			tabId = currentTab.attr('options_id');
		console.log( tabId );

		$('.tourbooking_option_groups.active, .tourbooking_options_tab.active').removeClass('active');
		currentTab.add('#' + tabId).addClass('active');
	}


} )( jQuery );