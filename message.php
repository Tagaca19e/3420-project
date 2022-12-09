<?php
$db = get_mysqli_connection();

// TODO(etagaca): Connect logged in session from Danny's logged in feature.
// Test for who is currently logged in.
$user_logged_in_id = 3;

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

include './snippets/get-username-by-id.php';

foreach ($messaged_user_ids as $user_id => $value) {
    echo "<div
            class='list-item__user'
            data-key='$user_id'>" 
            . get_username_by_id($user_id) . "</div>";
}

?>

<!-- Load api to display messages -->
<script src="./assets/message.js"></script>