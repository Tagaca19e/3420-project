<?php
session_start();
include "../snippets/data-encoder.php";

if (isset($_POST["city"]) && isset($_POST["state"]) 
    && isset($_POST["condition"]) && isset($_POST["price"]) 
    && isset($_POST["description"])) {
    $user_id = decode($_POST["user_id"]);
    $listing_id = $_POST["listing_id"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $condition = $_POST["condition"];
    $price = $_POST["price"];
    $description = $_POST["description"];
} else {
    echo "No data provided.";
    return;
}

require_once("../snippets/get-pdo-connection.php");

$db = get_pdo_connection();
$query = $db->prepare("CALL updateListingsInfo(?, ?, ?, ?, ?, ?, ?)");

$query->bindParam(1, $user_id, PDO::PARAM_INT);
$query->bindParam(2, $listing_id, PDO::PARAM_INT);
$query->bindParam(3, $city, PDO::PARAM_STR);
$query->bindParam(4, $state, PDO::PARAM_STR);
$query->bindParam(5, $condition, PDO::PARAM_STR);
$query->bindParam(6, $price, PDO::PARAM_STR);
$query->bindParam(7, $description, PDO::PARAM_STR);

if ($query->execute()) {
    echo "Update success!";
} else {
    echo "Update failed!";
}
?>