$('.form.ticket-answer .form-control[name="message"]').focus(function(e) {
	if($(this).height() < 72) {
		$(this).css({
			height: '72px'
		});
	}
});
$('.form.ticket-answer .form-control[name="message"]').focusout(function(e) {
	if($(this).val() == '') {
		$(this).css({
			height: '36px'
		});
	}
});

$('.form.ticket-answer .postMessage').click(function(e) {
	e.preventDefault();
	$(this).parents('.form').submit();
	return false;
});