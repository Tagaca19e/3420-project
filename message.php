<?php
$db = get_mysqli_connection();

// TODO(etagaca): Connect logged in session from Danny's logged in feature.
// Test for who is currently logged in.
$userLoggedInId = 3;

// Get all messages that user has sent.
$query = $db->prepare("SELECT * FROM Messages WHERE senderID = $userLoggedInId");
$query->execute();

$result = $query->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);

$userMessages = array();

foreach ($rows as $row) {
    $receiverId = $row["receiverId"]; 

    if (!array_key_exists($receiverId, $userMessages)) {
        // Create a list of users that user has sent a message to.
        $userMessages[$receiverId] = array();
    }

    $messageInfo = array();

    // Append message information.
    $messageInfo["message"] = $row["textMessage"];
    $messageInfo["messageId"] = $row["messageId"];

    // Track who sent the message.
    $messageInfo["senderId"] = $userLoggedInId;
    array_push($userMessages[$receiverId], $messageInfo);
}

// Get all messages that user has received.
$query = $db->prepare("SELECT * FROM Messages WHERE receiverID = $userLoggedInId");
$query->execute();

$result = $query->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);

foreach ($rows as $row) {
    $senderId = $row["senderId"];
    $messageInfo = array();

    // Append message information.
    $messageInfo["message"] = $row["textMessage"];
    $messageInfo["messageId"] = $row["messageId"];

    // Track who sent the message.
    $messageInfo["senderId"] = $senderId;
    array_push($userMessages[$senderId], $messageInfo);
}

// Sort message by messageId for user experience.
foreach ($userMessages as $idx => $receiverMessages ) {
    $sortedMessages = array_column($receiverMessages, "messageId");
    array_multisort($sortedMessages, SORT_ASC, $receiverMessages);

    $userMessages[$idx] = $receiverMessages; 
}

function displayMessage($userMessages) {
    foreach ($userMessages as $message) {
        echo $message["senderId"] . ": " . $message["message"] . "<br>";
        echo "<br>";
    }
}

foreach ($userMessages as $key => $messages) {
    echo "From user id: " . $key . "<br>";
    displayMessage($messages);
    echo "=========================================<br>";
}

?>