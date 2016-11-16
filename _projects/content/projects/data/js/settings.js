$('.addMore').click(function(e) {
	e.preventDefault();
	$(this).before($($('#defaultInput').clone()).attr({id: ''}));
	$(this).prev().fadeIn();
});

$('.removeInput').click(function(e) {
	e.preventDefault();
	$(this).parent().fadeOut(function() {
		$(this).remove();
	});
});