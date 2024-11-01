/**
 * Calc
 *
 * @package Tour_booking
 * @subpackage JavaScript
 */

( function( $ ) {
	$( document ).ready(function(){
		if ( $('.tb_tour_route_map').length > 0 ) {
			front_map_initialize('tour_start_address', 'tour_start_map');
			front_map_initialize('tour_finish_address', 'tour_finish_map');
			$('.tb_route_title').addClass('tb_show_map');
		}

		var 
			listing = $('.tb_tour_list'), 
			listing_count = $('.tb_tour_short').length;
		if ( listing_count > 0 && listing.attr('max') <= listing_count ) {
			listing.next().hide();
		}

		$('.tb_message_overlay, .tb_close').click( closeMessageWindow );
		$('.tb_more_tours').click( showMoreTours );

		$('#tb_tour_selector').submit( selectTours );
		$('#tb_booking_form').submit( submitBookingForm );
	});

	function closeMessageWindow(e) {
		if ( e.target != this ) return;

		$('.tb_message_overlay').fadeOut();
	}

	function front_map_initialize(address_id, map_id) {
    var 
	  	geocoder = new google.maps.Geocoder(),
	  	latlng = new google.maps.LatLng(50.4480, 30.5253),
	  	mapOptions = {
	    	zoom: 15,
	    	center: latlng
	  	},
	  	map = new google.maps.Map(document.getElementById(map_id), mapOptions),
	  	address = $('#' + address_id).text();

  	if ( address.length > 0 ) {
  		front_set_marker(address, map, geocoder);
  	}
	}

	function front_set_marker(address, map, geocoder) {
  	var marker;
	
  	geocoder.geocode( { 'address': address }, function(results, status) {
    	if ( status == google.maps.GeocoderStatus.OK ) {
      		map.setCenter(results[0].geometry.location);
      		marker = new google.maps.Marker({
      			label: address,
          		map: map,
          		position: results[0].geometry.location
      		});
    	} else {
      		alert('Geocode was not successful for the following reason: ' + status);
    	}
  	});			
	}

	function selectTours(e) {
		var 
			formData = $(e.target).serializeArray().reduce( function(obj, item) {
				obj[item.name] = item.value;
				return obj;
			}, {}),
			ajax_wait = $('#tb_booking_selector_ajax');

		formData['security'] = tbHelper.ajax_nonce;
		formData['action'] = 'tb_select_tours';

		ajax_wait.fadeIn('slow');
    $.ajax({
      type: 'POST',
      url: tbHelper.ajax_url,
      data: formData,
      success: function( result ) {
      	// console.log(result);
				ajax_wait.fadeOut('slow');
      	window.location.href = result;
      }
    });
		return false;
	}

	function showMoreTours(e) {
		var 
			count_tours = $('.tb_tour_short').length,
			more_button = $(e.target),
			ajax_wait = more_button.next(),
			tour_list = more_button.prev(),
			atts = JSON.parse( tour_list.attr('atts') );

		ajax_wait.fadeIn('slow');

    $.ajax({
      type: 'POST',
      url: tbHelper.ajax_url,
      data: { 
      	'action': 'tb_show_more_tours',
      	'atts': atts, 
      	'offset': count_tours
  	 	},
      success: function( result ) {
		    ajax_wait.fadeOut();
		    tour_list.append(result);

		    if ( tour_list.attr('max') >= count_tours ) {
		    	more_button.hide();
		    }
      }
    });
	}

	function submitBookingForm( e ) {

		$('#tb_booking_form_ajax').fadeIn('slow');

		grecaptcha.ready(function() {
      grecaptcha.execute( tbHelper.recaptcha_public, {action: 'homepage'} ).then(function(token) {

				var formData = $(e.target).serializeArray().reduce( function(obj, item) {
					obj[item.name] = item.value;
					return obj;
				}, {});
				formData['security'] = tbHelper.ajax_nonce;
				formData['token'] = token;
				formData['action'] = 'tb_book_tour';

		    $.ajax({
	        type: 'POST',
	        url: tbHelper.ajax_url,
	        data: formData,
	        success: function( result ) {
				    // console.log(result);
				    $("#tb_booking_form_ajax").fadeOut();
	        	if ( result == 'recaptcha_error' ) {
							$('#tb_message_error').fadeIn();
	        	} else {
							$('#tb_message_overlay').fadeIn();
	        	}
	        }
		    });
      });
	  });			

		return false;
	}

} )( jQuery );