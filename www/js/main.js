$(function() {
	waitForMsg();
});

function waitForMsg() {
	$.ajax({
		type: 'GET',
		url: pollUrl,
		async: true,
		cache: false,
	 
		success: function(json) {
			console.log(json);

			if (json['mine']) {
				var $newItem = $($('.media.item-template').clone()).removeClass('item-template');
				$newItem.addClass(json['mine']);
				$('#chat').append($newItem).fadeIn('slow');

				$('html, body').animate({ scrollTop: $($newItem).offset().top + 20 }, 100);
			}
			setTimeout("waitForMsg()",2000);
		},
		error: function(XMLHttpRequest,textStatus,errorThrown) {
			// alert("error: "+textStatus + " "+ errorThrown );
			setTimeout("waitForMsg()",2000);
		}
	});
}

/* new room */
$('#new-room').on('click', function(e) {
	$.ajax({
		type: 'POST',
		url: $(e.target).attr('data-url'),
		async: true,
		cache: false,
		data: {'name': $('#new-room-name').val()},
		success: function(json) {
			var $newItem = $($('.room.item-template').clone()).removeClass('item-template');
			$newItem.find('a').html(json['name']); // name
			$newItem.find('a').attr('href', json['url']); // url
			$('#roomList').after($newItem);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert("error: "+textStatus + " "+ errorThrown );
		}
	});
});

$('.sidebar').on('click', '.room', function(e) {
	e.preventDefault();

	$.ajax({
		type: 'GET',
		url: $(e.target).attr('href'),
		async: true,
		cache: false,
		// data: {'id': $('#new-room-name').val()},
		success: function(json) {
			console.log(json);
		},
		error: function(XMLHttpRequest,textStatus,errorThrown) {
			// alert("error: "+textStatus + " "+ errorThrown );
		}
	});
});
