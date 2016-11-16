/*\
|*|
|*|  :: Mobile Emulation Core ::
|*|
|*|  Mobile Emulator
|*|  Copyright 2015 Matiboux
|*|
|*|  Created on the 9th September 2015
|*|
|*|  http://twitter.com/Matiboux
|*|
\*/


/** *** *** *** */

/** Start time of script */
var dateStart = new Date;

/** Mobile Emulator Core */
var Mobile = {}; // Core
	Mobile.settings = {}; // Config & Settings
	Mobile.apps = {}; // Apps Code
	Mobile.error = {}; // Apps Code

/** Verify is jQuery is loaded */
if (typeof jQuery === 'undefined') {
	throw new Error('Mobile Emulator\'s Script require jQuery');
}

/** *** *** *** */

(function($, core) {
	
/** --------------- */
/**  Configuration  */
/** --------------- */

/** Identity Parameters */
core.settings['identity'] = {
	name: ''
};

/** Confidentiality Parameters */
core.settings['confidentiality'] = {
	keepHistory: false
};

/** Batery Settings */
core.settings['battery'] = {
	randomUpdate: false,
	refreshInterval: 12000
};

/** Time Settings */
core.settings['time'] = {
	refreshInterval: 1000
};

/** ----------- */
/**  Functions  */
/** ----------- */
		
/** Get Date */
core.getDate = function(format, timestamp) {
	
	// var timestamp = timestamp || (new Date);
	// var date = {};
		// date.timestamp = currentDate.getTime();
		// date.year = currentDate.getFullYear();
		// date.month = ('0' + (currentDate.getMonth() + 1)).slice(-2);
		// date.date = ('0' + currentDate.getDate()).slice(-2);
		// date.hours = ('0' + currentDate.getHours()).slice(-2);
		// date.minutes = ('0' + currentDate.getMinutes()).slice(-2);
		// date.seconds = ('0' + currentDate.getDate()).slice(-2);
		// date.millis = ('00' + currentDate.getMilliseconds()).slice(-3);
	
	  var that = this;
	  var jsdate, f;
	  // Keep this here (works, but for code commented-out below for file size reasons)
	  // var tal= [];
	  var txt_words = [
		'Sun', 'Mon', 'Tues', 'Wednes', 'Thurs', 'Fri', 'Satur',
		'January', 'February', 'March', 'April', 'May', 'June',
		'July', 'August', 'September', 'October', 'November', 'December'
	  ];
	  // trailing backslash -> (dropped)
	  // a backslash followed by any character (including backslash) -> the character
	  // empty string -> empty string
	  var formatChr = /\\?(.?)/gi;
	  var formatChrCb = function(t, s) {
		return f[t] ? f[t]() : s;
	  };
	  var _pad = function(n, c) {
		n = String(n);
		while (n.length < c) {
		  n = '0' + n;
		}
		return n;
	  };
	  f = {
		// Day
		d: function() { // Day of month w/leading 0; 01..31
		  return _pad(f.j(), 2);
		},
		D: function() { // Shorthand day name; Mon...Sun
		  return f.l()
			.slice(0, 3);
		},
		j: function() { // Day of month; 1..31
		  return jsdate.getDate();
		},
		l: function() { // Full day name; Monday...Sunday
		  return txt_words[f.w()] + 'day';
		},
		N: function() { // ISO-8601 day of week; 1[Mon]..7[Sun]
		  return f.w() || 7;
		},
		S: function() { // Ordinal suffix for day of month; st, nd, rd, th
		  var j = f.j();
		  var i = j % 10;
		  if (i <= 3 && parseInt((j % 100) / 10, 10) == 1) {
			i = 0;
		  }
		  return ['st', 'nd', 'rd'][i - 1] || 'th';
		},
		w: function() { // Day of week; 0[Sun]..6[Sat]
		  return jsdate.getDay();
		},
		z: function() { // Day of year; 0..365
		  var a = new Date(f.Y(), f.n() - 1, f.j());
		  var b = new Date(f.Y(), 0, 1);
		  return Math.round((a - b) / 864e5);
		},

		// Week
		W: function() { // ISO-8601 week number
		  var a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3);
		  var b = new Date(a.getFullYear(), 0, 4);
		  return _pad(1 + Math.round((a - b) / 864e5 / 7), 2);
		},

		// Month
		F: function() { // Full month name; January...December
		  return txt_words[6 + f.n()];
		},
		m: function() { // Month w/leading 0; 01...12
		  return _pad(f.n(), 2);
		},
		M: function() { // Shorthand month name; Jan...Dec
		  return f.F()
			.slice(0, 3);
		},
		n: function() { // Month; 1...12
		  return jsdate.getMonth() + 1;
		},
		t: function() { // Days in month; 28...31
		  return (new Date(f.Y(), f.n(), 0))
			.getDate();
		},

		// Year
		L: function() { // Is leap year?; 0 or 1
		  var j = f.Y();
		  return j % 4 === 0 & j % 100 !== 0 | j % 400 === 0;
		},
		o: function() { // ISO-8601 year
		  var n = f.n();
		  var W = f.W();
		  var Y = f.Y();
		  return Y + (n === 12 && W < 9 ? 1 : n === 1 && W > 9 ? -1 : 0);
		},
		Y: function() { // Full year; e.g. 1980...2010
		  return jsdate.getFullYear();
		},
		y: function() { // Last two digits of year; 00...99
		  return f.Y()
			.toString()
			.slice(-2);
		},

		// Time
		a: function() { // am or pm
		  return jsdate.getHours() > 11 ? 'pm' : 'am';
		},
		A: function() { // AM or PM
		  return f.a()
			.toUpperCase();
		},
		B: function() { // Swatch Internet time; 000..999
		  var H = jsdate.getUTCHours() * 36e2;
		  // Hours
		  var i = jsdate.getUTCMinutes() * 60;
		  // Minutes
		  var s = jsdate.getUTCSeconds(); // Seconds
		  return _pad(Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3);
		},
		g: function() { // 12-Hours; 1..12
		  return f.G() % 12 || 12;
		},
		G: function() { // 24-Hours; 0..23
		  return jsdate.getHours();
		},
		h: function() { // 12-Hours w/leading 0; 01..12
		  return _pad(f.g(), 2);
		},
		H: function() { // 24-Hours w/leading 0; 00..23
		  return _pad(f.G(), 2);
		},
		i: function() { // Minutes w/leading 0; 00..59
		  return _pad(jsdate.getMinutes(), 2);
		},
		s: function() { // Seconds w/leading 0; 00..59
		  return _pad(jsdate.getSeconds(), 2);
		},
		u: function() { // Microseconds; 000000-999000
		  return _pad(jsdate.getMilliseconds() * 1000, 6);
		},

		// Timezone
		e: function() { // Timezone identifier; e.g. Atlantic/Azores, ...
		  // The following works, but requires inclusion of the very large
		  // timezone_abbreviations_list() function.
		  /*              return that.date_default_timezone_get();
		   */
		  throw 'Not supported (see source code of date() for timezone on how to add support)';
		},
		I: function() { // DST observed?; 0 or 1
		  // Compares Jan 1 minus Jan 1 UTC to Jul 1 minus Jul 1 UTC.
		  // If they are not equal, then DST is observed.
		  var a = new Date(f.Y(), 0);
		  // Jan 1
		  var c = Date.UTC(f.Y(), 0);
		  // Jan 1 UTC
		  var b = new Date(f.Y(), 6);
		  // Jul 1
		  var d = Date.UTC(f.Y(), 6); // Jul 1 UTC
		  return ((a - c) !== (b - d)) ? 1 : 0;
		},
		O: function() { // Difference to GMT in hour format; e.g. +0200
		  var tzo = jsdate.getTimezoneOffset();
		  var a = Math.abs(tzo);
		  return (tzo > 0 ? '-' : '+') + _pad(Math.floor(a / 60) * 100 + a % 60, 4);
		},
		P: function() { // Difference to GMT w/colon; e.g. +02:00
		  var O = f.O();
		  return (O.substr(0, 3) + ':' + O.substr(3, 2));
		},
		T: function() { // Timezone abbreviation; e.g. EST, MDT, ...
		  // The following works, but requires inclusion of the very
		  // large timezone_abbreviations_list() function.
		  /*              var abbr, i, os, _default;
		  if (!tal.length) {
			tal = that.timezone_abbreviations_list();
		  }
		  if (that.php_js && that.php_js.default_timezone) {
			_default = that.php_js.default_timezone;
			for (abbr in tal) {
			  for (i = 0; i < tal[abbr].length; i++) {
				if (tal[abbr][i].timezone_id === _default) {
				  return abbr.toUpperCase();
				}
			  }
			}
		  }
		  for (abbr in tal) {
			for (i = 0; i < tal[abbr].length; i++) {
			  os = -jsdate.getTimezoneOffset() * 60;
			  if (tal[abbr][i].offset === os) {
				return abbr.toUpperCase();
			  }
			}
		  }
		  */
		  return 'UTC';
		},
		Z: function() { // Timezone offset in seconds (-43200...50400)
		  return -jsdate.getTimezoneOffset() * 60;
		},

		// Full Date/Time
		c: function() { // ISO-8601 date.
		  return 'Y-m-d\\TH:i:sP'.replace(formatChr, formatChrCb);
		},
		r: function() { // RFC 2822
		  return 'D, d M Y H:i:s O'.replace(formatChr, formatChrCb);
		},
		U: function() { // Seconds since UNIX epoch
		  return jsdate / 1000 | 0;
		}
	  };
	  this.date = function(format, timestamp) {
		that = this;
		jsdate = (timestamp === undefined ? new Date() : // Not provided
		  (timestamp instanceof Date) ? new Date(timestamp) : // JS Date()
		  new Date(timestamp * 1000) // UNIX timestamp (auto-convert to int)
		);
		return format.replace(formatChr, formatChrCb);
	  };
	  return this.date(format, timestamp);
	
}

/** Load Mobile Screen */
core.loadMobileScreen = function() {
	$('.mobile > *').empty();
	$('.mobile > *').append(
		$('<div>').addClass('status-bar').append(
			$('<div>').addClass('left-side').append(
				$('<div>').addClass('notifications').append(
					$('<div>').attr({
						id: 'twitter'
					}).append(
						$('<i>').addClass('fa fa-twitter')
					),
					$('<div>').attr({
						id: 'twitter'
					}).append(
						$('<i>').addClass('fa fa-twitter')
					),
					$('<div>').attr({
						id: 'facebook'
					}).append(
						$('<i>').addClass('fa fa-facebook')
					),
					$('<div>').attr({
						id: 'download'
					}).append(
						$('<i>').addClass('fa fa-download')
					)
				)
			),
			$('<div>').addClass('right-side').append(
				$('<div>').addClass('signals').append(
					$('<div>').addClass('wifi').append(
						$('<i>').addClass('fa fa-wifi')
					),
					$('<div>').addClass('cellular').append(
						$('<i>').addClass('fa fa-signal')
					)
				),
				$('<div>').addClass('battery'),
				$('<time>').addClass('time')
			)
		),
		$('<div>').addClass('content').attr({
			id: ''
		}),
		$('<div>').addClass('nav-bar').append(
			$('<div>').addClass('tools').append(
				$('<i>').addClass('fa fa-ellipsis-h')
			),
			$('<div>').addClass('home').append(
				$('<i>').addClass('fa fa-home')
			),
			$('<div>').addClass('return').append(
				$('<i>').addClass('fa fa-angle-left')
			)
		)
	);
	
}
	
/** Boot Mobile */
core.bootMobile = function() {
	
	/** Hide Loading Icon */
	$('.messages .loading-icon').hide();
	
	/** Add Info Message */
	$('.messages > *').append(
		$('<div>').addClass('message message-info').append(
			$('<div>').addClass('message-icon').append(
				$('<i>').addClass('fa fa-mobile')
			),
			$('<div>').addClass('message-content').append(
				$('<h2>').text('Template Mobile Android-like'),
				$('<p>').html(' \
				Template simulant un appariel Mobile Android <br /> \
				Resolution  480p (720x480)')
			)
		)
	);
	
	/** Launch Boot Screen */
	$('.mobile > *').empty().append(
		$('<div>').addClass('content full-screen').attr({
			id: 'boot-screen'
		}).append(
			$('<div>').addClass('icon').append(
				$('<i>').addClass('fa fa-refresh fa-spin')
			),
			$('<div>').addClass('text').text('Démarrage en cours...')
		)
	);
	
	/** Load Mobile Screen */
	setTimeout(function() {
		
		/** Load Mobile Screen */
		core.loadMobileScreen();
		
	}, 2000);
	
}
	
/** Change Content App */
core.changeContentApp = function(loadApp) {
	
	core.apps[$('.mobile .content').attr('id')] = $('.mobile .content > *').clone();
	
	if(typeof loadApp != 'undefined') {
		$('.mobile .content').empty();
		
		if(core.apps[loadApp] != null) {
			$('.mobile .content').attr({
				id: loadApp
			}).append(core.apps[loadApp]);
		}
		else {
			$('.mobile > *').empty().append(
				$('<div>').addClass('content full-screen').attr({
					id: 'app-not-found'
				}).append(
					$('<div>').addClass('icon').append(
						$('<i>').addClass('fa fa-warning')
					)
				).append(
					$('<div>').addClass('text').text('App non trouvé')
				)
			);
		}
	}
	
}
	
/** Set Battery Level */
core.setBatteryLevel = function(newBatteryLevel) {
	
	if(newBatteryLevel >= 0) {
		$('.mobile .status-bar .battery').attr({
			batteryLevel: newBatteryLevel
		}).text(newBatteryLevel + '% ');
		
		if(newBatteryLevel <= 20) $('.mobile .status-bar .battery').addClass('critical').append($('<i>').addClass('fa fa-battery-0'));
		else if(newBatteryLevel > 20 && newBatteryLevel <= 40) $('.mobile .status-bar .battery').append($('<i>').addClass('fa fa-battery-1'));
		else if(newBatteryLevel > 40 && newBatteryLevel <= 60) $('.mobile .status-bar .battery').append($('<i>').addClass('fa fa-battery-2'));
		else if(newBatteryLevel > 60 && newBatteryLevel <= 80) $('.mobile .status-bar .battery').append($('<i>').addClass('fa fa-battery-3'));
		else if(newBatteryLevel > 80 && newBatteryLevel <= 100) $('.mobile .status-bar .battery').append($('<i>').addClass('fa fa-battery-4'));
		
		if(newBatteryLevel == 0 && $('.mobile .content').attr('id') != 'low-battery') {
			$('.mobile > *').empty().append(
				$('<div>').addClass('content full-screen').attr({
					id: 'low-battery'
				}).append(
					$('<div>').addClass('icon').append(
						$('<i>').addClass('fa fa-battery-0')
					)
				).append(
					$('<div>').addClass('text').text('Batterie faible')
				)
			);
		}
	}
	else
		return false;
	
}
	
}(jQuery, Mobile));


