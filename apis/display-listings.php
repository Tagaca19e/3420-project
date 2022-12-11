<?php
session_start();

// Check for logged in user.
if (!isset($_SESSION["user_id"]) && !isset($_SESSION["username"])) {
    return;
}

// Use PDO since we cannot access Table Views in sql with mysqli.
require_once("../snippets/get-pdo-connection.php");

$db = get_pdo_connection();
$sql = "SELECT * FROM UserListingsInfo";

$query = $db->prepare($sql);
$query->execute();

$rows = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
    // Show only available listings.    
    if (empty($row["endDate"])) {
        $user_id = $row["userId"];
        $username = $row["username"];
        $user_rating = (empty($row["userRating"]) ? 0 : $row["userRating"]);
        $city = $row["city"];
        $state = $row["state"];
        $condition = $row["bookCondition"];
        $price = $row["price"];
        $quantity = $row["quantity"];
        $description = $row["description"];
        $photo_source = $row["photoSource"];

        // Render each listing with item-listing template.
        include "../snippets/item-listing.php";
    }
}
?>