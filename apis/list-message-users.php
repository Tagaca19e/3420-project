<?php
session_start();
include "../snippets/data-encoder.php";

if (isset($_POST["user_id"])) {
    $user_logged_in_id = decode($_POST["user_id"]);

    // Check for user logged in validity.
    if ($user_logged_in_id != $_SESSION["user_id"]) {
        echo "Error invalid request.";
        return;
    }
} else {
    echo "No data provided.";
    return;
}

require_once("../snippets/get-mysqli-connection.php");

$db = get_mysqli_connection();

// Get all messages that user has sent.
$query = $db->prepare("SELECT * FROM Messages WHERE senderID = $user_logged_in_id");
$query->execute();

$result = $query->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);

$messaged_user_ids = array();

foreach ($rows as $row) {
    $receiver_id = $row["receiverId"]; 

    if (!array_key_exists($receiver_id, $messaged_user_ids)) {
        $messaged_user_ids[$receiver_id] = true;
    }
}

include '../snippets/get-username-by-id.php';

// Display list of messaged users from recent to oldest.
foreach (array_reverse($messaged_user_ids, true) as $user_id => $value) {
    echo "<div
            class='list-item__user'
            data-key='$user_id'>" 
            . get_username_by_id($user_id) . "</div>";
}
?>