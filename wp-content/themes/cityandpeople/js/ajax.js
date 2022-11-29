jQuery ( function( $ ) {
	$( '#filter input' ).change( function() {
		var filter = $( '#filter' );
		window.location.href =	'/city-and-people11/filter_pagination?' + filter.serialize() +	'&page1=1';
		return false;
	});
	$( '#diapason' ).change( function() {
		var diapasonForm = $( '#diapason_form' );
		diapasonForm.find( '#range_value' ).text( diapasonForm.find( '#diapason' ).val() );
		let data1 = [];
		let data2 = [];
		data1.action = 'my_nearest_function';
		data2 = diapasonForm.serialize();
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
		return false;
	});
});