/** Live */
(function($, core) {
	
	/** Update Time */
	var updateTime = setInterval(function() {
		$('.mobile .status-bar .time').attr({
			datetime: core.getDate('Y-m-d H:i:s')
		}).text(core.getDate('H:i'));
	}, core.settings['time'].updateInterval);
	
	/** Update Battery Level */
	var updateBatteryLevel = setInterval(function() {
		var currentBatteryLevel = $('.mobile .status-bar .battery').attr('batteryLevel');
		
		if(core.settings['battery'].randomUpdate && Math.floor((Math.random() * 2) + 1) == 1)
			var newBatteryLevel = currentBatteryLevel - 1;
		else
			var newBatteryLevel = currentBatteryLevel;
		
		core.setBatteryLevel(newBatteryLevel);
	}, core.settings['battery'].updateInterval);
	
	/** Open App */
	$('.open-app').click(function() {
		core.changeContentApp($(this).attr('openApp'));
	});

	/** ** Nav Bar ** */

	/** Go Home */
	$('.nav-bar .home').click(function() {
		core.changeContentApp('home');
	});
	
}(jQuery, Mobile));


// /** Main */
// (function($, core) {
	
	// /** Boot Mobile */
	// core.bootMobile();
	
	// setTimeout(function() {
		
		// /** Set Battery & Time */
		// $('.mobile .status-bar > .right-side > .battery').attr({
			// batteryLevel: 100
		// }).text('100% ').append(
			// $('<i>').addClass('fa fa-battery-4')
		// );
		// $('.mobile .status-bar > .right-side > .time').attr({
			// datetime: core.getDate('Y-m-d H:i:s')
		// }).text(core.getDate('H:i'));
		
		// /** Load Home Screen */
		// core.apps['home'] = [];
		// core.apps['home'].push(
			// $('<form>').addClass('search-form').attr({
				// action: '#'
			// }).append(
				// $('<div>').addClass('search-group').append(
					// $('<input>').addClass('query').attr({
						// type: 'text',
						// placeholder: 'Recherche'
					// }),
					// $('<button>').addClass('reset').attr({
						// type: 'reset'
					// }).append(
						// $('<i>').addClass('fa fa-remove')
					// ),
					// $('<button>').addClass('submit').attr({
						// type: 'submit'
					// }).append(
						// $('<i>').addClass('fa fa-search')
					// )
				// )
			// ),
			// $('<div>').addClass('apps').append(
				// // $('<li>').addClass('open-app').attr({
					// // openApp: 'ChatApp'
				// // }).append(
					// // $('<i>').addClass('fa fa-send'),
					// // $('<label>').text('ChatApp')
				// // )
			// ),
			// $('<div>').addClass('shortcuts').append(
				// /** 4 Slots available */
				// $('<li>').addClass('open-app').attr({
					// openApp: 'ChatApp'
				// }).append(
					// $('<i>').addClass('fa fa-send')
				// ),
				// $('<li>').addClass('open-app').attr({
					// openApp: ''
				// }).append(
					// $('<i>').addClass('fa fa-minus')
				// ),
				// $('<li>').addClass('open-app').attr({
					// openApp: ''
				// }).append(
					// $('<i>').addClass('fa fa-minus')
				// ),
				// $('<li>').addClass('open-app').attr({
					// openApp: 'Settings'
				// }).append(
					// $('<i>').addClass('fa fa-cog')
				// )
			// )
		// );
		// core.changeContentApp('home');
		
	// }, 3000);


	// /** -- Chat App -- */

	// /** Chat App: Get Channel Messages */
	// function getChannelMessages(channel_id) {
		// channel_id = channel_id || 0;
		// var channels = [];
		
		// channels[7587] = [
			// {
				// author: 'you',
				// meta: {
					// datetime: '2015-09-07 12:11:26'
				// },
				// body: {
					// author: 'Mati',
					// text: 'Hey, ??a va ?',
				// }
			// },
			// {
				// author: 'Someone',
				// meta: {
					// datetime: '2015-09-07 12:12:13'
				// },
				// body: {
					// author: 'Someone',
					// text: 'Oui et toi ?',
				// }
			// },
			// {
				// author: 'you',
				// meta: {
					// datetime: '2015-09-07 12:12:57'
				// },
				// body: {
					// author: 'Mati',
					// text: 'Super ! <3',
				// }
			// }
		// ];
		// channels[5842] = [
			// {
				// author: 'you',
				// meta: {
					// datetime: '2015-09-07 11:44:51'
				// },
				// body: {
					// author: 'Mati',
					// text: 'Hey',
				// }
			// },
			// {
				// author: 'Otherone',
				// meta: {
					// datetime: '2015-09-07 11:45:35'
				// },
				// body: {
					// author: 'Otherone',
					// text: 'Hey, tu fais quoi ?',
				// }
			// },
			// {
				// author: 'you',
				// meta: {
					// datetime: '2015-09-07 11:46:27'
				// },
				// body: {
					// author: 'Mati',
					// text: 'Je code des trucs.',
				// }
			// },
			// {
				// author: 'Otherone',
				// meta: {
					// datetime: '2015-09-07 11:48:54'
				// },
				// body: {
					// author: 'Otherone',
					// text: 'Cool :3',
				// }
			// }
		// ];
		
		// if(channel_id == 0) {
			// return channels;
		// }
		// else {
			// return channels[channel_id];
		// }
	// }

	// /** Chat App: Get Channels List */
	// function getChannelList() {
		// var channels = getChannelMessages();
		// var channelsList = []
		// $.each(channels, function(index, eachChannel) {
			// $.each(eachChannel, function(index, eachMessage) {
				// channelsList[index] = eachMessage;
			// });
		// });
		// return channelsList;
	// }

	// /** Chat App: Open Side Menu */
	// $('.content[app="ChatApp"] .header .menu-toggle').click(function() {
		// alert('Side Menu cannot be openned yet.')
	// });

	// /** Chat App: Return (back to home tab) */
	// $('.content[app="ChatApp"] .header .go-home-tab').click(function() {
		// // alert('Go Home Tab cannot be used yet.');
		
		// $(this).css({
			// display: 'none'
		// });
		// $('.content[app="ChatApp"] .header .menu-toggle').css({
			// display: 'block'
		// });
		// $('.content[app="ChatApp"] .header .title').text('Chat App');
		
		// $('.content[app="ChatApp"] .app-tab').css({
			// display: 'none'
		// });
		// $('.content[app="ChatApp"] .app-tab[home-tab="true"]').css({
			// display: 'block'
		// });
	// });

	// /** Chat App: Open channel */
	// $('.content[app="ChatApp"] .app-tab#channel-list .channel-link').click(function() {
		// $('.content[app="ChatApp"] .app-tab').css({
			// display: 'none'
		// });
		
		// $('.content[app="ChatApp"] .header .menu-toggle').css({
			// display: 'none'
		// });
		// $('.content[app="ChatApp"] .header .go-home-tab').css({
			// display: 'block'
		// });
		// $('.content[app="ChatApp"] .header .title').text('Chat: ' + $(this).attr('channel-id'));
		
		// $('.content[app="ChatApp"] .app-tab#chat-box').css({
			// display: 'block'
		// });
		// $('.content[app="ChatApp"] .app-tab#chat-box').attr('channel-id', $(this).attr('channel-id'));
		
		// $('.content[app="ChatApp"] .app-tab#chat-box .messages-zone').empty();
		// $('.content[app="ChatApp"] .app-tab#chat-box .messages-zone').append(
			// $('<div>').addClass('refresh-icon').append(
				// $('<i>').addClass('fa fa-refresh fa-spin')
			// )
		// );
		
		// var messages = getChannelMessages($(this).attr('channel-id'));
		
		// $('.content[app="ChatApp"] .app-tab#chat-box .messages-zone').empty();
		// $.each(messages, function(index, eachMessage) {
			// var datePost = new Date(eachMessage.meta.datetime);
			// $('.content[app="ChatApp"] .app-tab#chat-box .messages-zone').append(
				// $('<article>').addClass('message').attr('author', eachMessage.author).append(
					// $('<div>').addClass('meta').append(
						// $('<time>').attr('datetime', eachMessage.meta.datetime).text(datePost.getHours() + ':' + datePost.getMinutes())
					// )
				// ).append(
					// $('<div>').addClass('body').append(
						// $('<div>').addClass('author').text(eachMessage.body.author)
					// ).append(
						// $('<div>').addClass('text').text(eachMessage.body.text)
					// )
				// )
			// );
		// });
	// });

	// /** Chat App: Send New Message */
	// $('.content[app="ChatApp"] .app-tab#chat-box > form.new-message').submit(function(e) {
		// e.preventDefault();
		
		// if($(this).find('.message-input').val() == '') {
			// alert('error: message empty');
		// }
		// else {
			// var datetime = new Date;
			
			// $('.content[app="ChatApp"] .app-tab#chat-box .messages-zone').append(
				// $('<article>').addClass('message').attr({
					// author: 'you'
				// }).append(
					// $('<div>').addClass('meta').append(
						// $('<text>').attr({
							// datetime: datetime.getFullYear() + '-' + (datetime.getMonth() + 1) + '-' + datetime.getDate() + ' ' + datetime.getHours() + ':' + datetime.getMinutes() + ':' + datetime.getSeconds()
						// }).text(datetime.getHours() + ':' + datetime.getMinutes())
					// )
				// ).append(
					// $('<div>').addClass('body').append(
						// $('<div>').addClass('author').text('Mati')
					// ).append(
						// $('<div>').addClass('text').text($(this).find('.message-input').val())
					// )
				// )
			// );
			// $('.content[app="ChatApp"] .app-tab#chat-box .messages-zone').scrollTop($('.content[app="ChatApp"] .app-tab#chat-box .messages-zone').prop('scrollHeight'));
			// $(this).find('.message-input').val('');
		// }
	// });
	
// }(jQuery, Mobile));