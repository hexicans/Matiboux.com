(function($) {

$('.bigMedia').click(function(e) {
	e.preventDefault();
	$(this).hide();
});

$('.post .media > img, .notification .media > img, table .preview').click(function(e) {
	e.preventDefault();
	$('.bigMedia').show().find('img').attr({
		src: $(this).attr('src')
	});
});

$(document).ready(function() {
	$('.post .media > img, .notification .media > img, table .preview').each(function() {
		var height = $(this).height();
		$(this).css({
			top: '-' + ((height - 500) /2) + 'px'
		});
	});
});

})(jQuery);