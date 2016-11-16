$('.scrollTop').click(function(e) {
	e.preventDefault();
	$('body').animate({
		scrollTop: $('.header').offset().top
	}, {
		duration: 600
	});
	return false;
});

$('.scrollBottom').click(function(e) {
	e.preventDefault();
	$('body').animate({
		scrollTop: $('.footer').offset().top - $(window).height()
	}, {
		duration: 600
	});
	return false;
});

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