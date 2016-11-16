$('.switchTo > * > *').click(function(e) {
	e.preventDefault();
	
	if(!$(this).hasClass('text-muted')) {
		$(this).parents('.switchTo').find('* > span').css({
			'font-weight': 'initial'
		}).removeClass().addClass('text-primary');
		$(this).removeClass().css({
			'font-weight': 700
		}).addClass('text-muted');
		
		$('*[only]').hide().find('input').prop({
			disabled: true
		});
		$('*[only="' + $(this).attr('value') + '"]').fadeIn();
		$('*[only="' + $(this).attr('value') + '"]').find('input').prop({
			disabled: false
		});
	}
	
	return false;
});