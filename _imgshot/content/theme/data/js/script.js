$('#showSensitiveContent').click(function(e) {
	e.preventDefault();
	$(this).parent().next().show();
	$(this).parent().remove();
});