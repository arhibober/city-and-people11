jQuery ( function( $ ) {
	$( '#filter input' ).change( function() {
		var filter = $( '#filter' );
		window.location.href =	'/city-and-people11/filter_pagination?' + filter.serialize() +	'&page1=1';
		return false;
	});
	$( '#diapason' ).change( function() {
		var diapasonForm = $( '#diapason_form' );
		diapasonForm.find( '#range_value' ).text( diapasonForm.find( '#diapason' ).val() );
		$.ajax({
			url: diapasonForm.attr( 'action' ),
			data: diapasonForm.serialize(), // form data
			type: diapasonForm.attr( 'method' ), // POST
			beforeSend: function( xhr ) {
				diapasonForm.find( '#nearest' ).text( 'Processing...' ); // changing the button label
			},
			success: function( data ) {
				$( '#nearest' ).html( data ); // insert data
			}
		});
		console.log(' data: '+diapasonForm.serialize());
		return false;
	});
	$( '#diapason_date' ).change( function() {
		var diapasonForm = $( '#diapason_date_form' );
		diapasonForm.find( '#range_value_date' ).text( diapasonForm.find( '#diapason_date' ).val() );
		$.ajax({
			url: diapasonForm.attr( 'action' ),
			data: diapasonForm.serialize(), // form data
			type: diapasonForm.attr( 'method' ), // POST
			beforeSend: function( xhr ) {
				diapasonForm.find( '#nearest_dates' ).text( 'Processing...' ); // changing the button label
			},
			success: function( data ) {
				$( '#nearest_dates' ).html( data ); // insert data
			}
		});
		console.log(' data: '+diapasonForm.serialize());
		return false;
	});
	$( '#diapason_birthday' ).change( function() {
		var diapasonForm = $( '#diapason_birthday_form' );
		diapasonForm.find( '#range_value_birthday' ).text( diapasonForm.find( '#diapason_birthday' ).val() );
		$.ajax({
			url: diapasonForm.attr( 'action' ),
			data: diapasonForm.serialize(), // form data
			type: diapasonForm.attr( 'method' ), // POST
			beforeSend: function( xhr ) {
				diapasonForm.find( '#nearest_birthdays' ).text( 'Processing...' ); // changing the button label
			},
			success: function( data ) {
				$( '#nearest_birthdays' ).html( data ); // insert data
			}
		});
		console.log(' data: '+diapasonForm.serialize());
		return false;
	});
	$( '#diapason_die' ).change( function() {
		var diapasonForm = $( '#diapason_die_form' );
		diapasonForm.find( '#range_value_die' ).text( diapasonForm.find( '#diapason_die' ).val() );
		$.ajax({
			url: diapasonForm.attr( 'action' ),
			data: diapasonForm.serialize(), // form data
			type: diapasonForm.attr( 'method' ), // POST
			beforeSend: function( xhr ) {
				diapasonForm.find( '#nearest_die' ).text( 'Processing...' ); // changing the button label
			},
			success: function( data ) {
				$( '#nearest_die' ).html( data ); // insert data
			}
		});
		console.log(' data: '+diapasonForm.serialize());
		return false;
	});
});
