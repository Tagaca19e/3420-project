// TODO(etagaca): Get logged in user id by session.
// Constant user id for test purposes.
var LOGGEDIN = 3;

// Displays user messages with AJAX.
function displayUserMessage(toDisplay) {
  $.ajax({
    type: "POST",
    url: '../apis/display-message.php',
    data: {
      logged_in: LOGGEDIN,
      to_display: toDisplay
    },
    success: function(data) {
      $('.tradespace__messages-inner').html(data);
      $('#message-text').attr('data-id', toDisplay)
    },
    error: function(xhr, status, error) {
      console.error(xhr);
    }
  });
}

function sendMessage(senderId, receiverId, textMessage) {
  $.ajax({
    type: "POST",
    url: '../apis/send-message.php',
    data: {
      sender_id: senderId,
      receiver_id: receiverId,
      message: textMessage
    },
    success: function(data) {
      // Update messages container.
      displayUserMessage(receiverId);
    },
    error: function(xhr, status, error) {
      console.error(xhr);
    }
  });
}

// Load messages of top user from list of messages.
var init = $('.list-item__user').attr('data-key')[0];
displayUserMessage(init);

/**
 * ---------------------------------------------------------------------------
 * Event listeners
 * ---------------------------------------------------------------------------
 */

$(document).ready(function () {
  $('.list-item__user').click(function () {
    displayUserMessage($(this).attr('data-key'));
  });
  
  $('#message-text').keypress(function (event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == '13') {
      var message = $(this).val();
      var receiverId = $('#message-text').attr('data-id');

      sendMessage(LOGGEDIN, receiverId, message);
      $(this).val('');
    }
  });
});