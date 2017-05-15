$( document ).ready(function() {
	var handler = StripeCheckout.configure({
		key: $('#stripe-key').val(),
		image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
		locale: 'auto',
		token: function(token) {
			$('#stripe-form-token').val(token.id);
			$('#stripe-form').submit();
		}
	});

	$('.stripe-plan-button').click(function(e){
		e.preventDefault();

		$('#stripe-form-plan').val($(this).data('plan'));
		// Open Checkout with further options:
		handler.open({
			name: 'Parent Check In Hub',
			description: $(this).data('description'),
			currency: 'usd',
			amount: $(this).data('amount')
		});
	});

	// Close Checkout on page navigation:
	window.addEventListener('popstate', function() {
	  handler.close();
	});
});
