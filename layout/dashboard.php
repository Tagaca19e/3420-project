<?php
session_start();
require_once("../snippets/get-mysqli-connection.php");
require_once("../snippets/data-encoder.php");
require_once("../snippets/get-user-rating.php");

// Validate login credentials.
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] == false
    || !isset($_SESSION["username"]) || !isset($_SESSION["user_id"])) {
    header("Location: login.php");
    return;
}
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?= $PROJECT_NAME ?></title>
        <link rel="stylesheet" href="../assets/tradespace.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script src="https://js.upload.io/uploader/v2"></script>
    </head>
    <body data-key="<?= encode($_SESSION["user_id"]) ?>">
        <div class="nav">
            <ul class="nav__primary-items">
                <li class="nav__item">Account</li>
                <li class="nav__item" data-key="create">Create</li>
                <li class="nav__item" data-key="all-listings">Explore</li>
                <li class="nav__item" data-key="my-listings">My Listings</li>
                <li class="nav__item" data-key="logout">Logout</li>
            </ul>
        </div>

        <!-- TODO(etagaca): Change names for classes. -->
        <div class="tradespace__wrapper">
            <div class="tradespace__user-profile">
                <h3><?= $_SESSION["username"] ?></h3>
                <h4><?= get_user_rating($_SESSION["user_id"]) ?></h4>
            </div>

            <!-- List of listings -->
            <div class="tradespace__listing-wrapper"></div>

            <div class="tradespace__message-wrapper">
                <!-- List of messaged users -->
                <div class="tradespace__message-list"></div>

                <div class="tradespace__messages">
                    <!-- Main messages -->
                    <div class="tradespace__messages-inner"></div>
                    <input 
                        type="text" 
                        id="message-text" 
                        placeholder="Enter message"
                    >
                </div>
            </div>
        </div>

        <!-- Load apis -->
        <script src="../assets/tradespace.js"></script>
    </body>
</html>