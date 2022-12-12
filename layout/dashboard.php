<?php
session_start();
require_once("../snippets/get-pdo-connection.php");
require_once("../snippets/get-mysqli-connection.php");
require_once("../snippets/data-encoder.php");
require_once("../snippets/get-user-rating.php");
require_once("../config/user-admins.php");

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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
        <script src="https://js.upload.io/uploader/v2"></script>
    </head>
    <body data-key="<?= encode($_SESSION["user_id"]) ?>">
        <div class="nav">
            <ul class="nav__primary-items">
                <?php
                if (in_array($_SESSION["username"], $_SESSION["admins"])) {
                    echo "<li class='nav__item' data-key='analytics'>Analytics</li>";
                }
                ?>
                <li class="nav__item" data-key="create">Create</li>
                <li class="nav__item" data-key="all-listings">Explore</li>
                <li class="nav__item" data-key="my-listings">My Listings</li>
                <li class="nav__item" data-key="logout">Logout</li>
                <?php
                $db = get_pdo_connection();
                $query = $db->prepare("SELECT * from UserCount");
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_ASSOC);
                echo "<li>Users: " . $results[0]["users"] . "</li>";
                
                ?>
            </ul>
        </div>

        <div class="tradespace__wrapper">
            <div>
                <div class="tradespace__user-profile">
                    <h3><?= $_SESSION["username"] ?></h3>
                    <h4><?= get_user_rating($_SESSION["user_id"]) ?></h4>
                    <a href = "account.php">account</a>
                    <!--
                    NOTE: Not able to implemet due to lack of time :(
                    <a class="user-profile__pswd">change password</a>
                    <input type="password" name="new_password">
                    <p></p>
                    -->
                </div>
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