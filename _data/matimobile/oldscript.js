/*\
|*|
|*|  :: Mobile Emulation ::
|*|
|*|  Mobile Emulator (by Matiboux)
|*|  Created the 9th September 2015
|*|
|*|  http://data.matiboux.com/mobile/
|*|
\*/

$(document).ready(function() {
	
	var dateStart = new Date;
	var settings = [];
	var loadApp = {};
	
	/** Settings */
		/** Set Default Settings */
		settings['useMatibouxApps'] = false;
		settings['identity'] = {
			username: ''
		};
		settings['battery'] = {
			randomUpdate: false,
			updateInterval: 12000
		};
		settings['time'] = {
			randomUpdate: false,
			updateInterval: 1000
		};
	
	
	/** Load App */
		/** Home Page */
		loadApp.homepage = function() {
			$('.mobile > *').empty().append(
				$('<div>').addClass('status-bar').append(
					$('<div>').addClass('left-side').append(
						$('<div>').addClass('notifications').append(
							$('<div>').attr('id', 'twitter').append(
								$('<i>').addClass('fa fa-twitter')
							)
						).append(
							$('<div>').attr('id', 'twitter').append(
								$('<i>').addClass('fa fa-twitter')
							)
						).append(
							$('<div>').attr('id', 'facebook').append(
								$('<i>').addClass('fa fa-facebook')
							)
						).append(
							$('<div>').attr('id', 'download').append(
								$('<i>').addClass('fa fa-download')
							)
						)
					)
				).append(
					$('<div>').addClass('right-side').append(
						$('<div>').addClass('signals').append(
							$('<div>').addClass('wifi').append(
								$('<i>').addClass('fa fa-wifi')
							)
						).append(
							$('<div>').addClass('cellular').append(
								$('<i>').addClass('fa fa-signal')
							)
						)
					).append(
						$('<div>').addClass('battery').attr({
							'battery-level': '100'
						}).text('100% ').append(
							$('<i>').addClass('fa fa-battery-4')
						)
					).append(
						$('<time>').addClass('time').attr({
							datetime: dateStart.getFullYear() + '-' + ('0' + (dateStart.getMonth() + 1)).slice(-2) + '-' + ('0' + dateStart.getDate()).slice(-2) + ' ' + ('0' + dateStart.getHours()).slice(-2) + ':' + ('0' + dateStart.getMinutes()).slice(-2) + ':' + ('0' + dateStart.getSeconds()).slice(-2)
						}).text(('0' + dateStart.getHours()).slice(-2) + ':' + ('0' + dateStart.getMinutes()).slice(-2))
					)
				)
			).append(
				$('<div>').addClass('content').attr({
					id: 'home'
				}).append(
					$('<form>').addClass('search-form').attr({
						action: '#'
					}).append(
						$('<div>').addClass('search-group').append(
							$('<input>').addClass('query').attr({
								type: 'text',
								placeholder: 'Recherche'
							})
						).append(
							$('<button>').addClass('reset').attr({
								type: 'reset'
							}).append(
								$('<i>').addClass('fa fa-remove')
							)
						).append(
							$('<button>').addClass('submit').attr({
								type: 'submit'
							}).append(
								$('<i>').addClass('fa fa-search')
							)
						)
					)
				).append(
					$('<div>').addClass('apps').append(
						// $('<li>').addClass('open-app').attr({
							// openApp: 'ChatApp'
						// }).append(
							// $('<i>').addClass('fa fa-send')
						// ).append(
							// $('<label>').text('ChatApp')
						// )
					)
				).append(
					/** 4 Slots available */
					$('<div>').addClass('shortcuts').append( 
						$('<li>').addClass('open-app').attr({
							openApp: 'ChatApp'
						}).append(
							$('<i>').addClass('fa fa-send')
						)
					).append(
						$('<li>').addClass('open-app').attr({
							openApp: ''
						}).append(
							$('<i>').addClass('fa fa-minus')
						)
					).append(
						$('<li>').addClass('open-app').attr({
							openApp: ''
						}).append(
							$('<i>').addClass('fa fa-minus')
						)
					).append(
						$('<li>').addClass('open-app').attr({
							openApp: 'Settings'
						}).append(
							$('<i>').addClass('fa fa-cog')
						)
					)
				)
			).append(
				$('<div>').addClass('nav-bar').append(
					$('<div>').addClass('tools').append(
						$('<i>').addClass('fa fa-ellipsis-h')
					)
				).append(
					$('<div>').addClass('home').append(
						$('<i>').addClass('fa fa-home')
					)
				).append(
					$('<div>').addClass('return').append(
						$('<i>').addClass('fa fa-chevron-left')
					)
				)
			);
		}
	
	/** Setup */
		/** Hide Loading Icon */
		$('.messages .loading-icon').hide();
		
		/** Add Info Message */
		$('.messages > *').append(
			$('<div>').addClass('message message-info').append(
				$('<div>').addClass('message-icon').append(
					$('<i>').addClass('fa fa-mobile')
				)
			).append(
				$('<div>').addClass('message-content').append(
					$('<h2>').text('Template Mobile Android-like')
				).append(
					$('<p>').html(' \
					Template simulant un appariel Mobile Android <br /> \
					Resolution  480p (720x480)')
				)
			)
		);
		
		/** Prepare Mobile */
		$('.mobile > *').empty().append(
			$('<div>').addClass('content').attr({
				id: 'boot-screen'
			}).append(
				$('<div>').addClass('icon').append(
					$('<i>').addClass('fa fa-refresh fa-spin')
				)
			).append(
				$('<div>').addClass('text').text('Démarrage en cours...')
			)
		);
		
		/** Load Home Page */
		setTimeout(loadApp.homepage(), 3000);
		
		/** Update Time Function */
		var updateTime = setInterval(function() {
			var currentDate = new Date;
			$('.mobile .status-bar .time').attr({
				datetime: currentDate.getFullYear() + '-' + ('0' + (currentDate.getMonth() + 1)).slice(-2) + '-' + ('0' + currentDate.getDate()).slice(-2) + ' ' + ('0' + currentDate.getHours()).slice(-2) + ':' + ('0' + currentDate.getMinutes()).slice(-2) + ':' + ('0' + currentDate.getSeconds()).slice(-2)
			}).text(('0' + currentDate.getHours()).slice(-2) + ':' + ('0' + currentDate.getMinutes()).slice(-2));
		}, 1000);
		
		/** Update Battery Level Function */
		var updateBatteryLevel = setInterval(function() {
			if(settings['battery'].randomUpdate) {
				var currentBatteryLevel = $('.mobile .status-bar .battery').attr('battery-level');
				
				if(Math.floor((Math.random() * 2) + 1) == 1) var newBatteryLevel = currentBatteryLevel - 1;
				else var newBatteryLevel = currentBatteryLevel;
				
				if(newBatteryLevel >= 0) {
					$('.mobile .status-bar .battery').attr({
						'battery-level': newBatteryLevel
					}).text(newBatteryLevel + '% ');
					
					if(newBatteryLevel <= 20) $('.mobile .status-bar .battery').addClass('critical').append($('<i>').addClass('fa fa-battery-0'));
					else if(newBatteryLevel > 20 && newBatteryLevel <= 40) $('.mobile .status-bar .battery').append($('<i>').addClass('fa fa-battery-1'));
					else if(newBatteryLevel > 40 && newBatteryLevel <= 60) $('.mobile .status-bar .battery').append($('<i>').addClass('fa fa-battery-2'));
					else if(newBatteryLevel > 60 && newBatteryLevel <= 80) $('.mobile .status-bar .battery').append($('<i>').addClass('fa fa-battery-3'));
					else if(newBatteryLevel > 80 && newBatteryLevel <= 100) $('.mobile .status-bar .battery').append($('<i>').addClass('fa fa-battery-4'));
					
					// if(newBatteryLevel == 0 && !$('.messages .message').hasClass('low-battery')) {
						// $('.messages > *').append(
							// $('<div>').addClass('message message-error low-battery').append(
								// $('<div>').addClass('message-icon').append(
									// $('<i>').addClass('fa fa-warning')
								// )
							// ).append(
								// $('<div>').addClass('message-content').append(
									// $('<h2>').text('Batterie faible')
								// ).append(
									// $('<p>').html('')
								// )
							// )
						// );
					// }
					if(newBatteryLevel == 0 && $('.mobile .content').attr('id') != 'low-battery') {
						$('.mobile > *').empty().append(
							$('<div>').addClass('content').attr({
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
			}
			else return false;
		}, settings['battery'].updateInterval);
		
		

	var currentContentApp = 'home';

	/** Change Content App */
	function changeContentApp(currentApp, newApp) {
		var currentAppSelector = '.' + currentApp;
		
		if($(currentAppSelector).attr('app') == newApp) {
			return true;
		}
		else {
			$(currentAppSelector).css({
				display: 'none'
			});
			$(currentAppSelector).removeClass(currentApp);
			
			$('.content[app="' + newApp + '"]').css({
				display: 'block'
			});
			$('.content[app="' + newApp + '"]').addClass(currentApp);
			
			$('.content[app="' + newApp + '"] .app-tab').css({
				display: 'none'
			});
			$('.content[app="' + newApp + '"] .app-tab[home-tab="true"]').css({
				display: 'block'
			});
			$('.content[app="' + newApp + '"]').attr('current-tab', $('.content[app="' + newApp + '"] .app-tab[home-tab="true"]').attr('id'));
		}
	}

	/** -- Home -- */

	/** Home: Open App */
	$('.apps .open-app').click(function() {
		changeContentApp('current-app', $(this).attr('open-app'));
	});

	/** Home: Open Shortcut App */
	$('.shortcuts .open-app').click(function() {
		changeContentApp('current-app', $(this).attr('open-app'));
	});

	/** -- Chat App -- */

	/** Chat App: Get Channel Messages */
	function getChannelMessages(channel_id) {
		channel_id = channel_id || 0;
		var channels = [];
		
		channels[7587] = [
			{
				author: 'you',
				meta: {
					datetime: '2015-09-07 12:11:26'
				},
				body: {
					author: 'Mati',
					text: 'Hey, รงa va ?',
				}
			},
			{
				author: 'Someone',
				meta: {
					datetime: '2015-09-07 12:12:13'
				},
				body: {
					author: 'Someone',
					text: 'Oui et toi ?',
				}
			},
			{
				author: 'you',
				meta: {
					datetime: '2015-09-07 12:12:57'
				},
				body: {
					author: 'Mati',
					text: 'Super ! <3',
				}
			}
		];
		channels[5842] = [
			{
				author: 'you',
				meta: {
					datetime: '2015-09-07 11:44:51'
				},
				body: {
					author: 'Mati',
					text: 'Hey',
				}
			},
			{
				author: 'Otherone',
				meta: {
					datetime: '2015-09-07 11:45:35'
				},
				body: {
					author: 'Otherone',
					text: 'Hey, tu fais quoi ?',
				}
			},
			{
				author: 'you',
				meta: {
					datetime: '2015-09-07 11:46:27'
				},
				body: {
					author: 'Mati',
					text: 'Je code des trucs.',
				}
			},
			{
				author: 'Otherone',
				meta: {
					datetime: '2015-09-07 11:48:54'
				},
				body: {
					author: 'Otherone',
					text: 'Cool :3',
				}
			}
		];
		
		if(channel_id == 0) {
			return channels;
		}
		else {
			return channels[channel_id];
		}
	}

	/** Chat App: Get Channels List */
	function getChannelList() {
		var channels = getChannelMessages();
		var channelsList = []
		$.each(channels, function(index, eachChannel) {
			$.each(eachChannel, function(index, eachMessage) {
				channelsList[index] = eachMessage;
			});
		});
		return channelsList;
	}

	/** Chat App: Open Side Menu */
	$('.content[app="ChatApp"] .header .menu-toggle').click(function() {
		alert('Side Menu cannot be openned yet.')
	});

	/** Chat App: Return (back to home tab) */
	$('.content[app="ChatApp"] .header .go-home-tab').click(function() {
		// alert('Go Home Tab cannot be used yet.');
		
		$(this).css({
			display: 'none'
		});
		$('.content[app="ChatApp"] .header .menu-toggle').css({
			display: 'block'
		});
		$('.content[app="ChatApp"] .header .title').text('Chat App');
		
		$('.content[app="ChatApp"] .app-tab').css({
			display: 'none'
		});
		$('.content[app="ChatApp"] .app-tab[home-tab="true"]').css({
			display: 'block'
		});
	});

	/** Chat App: Open channel */
	$('.content[app="ChatApp"] .app-tab#channel-list .channel-link').click(function() {
		$('.content[app="ChatApp"] .app-tab').css({
			display: 'none'
		});
		
		$('.content[app="ChatApp"] .header .menu-toggle').css({
			display: 'none'
		});
		$('.content[app="ChatApp"] .header .go-home-tab').css({
			display: 'block'
		});
		$('.content[app="ChatApp"] .header .title').text('Chat: ' + $(this).attr('channel-id'));
		
		$('.content[app="ChatApp"] .app-tab#chat-box').css({
			display: 'block'
		});
		$('.content[app="ChatApp"] .app-tab#chat-box').attr('channel-id', $(this).attr('channel-id'));
		
		$('.content[app="ChatApp"] .app-tab#chat-box .messages-zone').empty();
		$('.content[app="ChatApp"] .app-tab#chat-box .messages-zone').append(
			$('<div>').addClass('refresh-icon').append(
				$('<i>').addClass('fa fa-refresh fa-spin')
			)
		);
		
		var messages = getChannelMessages($(this).attr('channel-id'));
		
		$('.content[app="ChatApp"] .app-tab#chat-box .messages-zone').empty();
		$.each(messages, function(index, eachMessage) {
			var datePost = new Date(eachMessage.meta.datetime);
			$('.content[app="ChatApp"] .app-tab#chat-box .messages-zone').append(
				$('<article>').addClass('message').attr('author', eachMessage.author).append(
					$('<div>').addClass('meta').append(
						$('<time>').attr('datetime', eachMessage.meta.datetime).text(datePost.getHours() + ':' + datePost.getMinutes())
					)
				).append(
					$('<div>').addClass('body').append(
						$('<div>').addClass('author').text(eachMessage.body.author)
					).append(
						$('<div>').addClass('text').text(eachMessage.body.text)
					)
				)
			);
		});
	});

	/** Chat App: Send New Message */
	$('.content[app="ChatApp"] .app-tab#chat-box > form.new-message').submit(function(e) {
		e.preventDefault();
		
		if($(this).find('.message-input').val() == '') {
			alert('error: message empty');
		}
		else {
			var datetime = new Date;
			
			$('.content[app="ChatApp"] .app-tab#chat-box .messages-zone').append(
				$('<article>').addClass('message').attr({
					author: 'you'
				}).append(
					$('<div>').addClass('meta').append(
						$('<text>').attr({
							datetime: datetime.getFullYear() + '-' + (datetime.getMonth() + 1) + '-' + datetime.getDate() + ' ' + datetime.getHours() + ':' + datetime.getMinutes() + ':' + datetime.getSeconds()
						}).text(datetime.getHours() + ':' + datetime.getMinutes())
					)
				).append(
					$('<div>').addClass('body').append(
						$('<div>').addClass('author').text('Mati')
					).append(
						$('<div>').addClass('text').text($(this).find('.message-input').val())
					)
				)
			);
			$('.content[app="ChatApp"] .app-tab#chat-box .messages-zone').scrollTop($('.content[app="ChatApp"] .app-tab#chat-box .messages-zone').prop('scrollHeight'));
			$(this).find('.message-input').val('');
		}
	});

	/** -- Nav Bar -- */

	$('.nav-bar .home').click(function() {
		changeContentApp('current-app', 'home');
	});
	
});