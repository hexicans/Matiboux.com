$('.filehash form').submit(function(e) {
	e.preventDefault();
	if($(this).find('[name=file]')[0].files.length > 0) {
		$('#message').removeClass().addClass('message message-primary').empty().append(
			$('<div>').addClass('container').append(
				$('<h3>').append(
					$('<i>').addClass('fa fa-refresh fa-fw fa-spin'), ' ',
					'Getting your file hash...'
				),
				$('<div>').addClass('progress progress-striped active').append(
					$('<div>').addClass('progress-bar').width(0)
				)
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
				$('#message').removeClass().addClass('message message-success').empty().append(
					$('<div>').addClass('container').append(
						$('<h3>').append('Yay, here is your ', data.hashType, ' file hash:'), 
						$('<p>').append(data.fileHash)
					)
				).fadeIn();
			}
			else {
				$('#message').removeClass().addClass('message message-danger').empty().append(
					$('<div>').addClass('container').append(
						$('<h3>').append(data.errorMessage)
					)
				).fadeIn();
			}
		}).fail(function() {
			$('#message').removeClass().addClass('message message-danger').empty().append(
				$('<div>').addClass('container').append(
					$('<h3>').append('AJAX request error')
				)
			).fadeIn();
		});
	}
	else {
		$('#message').removeClass().addClass('message message-danger').empty().append(
			$('<div>').addClass('container').append(
				$('<h3>').append('Please select a file first')
			)
		).fadeIn();
	}
});

$('.form :reset').click(function() {
	$('#message').empty().fadeOut();
});