// TODO(etagaca): Get logged in user id by session.
// Constant user id for test purposes.
// ======== TEST ONLY ======== 
var LOGGEDIN = $('body').attr('data-key');
// ===========================

/**
 * Displays user message history between logged in user and another user.
 * @param {number} userId - User id of logged in user.
 * @param {number} toDisplay - User id of messaged user.
 */
function displayUserMessage(userId, toDisplay) {
  $.ajax({
    type: "POST",
    url: '../apis/display-message.php',
    data: {
      logged_in: userId,
      to_display: toDisplay
    },
    success: function (data) {
      $('.tradespace__messages-inner').html(data);
      $('#message-text').attr('data-id', toDisplay)
    },
    error: function (xhr, status, error) {
      console.error(xhr);
    }
  });
}

/**
 * Lists out usernames that was messaged by logged in user.
 * @param {number} userId - User id of logged in user.
 * @returns {object} - Promise whether listings user messages was successful or 
 *   not.
 */
 function listMessagedUsers(userId) {
  return new Promise((resolve, reject) => {
    $.ajax({
      type: "POST",
      url: '../apis/list-message-users.php',
      data: {
        user_id: userId
      },
      success: function (data) {
        $('.tradespace__message-list').html(data);
        resolve();
      },
      error: function (xhr, status, error) {
        reject(xhr);
      }
    });
  });
}

/**
 * Inserts message to 'Messages' table, and use ajax to display it to message
 * container.
 * @param {number} senderId - User id of the sender.
 * @param {number} receiverId - User id of the receiver.
 * @param {string} textMessage - Message to be posted.
 */
function sendMessage(senderId, receiverId, textMessage) {
  $.ajax({
    type: "POST",
    url: '../apis/send-message.php',
    data: {
      sender_id: senderId,
      receiver_id: receiverId,
      message: textMessage
    },
    success: function (data) {
      displayUserMessage(LOGGEDIN, receiverId);   // Update messages container.
      listMessagedUsers(LOGGEDIN);            // Update list of messaged users.
    },
    error: function (xhr, status, error) {
      console.error(xhr);
    }
  });
}

/**
 * Displays all listings available in the database.
 * @returns {object} - Promise whether listings were displayed.
 */
function displayListings() {
  return new Promise((resolve, reject) => {
    $.ajax({
      type: "POST",
      url: '../apis/display-listings.php',
      data: {},
      success: function (data) {
        $('.tradespace__listing-wrapper').html(data);
        resolve();
      },
      error: function (xhr, status, error) {
        reject(xhr);
      }
    });
  });
}

/**
 * Displays all listings available that are listed by user.
 * @param {number} userId - User id of logged in user.
 * @returns {object} - Promise whether listings were displayed.
 */
function displayUserListings(userId) {
  return new Promise((resolve, reject) => {
    $.ajax({
      type: "POST",
      url: '../apis/display-user-listings.php',
      data: {
        user_id: userId
      },
      success: function (data) {
        $('.tradespace__listing-wrapper').html(data);
        resolve();
      },
      error: function (xhr, status, error) {
        reject(xhr);
      }
    });
  });
}

/**
 * Uploads images into upload.io and store cdn link in create form.
 */
function imageUpload() {
  const uploader = Uploader({
    apiKey: "public_FW25az36ojjQ5Ei6XgZqzT5VUdZ2" // Replace with your API key.
  });
  uploader.open({ maxFileCount: 1 }).then(
    files => {
      const fileUrls = files.map(x => x.fileUrl).join("\n");
      const success = fileUrls.length === 0 
          ? "No file selected." 
          : `File uploaded:\n\n${fileUrls}`;
      console.log(success);
      $('.listing-create__image-upload').next()
        .text(`${success.substring(0, 40)}....`);

      // Store cdn link into form.
      $('.listing-create > input[name="photo_source"]').val(fileUrls);
    },
    error => {
      alert(error);
    }
  );
}

/**
 * Displays listing template to create a listing.
 */
function displayListingTemplate() {
  return new Promise((resolve, reject) => {
    $.ajax({
      type: "POST",
      url: '../apis/display-listing-template.php',
      data: {},
      success: function (data) {
        $('.tradespace__listing-wrapper').html(data);
        resolve();
      },
      error: function (xhr, status, error) {
        reject(xhr);
      }
    });
  });
}

function createListing(data) {
  return new Promise((resolve, reject) => {
    $.ajax({
      type: "POST",
      url: '../apis/create-listing.php',
      data: data,
      success: function (data) {
        console.log('success', data);
        resolve();
      },
      error: function (xhr, status, error) {
        reject(xhr);
      }
    });
  });
}

