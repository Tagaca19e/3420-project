// Global user id.
var LOGGEDIN = $('body').attr('data-key');

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

      $('.listing-form__image-upload').next()
        .text(`${success.substring(0, 40)}....`);

      // Store cdn link into form.
      $('.listing-form > input[name="photo_source"]').val(fileUrls);
    },
    error => {
      alert(error);
    }
  );
}

/**
 * Inserts as listing to Listing, Book, and Photo table.
 * @param {object} data - Data to be sent to api endpoint.
 * @returns {object} - Promise whether listing was successfully inserted in
 * database.
 */
function createListing(data) {
  return new Promise((resolve, reject) => {
    $.ajax({
      type: "POST",
      url: '../apis/create-listing.php',
      data: data,
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
 * Updates user password.
 * @param {string} pswd - New password to be inserted in db. 
 * @returns {object} - Promise whether update was successful or not.
 */
function updatePswd(pswd) {
  return new Promise((resolve, reject) => {
    if (pswd == '') {
      reject('passwords cant be empty');
      return;
    }
    $.ajax({
      type: "POST",
      url: '../apis/update-password.php',
      data: {
        new_password: pswd
      },
      success: function (data) {
        console.log(data);
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

function displayAnalytics() {
  return new Promise((resolve, reject) => {
    $.ajax({
      type: "POST",
      url: '../apis/display-analytics.php',
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
 * Gets data from the DOM generated by display analytics api then displays 
 * data points using a pie chart by canvas js.
 * @param {string} id - id of element in the DOM.
 * @param {string} title - title of chart.
 */
function pieChart(id, title) {
  return new Promise((resolve) => {
    var data = $(`#${id}`).attr('data-key');
    
    data = decodeURIComponent(atob(data));
    var dataArr = data.split('|');
    
    // Get encoded data from display analytics api.
    var data = [];
    dataArr.forEach(function (val) {
      if (val) {
        data.push(JSON.parse(val));
      }
    });
    
    var chart = new CanvasJS.Chart(id, {
      animationEnabled: true,
      title: {
        text: title
      },
      data: [{
        type: "pie",
        startAngle: 240,
        yValueFormatString: "##0\"\"",
        indexLabel: "{label} {y}",
        dataPoints: data
      }]
    });
    chart.render();
    resolve();
  });
}

function printReport() {
  var pdf = new jsPDF('p', 'pt', 'letter');
  source = $('#content-report')[0];
  specialElementHandlers = {
      // element with id of "bypass" - jQuery style selector
      '#bypassme': function (element, renderer) {
          // true = "handled elsewhere, bypass text extraction"
          return true
      }
  };

  margins = {
      top: 80,
      bottom: 60,
      left: 40,
      width: 522
  };

  pdf.fromHTML(
      source, // HTML string or DOM elem ref.
      margins.left, 
      margins.top, { 
          'width': margins.width,
          'elementHandlers': specialElementHandlers
      },

      function (dispose) {
          pdf.save('tradespace-report.pdf');
      }, margins
  );
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
    var init = $('.list-item__user').attr('data-key') || false;
    if (init) {
      displayUserMessage(LOGGEDIN, init[0]);
    }

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

  // Display analytics.
  $('.nav__item[data-key="analytics"]').click(function () {
    displayAnalytics().then(function () {
      pieChart('analytics__pie-chart--city', 'Listing by City')
        .then(() => {
          pieChart('analytics__pie-chart--state', 'Listing by State').then(() => {
            var canvasJs = $('.canvasjs-chart-container');

            for (var i = 0; i < canvasJs.length; i++) {
              canvasJs[i].firstChild.style.position = "unset";
            }
          });
      });
    });
  });

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
    logout().then(function () {
      window.location.replace('../layout/login.php');
    });
  });

  /**
   * --------------------------------------------------------------------------
   * Analytics
   * --------------------------------------------------------------------------
   */

  // Save report.
  $('.tradespace__listing-wrapper').click(function () {
    printReport();
  });

  /**
   * --------------------------------------------------------------------------
   * Profile
   * --------------------------------------------------------------------------
   */

  $('input[name="new_password"]').keypress(function (event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == '13') {
      var pswdForm = $(this);
      if ($(this).val() == '') {
        pswdForm.next().text('password can\' be emtpy!');
        setTimeout(function () {
          pswdForm.next().text('');
        }, 2500);
      } else {
        updatePswd($(this).val()).then(function () {
          pswdForm.next().text('password changed!');
          setTimeout(function () {
            pswdForm.next().text('');
          }, 2500);
        });
        $(this).val('');
      }
    }
  });

  /**
   * --------------------------------------------------------------------------
   * Listings
   * --------------------------------------------------------------------------
   */

  // Upload image.
  $('.tradespace__listing-wrapper').on('click', '.listing-form__image-upload', 
    function () {
      imageUpload();
  });

  $('.tradespace__listing-wrapper').on('submit', '.listing-create', 
    function (event) {
      event.preventDefault();
      // Get data from create listing form.
      const formData = new FormData($(this)[0]);
      var data = {};

      // Convert formData to a JSON object.
      formData.forEach((value, key) => data[key] = value);

      createListing(data).then(function () {
        displayUserListings(LOGGEDIN);
      });
  });

  // Edit info about listing.
  $('.tradespace__listing-wrapper').on('click', 
  '.list-item__listing-info > button', function () {
      var listingId = $(this).attr('data-key');

      $(`.listing--${listingId}`).addClass('hidden');
      $(`.listing-edit--${listingId}`).addClass('visible');
  });

  // Cancel edit listing.
  $('.tradespace__listing-wrapper').on('click', '.listing-edit__cancel', 
    function () {
      var listingId = $(this).attr('data-key');

      $(`.listing--${listingId}`).removeClass('hidden');
      $(`.listing-edit--${listingId}`).removeClass('visible');
  });

  // Submit listing updates.
  $('.tradespace__listing-wrapper').on('submit', '.listing-edit', 
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