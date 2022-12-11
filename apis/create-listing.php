<?php
session_start();
include "../snippets/data-encoder.php";

if (isset($_POST["user_id"]) 
    && isset($_POST["city"])
    && isset($_POST["state"]) 
    && isset($_POST["condition"]) 
    && isset($_POST["price"]) 
    && isset($_POST["quantity"]) 
    && isset($_POST["description"])  
    && isset($_POST["photo_source"]) 
    && isset($_POST["start_date"])) {

    $user_id = decode($_POST["user_id"]);
    $city = $_POST["city"];
    $state = $_POST["state"];
    $condition = $_POST["condition"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $description = $_POST["description"];
    $photo_source = $_POST["photo_source"];
    $start_date = $_POST["start_date"];
} else {
    echo "No data provided.";
    return;
}

require_once("../snippets/get-pdo-connection.php");

$db = get_pdo_connection();
$query = $db->prepare("CALL createListing(?, ?, ?, ?, ?, ?, ?, ?, ?)");

$query->bindParam(1, $user_id, PDO::PARAM_INT);
$query->bindParam(2, $city, PDO::PARAM_STR);
$query->bindParam(3, $state, PDO::PARAM_STR);
$query->bindParam(4, $condition, PDO::PARAM_STR);
$query->bindParam(5, $price, PDO::PARAM_STR);
$query->bindParam(6, $description, PDO::PARAM_STR);
$query->bindParam(7, $start_date, PDO::PARAM_STR);
$query->bindParam(8, $quantity, PDO::PARAM_INT);
$query->bindParam(9, $photo_source, PDO::PARAM_STR);

if ($query->execute()) {
    echo "Listing created successfully!";
} else {
    echo "Failed to create listing!";
}
?>