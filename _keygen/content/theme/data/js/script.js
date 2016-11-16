function setMessageStyle(element, style) {
	var isStyleAllowed = false;
	var styleAllowed = [
		'message-info',
		'message-success',
		'message-warning',
		'message-danger'
	];
	
	if(style == '') return false;
	else {
		for (eachStyleKey in styleAllowed) {
			if (styleAllowed[eachStyleKey] == style) {
				isStyleAllowed = true;
				break;
			}
		}
	}
	
	if(isStyleAllowed == true) {
		for(eachStyleKey in styleAllowed) {
			if(element.hasClass(styleAllowed[eachStyleKey])) {
				element.removeClass(styleAllowed[eachStyleKey]);
			}
		}
		
		if(!element.hasClass(style)) {
			element.addClass(style);
			return true;
		}
		else
			return false;
	}
}

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

$('.form.keygen').submit(function(e) {
	e.preventDefault();
	var timer_millisStart = (new Date()).getTime();
	
	$('#error-message').css({
		display: 'none'
	});
	
	$('#generated-message').css({
		display: 'block'
	});
	setMessageStyle($('#generated-message'), 'message-info');
	$('#generated-message h1')
		.empty()
		.append($('<i>').addClass('fa fa-refresh fa-fw fa-spin'))
		.append(' Génération de la clé...');
	$('#generated-message p')
		.empty();
	
	$.post($(this).attr('action'), {
		'genreNum': (!$('input[name="genreNum"]').prop('disabled')) ? (($('input[name="genreNum"]').prop('checked')) ? 1 : 0) : '',
		'genreMin': (!$('input[name="genreMin"]').prop('disabled')) ? (($('input[name="genreMin"]').prop('checked')) ? 1 : 0) : '',
		'genreMaj': (!$('input[name="genreMaj"]').prop('disabled')) ? (($('input[name="genreMaj"]').prop('checked')) ? 1 : 0) : '',
		'genreSpe': (!$('input[name="genreSpe"]').prop('disabled')) ? (($('input[name="genreSpe"]').prop('checked')) ? 1 : 0) : '',
		'multiCharacter': (!$('input[name="multiCharacter"]').prop('disabled')) ? (($('input[name="multiCharacter"]').prop('checked')) ? 1 : 0) : '',
		'showsHashs': (!$('input[name="showsHashs"]').prop('disabled')) ? (($('input[name="showsHashs"]').prop('checked')) ? 1 : 0) : '',
		'length': (!$('input[name="length"]').prop('disabled')) ? $('input[name=length]').val() : '',
		'blockLength': (!$('input[name="blockLength"]').prop('disabled')) ? $('input[name=blockLength]').val() : '',
		'authKey': $.cookie('AuthKeyCookie'),
		'encryptPassword': (!$('input[name="password"]').prop('disabled')) ? $('input[name=password]').val() : ''
	}).done(function(data) {
		console.log(data);
		if(!data.error) {
			if(data.forceMultiCharacter) {
				$('#error-message').css({
					display: 'block'
				});
				setMessageStyle($('#error-message'), 'message-warning');
				$('#error-message h1')
					.empty()
					.append($('<i>').addClass('fa fa-warning fa-fw'))
					.append(' La redondance des caractères a été activée');
				$('#error-message p')
					.empty()
					.append(' Vous avez entré une taille superieure aux nombre de caractères disponibles à la générétion');
			}
			
			$('#generated-message').css({
				display: 'block'
			});
			setMessageStyle($('#generated-message'), 'message-success');
			$('#generated-message h1')
				.empty()
				.append($('<i>').addClass('fa fa-trophy fa-fw'))
				.append(' Clé générée')
				.append($('<small>').append(' en ' + ((new Date()).getTime() - timer_millisStart) + ' millisecondes'));
			$('#generated-message p')
				.empty()
				.append('Votre clé est : ', $('<b>').append(decodeURIComponent(data.generatedKey)));
			
			if(data.hashs) {
				$('#generated-message p').append($('<br>'));
				$.each(data.hashs, function(type, hash) {
					$('#generated-message p')
						.append($('<br>'))
						.append($('<small>').append(type, ' :'), ' ', decodeURIComponent(hash));
				});
			}
		}
		else {
			$('#error-message').css({
				display: 'block'
			});
			setMessageStyle($('#error-message'), 'message-danger');
			$('#error-message h1')
				.empty()
				.append($('<i>').addClass('fa fa-warning fa-fw'))
				// .append(' Erreur (' + data.errorCode + ')');
				.append(' ' + data.errorMessage);
			$('#error-message p')
				.empty();
				// .append(data.errorMessage);
			
			$('#generated-message').css({
				display: 'none'
			});
		}
	}).fail(function() {
		$('#error-message').css({
			display: 'block'
		});
		setMessageStyle($('#error-message'), 'message-danger');
		$('#error-message h1')
			.empty()
			.append($('<i>').addClass('fa fa-warning fa-fw'))
			.append(' Erreur AJAX');
		$('#error-message p')
			.empty()
			.append('La requête AJAX a échouée');
		
		$('#generated-message').css({
			display: 'none'
		});
	});
});

$('.form :reset').click(function() {
	$('#error-message').css({
		display: 'none'
	});
	
	$('#generated-message').css({
		display: 'none'
	});
});

// $('a.test').click(function(e) {
	// e.preventDefault();
	
	// $.post({
		// url: 'tg.php',
		// dataType: 'html'
	// }).done(function(data) {
		// $('.main .panel .panel-body').empty();
		// $('.main .panel .panel-body').append($(data).find('body').html());
	// }).fail(function() {
		// $('.main .panel .panel-body').empty();
		// $('.main .panel .panel-body').html('error');
	// });
// });



// console.log(' -- ');
// var str = "window.location.href";
// var prefix = "window.location.origin" + "/";

// var indexOf = str.indexOf(prefix); // ==0
// var indexOfLength = prefix.length; // -> START INDEX
// var length = str.length; // -> END INDEX
// var slice = str.slice(indexOfLength, length);

// var split = str.split("http://matiboux.com/");

// console.log(indexOf);
// console.log(indexOfLength);
// console.log(length);
// console.log(split);
// console.log(slice);



// console.log(' -- ');

// console.log(window.location);
// console.log(window.location.host);
// console.log(window.location.hostname);
// console.log(window.location.pathname);