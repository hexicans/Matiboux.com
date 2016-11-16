/*\
|*|
|*|  :: Mobile Emulation Script ::
|*|
|*|  Mobile Emulator
|*|  Copyright 2015 Matiboux
|*|
|*|  Created on the 9th September 2015
|*|
|*|  http://twitter.com/Matiboux
|*|
\*/

/** Main */
(function($, core) {
	
	/** Boot Mobile */
	core.bootMobile();
	
	setTimeout(function() {
		
		/** Set Battery & Time */
		$('.mobile .status-bar > .right-side > .battery').attr({
			batteryLevel: 100
		}).text('100% ').append(
			$('<i>').addClass('fa fa-battery-4')
		);
		$('.mobile .status-bar > .right-side > .time').attr({
			datetime: core.getDate('Y-m-d H:i:s')
		}).text(core.getDate('H:i'));
		
		/** Load Home Screen */
		core.apps['home'] = [];
		core.apps['home'].push(
			$('<form>').addClass('search-form').attr({
				action: '#'
			}).append(
				$('<div>').addClass('search-group').append(
					$('<input>').addClass('query').attr({
						type: 'text',
						placeholder: 'Recherche'
					}),
					$('<button>').addClass('reset').attr({
						type: 'reset'
					}).append(
						$('<i>').addClass('fa fa-remove')
					),
					$('<button>').addClass('submit').attr({
						type: 'submit'
					}).append(
						$('<i>').addClass('fa fa-search')
					)
				)
			),
			$('<div>').addClass('apps').append(
				// $('<li>').addClass('open-app').attr({
					// openApp: 'ChatApp'
				// }).append(
					// $('<i>').addClass('fa fa-send'),
					// $('<label>').text('ChatApp')
				// )
			),
			$('<div>').addClass('shortcuts').append(
				/** 4 Slots available */
				$('<li>').addClass('open-app').attr({
					openApp: 'ChatApp'
				}).append(
					$('<i>').addClass('fa fa-send')
				),
				$('<li>').addClass('open-app').attr({
					openApp: ''
				}).append(
					$('<i>').addClass('fa fa-minus')
				),
				$('<li>').addClass('open-app').attr({
					openApp: ''
				}).append(
					$('<i>').addClass('fa fa-minus')
				),
				$('<li>').addClass('open-app').attr({
					openApp: 'Settings'
				}).append(
					$('<i>').addClass('fa fa-cog')
				)
			)
		);
		core.changeContentApp('home');
		
	}, 3000);
	
}(jQuery, Mobile));