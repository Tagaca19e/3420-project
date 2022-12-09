
<?php
require_once("../config.php");

if (isset($_POST["sender_id"]) && isset($_POST["receiver_id"]) && isset($_POST["message"])) {
    $sender_id = $_POST["sender_id"];
    $receiver_id = $_POST["receiver_id"];
    $message = $_POST["message"];
} else {
    echo "No data provided.";
    return;
}

$date = date("Y-m-d");

echo $sender_id . "<br>";
echo $receiver_id . "<br>";
echo $message . "<br>";
echo $date . "<br>";

$db = get_mysqli_connection();

$sql = "INSERT INTO Messages (senderId, receiverId, textMessage, messageDate) VALUES (?, ?, ?, ?)";

$query = $db->prepare($sql);
$query->bind_param("iiss", $sender_id, $receiver_id, $message, $date);

$query->execute();
?>