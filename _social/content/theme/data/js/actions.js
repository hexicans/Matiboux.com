(function($) {

$('.post .action-like, .notification .action-like').click(function(e) {
	e.preventDefault();
	var $this = $(this);
	var formData = new FormData();
	formData.append('authKey', $.cookie('AuthKey'));
	formData.append('postId', $this.parents('.post, .notification').attr('id'));
	
	$.ajax($this.attr('href'), {
		method: 'POST',
		dataType: 'json',
		data: formData,
		contentType: false,
		processData: false
	}).done(function(data) {
		if(!data.error) {
			$this.parents('.post, .notification').find('.action-like').toggleClass('active');
			$this.parents('.post, .notification').find('.action-like').find('b').empty().append(data.likesCount ? data.likesCount : 0);
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

$('.post .action-repost, .notification .action-repost').click(function(e) {
	e.preventDefault();
	var $this = $(this);
	var formData = new FormData();
	formData.append('authKey', $.cookie('AuthKey'));
	formData.append('postId', $this.parents('.post, .notification').attr('id'));
	
	$.ajax($this.attr('href'), {
		method: 'POST',
		dataType: 'json',
		data: formData,
		contentType: false,
		processData: false
	}).done(function(data) {
		if(!data.error) {
			if(!data.doRepost && $this.parents('.post, .notification').hasClass('repost'))
				$this.parents('.post, .notification').fadeOut(function() {
					$(this).remove();
				});
			else {
				if(data.doRepost) $this.parents('.post, .notification').find('.action-repost').addClass('active');
				else $this.parents('.post, .notification').find('.action-repost').removeClass('active');
				
				$this.parents('.post, .notification').find('.action-repost').find('b').empty().append(data.repostsCount ? data.repostsCount : 0);
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