<?php
require_once("../snippets/get-mysqli-connection.php");
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $PROJECT_NAME ?></title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</head>
<body>
<h1><?= $PROJECT_NAME?></h1>

<div class="tradespace__wrapper">
    <div class="tradespace__message-wrapper">
        <div class="tradespace__list">
            <?php
            require_once("../sections/message.php");
            ?>
        </div>

        <div class="tradespace__messages">
            <div class="tradespace__messages-inner"></div>
            <input type="text" id="message-text" placeholder="Enter message">
        </div>
    </div>
    <div class="tradespace__listing-wrapper">
        <?php
        require_once("../sections/listing.php")
        ?>
    </div>
</div>