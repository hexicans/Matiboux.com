(function($) {

$('.new-post form .select-media, .post form .select-media').click(function(e) {
	e.preventDefault();
	$(this).parents('form').find('input[name="media"]').click();
});
$('.new-post form input[name="media"], .post form input[name="media"]').change(function() {
	if(this.files.length > 0) {
		$this = $(this);
		var reader = new FileReader();
		reader.onload = function(e) {
			$this.parents('form').find('.media').remove();
			$this.parents('form').append(
				$('<div>').addClass('media').append(
					$('<img>').attr({
						src: e.target.result
					})
				)
			);
			var height = $this.parents('form').find('.media > img').height();
			$this.parents('form').find('.media > img').css({
				top: '-' + ((height - 500) /2) + 'px'
			});
		}
		reader.readAsDataURL(this.files[0]);
	}
	else $(this).parents('form').find('.media').remove();
});
$('.new-post form, .post form').submit(function(e) {
	e.preventDefault();
	var $this = $(this);
	var media = $this.find('[name=media]')[0];
	if($this.find('[name=content]').val() != '') {
		var formData = new FormData();
		formData.append('authKey', $.cookie('AuthKey'));
		formData.append('content', $this.find('[name=content]').val());
		if($('form').parents('.post').length) formData.append('replyTo', $('form').parents('.post').attr('id'));
		if(media.files.length > 0) formData.append('media', media.files[0], media.files[0].name);
		
		$('#message').removeClass().addClass('message message-primary').empty().append(
			$('<p>').append('Sending your post...'),
			$('<div>').addClass('progress progress-striped active').append(
				$('<div>').addClass('progress-bar').width(0)
			)
		).fadeIn();
		
		$.ajax($this.attr('action'), {
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
				$('#message').fadeOut();
				window.location.href = $this.attr('redirect-url');
			}
			else {
				$('#message').removeClass().addClass('message message-danger').empty().append(
					$('<p>').append(data.errorMessage)
				).fadeIn();
			}
		}).fail(function() {
			$('#message').removeClass().addClass('message message-danger').empty().append(
				$('<p>').append('AJAX request error')
			).fadeIn();
		});
	}
});

})(jQuery);