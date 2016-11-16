(function($) {

$('.menu-links > p').click(function(e) {
	e.preventDefault();
	
	if(!$(this).hasClass('text-muted')) {
		$(this).parents('.menu-links').find('p').css({
			'font-weight': 'initial'
		}).removeClass().addClass('text-primary');
		$(this).css({
			'font-weight': 700
		}).removeClass().addClass('text-muted');
		
		$('body').animate({
			scrollTop: $('*[from="' + $(this).attr('value') + '"]').offset().top
		}, 600);
	}
});

$('.new-post .form .form-control[name="content"], .post .form .form-control[name="content"], .ticket .form .form-control[name="message"]').focus(function(e) {
	if($(this).height() < 72) {
		$(this).css({
			height: '72px'
		});
	}
});
$('.new-post .form .form-control[name="content"], .post .form .form-control[name="content"], .ticket .form .form-control[name="message"]').focusout(function(e) {
	if($(this).val() == '') {
		$(this).css({
			height: '36px'
		});
	}
});

$('form .submit').click(function(e) {
	e.preventDefault();
	$(this).parents('form').submit();
});
$('.form :reset').click(function() {
	$('#message').css({
		display: 'none'
	});
});

$('.edit-avatar form .delete-avatar').click(function(e) {
	e.preventDefault();
	var $this = $(this);
	
	$this.parents('.form').find('.progress').fadeIn();
	$this.parents('.form').find('.progress > *').removeClass('progress-bar-success').width(0);
	$this.parents('.form').find('input[name="avatar"]').next().empty().append(
		$('<i>').addClass('fa fa-angle-right fa-fw'),
		" Deleting... ",
		$('<i>').addClass('fa fa-circle-o-notch fa-spin fa-fw')
	);
	
	$.ajax({
		url: $this.parents('.form').attr('action'),
		type: 'POST',
		dataType: 'json',
		data: {
			authKey: $.cookie('AuthKey'),
			size: 80
		},
		xhr: function() {
			var xhr = $.ajaxSettings.xhr();
			xhr.upload.addEventListener("progress", function(e) {
				if(e.lengthComputable) {
					var percentComplete = e.loaded / e.total;
					percentComplete = parseInt(percentComplete * 100);
					
					$this.parents('.form').find('.progress > *').width(percentComplete + '%');
					if(percentComplete === 100) $this.parents('.form').find('.progress > *').addClass('progress-bar-success');
				}
			}, false);
			return xhr;
		}
	}).done(function(data) {
		if(!data.error) {
			$this.parents('.form').find('.avatar').attr('src', data.url);
			$this.parents('.form').find('input[name="avatar"]').next().empty().append(
				$('<i>').addClass('fa fa-angle-right fa-fw'),
				" Deleted ",
				$('<i>').addClass('fa fa-check fa-fw')
			);
		}
		else $this.next().find('.avatar-status').removeClass().addClass('fa fa-times fa-fw').before('(' + data.error + ')', ' ');
	}).fail(function() {
		$this.next().find('.avatar-status').removeClass().addClass('fa fa-times fa-fw').before('(Ajax error)', ' ');
	}).always(function() {
		$this.parents('.form').find('.progress').fadeOut();
	});
});

$('.edit-avatar form input[name="avatar"]').change(function() {
	if(this.files.length > 0) {
		var $this = $(this);
		var avatar = this.files[0];
		var formData = new FormData();
		formData.append('authKey', $.cookie('AuthKey'));
		formData.append('avatar', avatar, avatar.name);
		
		$this.parents('.form').find('.progress').fadeIn();
		$this.parents('.form').find('.progress > *').removeClass('progress-bar-success').width(0);
		$this.next().empty().append(
			$('<i>').addClass('fa fa-angle-right fa-fw'),
			" ", avatar.name, " ",
			$('<i>').addClass('fa fa-circle-o-notch fa-spin fa-fw avatar-status')
		);
		
		$.ajax({
			url: $this.parents('.form').attr('action'),
			type: 'POST',
			dataType: 'json',
			data: formData,
			xhr: function() {
				var xhr = $.ajaxSettings.xhr();
				xhr.upload.addEventListener("progress", function(e) {
					if(e.lengthComputable) {
						var percentComplete = e.loaded / e.total;
						percentComplete = parseInt(percentComplete * 100);
						
						$this.parents('.form').find('.progress > *').width(percentComplete + '%');
						if(percentComplete == 100) $this.parents('.form').find('.progress > *').addClass('progress-bar-success');
					}
				}, false);
				return xhr;
			},
			contentType: false,
			processData: false
		}).done(function(data) {
			if(!data.error) {
				$this.parents('.form').find('.avatar').attr('src', data.url);
				$this.next().find('.avatar-status').removeClass().addClass('fa fa-check fa-fw');
			}
			else $this.next().find('.avatar-status').removeClass().addClass('fa fa-times fa-fw').before('(' + data.error + ')', ' ');
		}).fail(function() {
			$this.next().find('.avatar-status').removeClass().addClass('fa fa-times fa-fw').before('(Ajax error)', ' ');
		}).always(function() {
			$this.parents('.form').find('.progress').fadeOut();
			$this.val('');
		});
	}
});

$(window).scroll(function(e) {
	e.preventDefault();
	return false;
});
$(document).ready(function() {
	$(window).scroll();
});

})(jQuery);