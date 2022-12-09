<?php
if (isset($_POST["logged_in"]) && isset($_POST["to_display"])) {
    $user_logged_in_id = $_POST["logged_in"];
    $user_id_to_display = $_POST["to_display"];
} else {
    echo "No data provided.";
    return;
}

require_once("../snippets/get-mysqli-connection.php");
$db = get_mysqli_connection();

// TODO(etagaca): Connect logged in session from Danny's logged in feature.
// Test for who is currently logged in.

// Get all messages that user has sent.
$query = $db->prepare("SELECT * FROM Messages WHERE senderID = $user_logged_in_id");
$query->execute();

$result = $query->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);

$user_messages = array();

foreach ($rows as $row) {
    $receiver_id = $row["receiverId"]; 

    if (!array_key_exists($receiver_id, $user_messages)) {
        // Create a list of users that user has sent a message to.
        $user_messages[$receiver_id] = array();
    }

    $message_info = array();

    // Append message information.
    $message_info["message"] = $row["textMessage"];
    $message_info["messageId"] = $row["messageId"];

    // Track who sent the message.
    $message_info["senderId"] = $user_logged_in_id;
    array_push($user_messages[$receiver_id], $message_info);
}

// Get all messages that user has received.
$query = $db->prepare("SELECT * FROM Messages WHERE receiverID = $user_logged_in_id");
$query->execute();

$result = $query->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);

foreach ($rows as $row) {
    $sender_id = $row["senderId"];
    $message_info = array();

    // Append message information.
    $message_info["message"] = $row["textMessage"];
    $message_info["messageId"] = $row["messageId"];

    // Track who sent the message.
    $message_info["senderId"] = $sender_id;
    array_push($user_messages[$sender_id], $message_info);
}

// Sort message by messageId for user experience.
foreach ($user_messages as $idx => $receiver_messages) {
    $sorted_messages = array_column($receiver_messages, "messageId");
    array_multisort($sorted_messages, SORT_ASC, $receiver_messages);

    $user_messages[$idx] = $receiver_messages; 
}

include '../snippets/get-username-by-id.php';

// Display where the message is to/from.
echo "<p 
        class='tradespace__message-header'
      >To. " 
      . get_username_by_id($user_id_to_display) . 
      "</p>";

function display_message($user_messages) {
    foreach ($user_messages as $message) {
        echo "<p>" . get_username_by_id($message["senderId"]) . ": " . $message["message"] . "</p>";
    }
}
display_message($user_messages[$user_id_to_display]);
?>