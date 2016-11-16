(function($) {

$('.search form').submit(function(e) {
	e.preventDefault();
	$('#message').fadeOut();
	
	if($(this).find('input[name="search"]').val() != '') window.location.href = $(this).attr('action') + ($(this).find('input[name="search"]').val()).replace('/', '//');
	else {
		$('#message').removeClass().addClass('message message-danger').empty().append(
			$('<p>').append('Please enter something to search first')
		).fadeIn();
	}
});

})(jQuery);