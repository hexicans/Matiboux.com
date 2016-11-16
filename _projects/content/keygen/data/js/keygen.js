// var count = 0;
$('.keygen form').submit(function(e) {
	e.preventDefault();
	var timer_millisStart = (new Date()).getTime();
	
	$('#message').removeClass().addClass('message message-primary').empty().append(
		// $('<h3>').append(
			// $('<i>').addClass('fa fa-refresh fa-fw fa-spin'), ' ',
			// 'Generating your keygen...'
		// ),
		$('<div>').addClass('progress progress-striped active').append(
			$('<div>').addClass('progress-bar').width(0)
		)
	).fadeIn();
		
	$.ajax($(this).attr('action'), {
		method: 'POST',
		dataType: 'json',
		data: new FormData($(this)[0]),
		xhr: function() {
			var xhr = $.ajaxSettings.xhr();
			xhr.upload.addEventListener("progress", function(e) {
				if(e.lengthComputable) {
					var percentComplete = e.loaded / e.total;
					percentComplete = parseInt(percentComplete * 100);
					
					$('#message .progress > *').width(percentComplete + '%');
					if(percentComplete === 100) $('#message .progress > *').addClass('progress-bar-success');
				}
			}, false);
			return xhr;
		},
		contentType: false,
		processData: false
	}).done(function(data) {
		if(!data.error) {
			// console.log(decodeURIComponent(data.keygen));
			if($.isArray(data.keygen)) {
				var keygens = [
					$('<i>').addClass('fa fa-trophy fa-fw'),
					'Yay, here is your keygens:'
				];
				var keygensList = [];
				$.each(data.keygen, function(i, keygen) {
					$.merge(keygensList, [
						'- ', decodeURIComponent(keygen), $('<br>')
					]);
				});
				$.merge(keygens, [keygensList]);
			}
			if(data.hashes) {
				var hashes = [$('<br>')];
				$.each(data.hashes, function(type, hash) {
					$.merge(hashes, [
						$('<p>').addClass('small').append(type, ': ', decodeURIComponent(hash))
					]);
				});
			}
			
			$('#message').removeClass().addClass('message message-success').empty().append(
				(keygens ?
					keygens : [
						$('<i>').addClass('fa fa-trophy fa-fw'),
						'Yay, here is your keygen: ',
						decodeURIComponent(data.keygen)
					]
				),
				(data.note ?
					[
						$('<br>'),
						$('<i>').addClass('fa fa-info fa-fw'), ' ',
						'Note: ', data.note
					] : []
				),
				(data.forceMultiCharacter ?
					[
						$('<br>'),
						$('<i>').addClass('fa fa-question fa-fw'), ' ',
						'La redondance des caractères a été forcée'
					] : []
				),
				(hashes ? hashes : [])
			).fadeIn();
			
			// if(data.keygen != 'eli') {
				// setTimeout(function() {
					// $('.keygen form').submit();
				// }, 200);
				// count++;
				// if(count % 10 == 0) console.log('doing #' + count + ' attempt');
			// }
			// else console.log('secret found in ' + count + ' attempt' + (count > 1 ? 's' : ''));
		}
		else {
			$('#message').removeClass().addClass('message message-danger').empty().append(data.errorMessage).fadeIn();
		}
	}).fail(function() {
		$('#message').removeClass().addClass('message message-danger').empty().append('AJAX request error').fadeIn();
	});
});

$('.form :reset').click(function() {
	$('#message').empty().fadeOut();
});