/**
 * Updates listings with new info.
 * @param {object} - Object that represents new changes of listing.
 * @returns {object} - Promise whether listing was successfully updated or not.
 */
function updateListing(updateData) {
  return new Promise((resolve, reject) => {
    $.ajax({
      type: "POST",
      url: '../apis/update-listing.php',
      data: updateData,
      success: function (data) {
        resolve();
      },
      error: function (xhr, status, error) {
        reject(xhr);
      }
    });
  });
}

/**
 * Deletes listing given a listing id.
 * @param {number} listingId - id of the listing. 
 * @returns {object} - Promise whether listing was successfully deleted or not.
 */
function deleteListing(listingId) {
  return new Promise((resolve, reject) => {
    $.ajax({
      type: "POST",
      url: '../apis/delete-listing.php',
      data: {
        listing_id: listingId
      },
      success: function (data) {
        resolve();
      },
      error: function (xhr, status, error) {
        reject(xhr);
      }
    });
  });
}

/**
 * Sets session superglobal to an empty array then destroys session.
 * @returns {object} - Promise whether logout api was successful.
 */
function logout() {
  return new Promise((resolve, reject) => {
    $.ajax({
      type: "POST",
      url: '../apis/logout.php',
      data: {},
      success: function (data) {
        resolve();
      },
      error: function (xhr, status, error) {
        reject(xhr);
      }
    });
  });
}

/**
 * ---------------------------------------------------------------------------
 * Initial page load
 * ---------------------------------------------------------------------------
 */

// Load all messaged users.
listMessagedUsers(LOGGEDIN)
  .then(function () {
    // Load most recent user messages.
    var init = $('.list-item__user').attr('data-key')[0];
    displayUserMessage(LOGGEDIN, init);

    // Load all listings available.
    displayListings();
});

$(document).ready(function () {
  /**
   * --------------------------------------------------------------------------
   * Messages
   * --------------------------------------------------------------------------
   */

  // Display messages when username is clicked on the user messaged list.
  $('.tradespace__message-list').on('click', '.list-item__user', function () {
    displayUserMessage(LOGGEDIN, $(this).attr('data-key'));
  });

  // Display message container when user try to message user who owns listing.
  $('.tradespace__listing-wrapper').on('click', '.list-item__listing-user',
    function () {
      var userId = $(this).attr('data-key');
      displayUserMessage(LOGGEDIN, userId);
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

  /**
   * --------------------------------------------------------------------------
   * Nav bar
   * --------------------------------------------------------------------------
   */

  // Create listing.
  $('.nav__item[data-key="create"]').click(function () {
    displayListingTemplate();
  });

  // View all listings.
  $('.nav__item[data-key="all-listings"]').click(function () {
    displayListings();
  });

  // View user owned active listings.
  $('.nav__item[data-key="my-listings"]').click(function () {
    displayUserListings(LOGGEDIN);
  });

  // Logout.
  $('.nav__item[data-key="logout"]').click(function () {
    console.log('clicked');
    logout().then(function () {
      window.location.replace('../layout/login.php');
    });
  });

  /**
   * --------------------------------------------------------------------------
   * Listings
   * --------------------------------------------------------------------------
   */

  // Upload image.
  $('.tradespace__listing-wrapper').on('click', '.listing-create__image-upload', 
    function () {
      imageUpload();
  });

  $('.tradespace__listing-wrapper').on('submit', 'form[class="listing-create"]', 
    function (event) {
      event.preventDefault();
      // Get data from create listing form.
      const formData = new FormData($(this)[0]);
      var data = {};

      // Convert formData to a JSON object.
      formData.forEach((value, key) => data[key] = value);

      console.log(data);
      createListing(data).then(function () {
        displayUserListings(LOGGEDIN);
      });
  });

  // Edit info about listing.
  $('.tradespace__listing-wrapper').on('click', '.list-item__listing > button',
    function () {
      var listingId = $(this).attr('data-key');
      
      $(`.listing--${listingId}`).addClass('hidden');
      $(`.listing-edit--${listingId}`).addClass('visible');
  });

  // Submit listing updates.
  $('.tradespace__listing-wrapper').on('submit', 'form[class="listing-edit"]', 
    function (event) {
      event.preventDefault();
      // Get data from edit form.
      const formData = new FormData($(this)[0]);
      var data = {};

      // Convert formData to a JSON object.
      formData.forEach((value, key) => data[key] = value);

      data.user_id = LOGGEDIN;
      updateListing(data).then(function () {
        displayUserListings(LOGGEDIN);
      });
  });

  $('.tradespace__listing-wrapper').on('click', '.listing-edit__delete', 
    function () {
      deleteListing($(this).attr('data-key'));
      displayUserListings(LOGGEDIN);
  });
});