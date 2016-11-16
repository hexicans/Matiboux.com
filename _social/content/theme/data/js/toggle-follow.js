(function($) {

$('a.toggle-follow').click(function(e) {
	e.preventDefault();
	var $this = $(this);
	var formData = new FormData();
	formData.append('authKey', $.cookie('AuthKey'));
	formData.append('username', $this.attr('people'));
	
	$('#message').removeClass().addClass('message message-primary').empty().append(
		$('<p>').append('Sending your request...')
	).fadeIn();
	
	$.ajax($this.attr('href'), {
		method: 'POST',
		dataType: 'json',
		data: formData,
		xhr: function() {
			var xhr = $.ajaxSettings.xhr();
			xhr.upload.addEventListener("progress", function(e) {
				if(e.lengthComputable) {
					var percentComplete = e.loaded / e.total;
					percentComplete = parseInt(percentComplete * 100);
					
					$('#message > .progress > *').width(percentComplete + '%');
					if(percentComplete === 100) $('#message > .progress > *').addClass('progress-bar-success');
				}
			}, false);
			return xhr;
		},
		contentType: false,
		processData: false
	}).done(function(data) {
		if(!data.error) {
			$this.removeClass('btn-primary btn-danger');
			if(data.doFollow) {
				$('#message').removeClass().addClass('message message-success').empty().append(
					$('<p>').append('You are now following him')
				);
				$this.addClass('btn-danger').empty().append(
					$('<i>').addClass('fa fa-user-times fa-fw'),
					' Unfollow'
				);
			}
			else {
				$('#message').removeClass().addClass('message message-success').empty().append(
					$('<p>').append('You have unfollowed him')
				);
				$this.addClass('btn-primary').empty().append(
					$('<i>').addClass('fa fa-user-plus fa-fw'),
					' Follow him!'
				);
			}
		}
		else $('#message').removeClass().addClass('message message-danger').empty().append(
			$('<p>').append(data.errorMessage)
		);
	}).fail(function() {
		$('#message').removeClass().addClass('message message-danger').empty().append(
			$('p').append('AJAX request error')
		);
	});
});

})(jQuery);