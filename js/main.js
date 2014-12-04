$(function() {
	waitForMsg(); // notifications
	
	if (localStorage.getItem('hash') === null) {
	  getHash();
	}
	getRooms();
	$(window).on("hashchange", handleHashChange);
    $(window).trigger('hashchange');
});

function handleHashChange() {
	var hash = window.location.hash.substring(1);
	$('#chat').html('');
	$('.room a').removeClass('active');
    switch (hash.length) {
        case 0:
            break;
        default:
        	$('.room a[href=' + window.location.hash + ']').addClass('active');
        	getMessagesForRoom(hash);
            break;
    }
}

/* new room */
$('#new-room').on('click', function(e) {
	$.ajax({
		type: 'POST',
		url: 'http://via.kopriva.net/chat/room',
		dataType: 'json',
		// dataType: "application/x-www-form-urlencoded; charset=utf-8",
		headers: {'Accept': 'application/json', 'Content-Type': 'application/json'},
		data: JSON.stringify({name: $('#new-room-name').val()}),
		success: function(json) {
			var $newItem = $($('.room.item-template').clone()).removeClass('item-template');
			$newItem.find('a').html(json['name']); // name
			$newItem.find('a').attr('href', '#' + json['id']); // url
			$('#roomList').after($newItem);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log("error: " + textStatus + " " + errorThrown );
		}
	});
});

/**
 * Sync for new data
 */
function waitForMsg() {
	$.ajax({
		type: 'GET',
		url: 'http://via.kopriva.net/chat/update',
		async: true,
		headers: {'Accept': 'application/json'},
		success: function(json) {
			if (json == true) {
				getRooms();
				getMessagesForRoom(window.location.hash.substring(1), localStorage.getItem('message-count'));

			}
			waitForMsg();
		},
		error: function(XMLHttpRequest,textStatus,errorThrown) {
			console.log(XMLHttpRequest, textStatus, errorThrown );
			waitForMsg();
		}
	});
}



/**
 * Get all available rooms
 */
function getRooms() {
	$.ajax({
		type: 'GET',
		url: 'http://via.kopriva.net/chat/room',
		async: true,
		headers: {'Accept': 'application/json'},
		success: function(json) {
			$('#roomList').nextAll('li').remove();
			for (row in json) {
				var $newItem = $($('.room.item-template').clone()).removeClass('item-template');
				$newItem.find('a').html(json[row]['name']); // name
				$newItem.find('a').attr('href', '#' + json[row]['id']); // url
				$('#roomList').after($newItem);
			}
			$('.room a[href=' + window.location.hash + ']').addClass('active');
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log("error: " + textStatus + " " + errorThrown );
		}
	});
}

function getMessagesForRoom(roomId, offset) {
	offset = offset | 0;
	console.log('offset: ' + offset);
	$.ajax({
		type: 'GET',
		url: 'http://via.kopriva.net/chat/message/' + roomId,
		async: true,
		headers: {'Accept': 'application/json', 'Content-Type': 'application/json', 'X-Order': 'ASC', 'X-Limit': 10, 'X-Offset': offset},
		success: function(json) {
			localStorage.setItem('message-count', (+offset + +json.length));
			if (json.length > 0) {
				if (offset == 0) {
					$('#chat').html('');
				}
				for (row in json) {
					var $newItem = $($('.media.item-template').clone()).removeClass('item-template');
					$newItem.find('p').html(json[row]['message']);
					$newItem.find('span.author').html(json[row]['user_hash'].substring(32));
					$newItem.find('.timeago').attr('datetime', json[row]['created']);
					$newItem.find('.timeago').html(json[row]['created']);
					if (localStorage.getItem('hash') == json[row]['user_hash']) {
						$newItem.addClass('mine');
					}
					$('#chat').append($newItem).fadeIn('slow');

					$('html, body').animate({ scrollTop: $($newItem).offset().top + 20 }, 100);
				}
				$(".timeago").timeago();
			} else if(offset == 0) {
				$('#chat').append($('<p class="lead text-muted text-center">No results.</p>')).fadeIn('slow');
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log("error: " + textStatus + " " + errorThrown );
		}
	});
}
/**
 * Get user's hash
 */
function getHash() {
	$.ajax({
		type: 'GET',
		url: 'http://via.kopriva.net/chat/user',
		async: true,
		headers: {'Accept': 'application/json'},
		success: function(json) {
			localStorage.setItem('hash', json['user_login']);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log("error: " + textStatus + " " + errorThrown );
		}
	});
}

/* new room */
$('#message-send').on('click', function(e) {
	$.ajax({
		type: 'POST',
		url: 'http://via.kopriva.net/chat/message/' + window.location.hash.substring(1),
		async: true,
		cache: false,
		headers: {'Accept': 'application/json', 'Content-type': 'application/json'},
		data: JSON.stringify({user_hash: localStorage.getItem('hash'), message: $('#input').val()}),
		success: function(json) {
			$('#input').val('');
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log("error: " + textStatus + " " + errorThrown );
		}
	});
});

