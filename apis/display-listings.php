<?php
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
        $userId = $row["userId"];
        $username = $row["username"];
        $user_rating = (empty($row["userRating"]) ? 0 : $row["userRating"]);
        $city = $row["city"];
        $state = $row["state"];
        $condition = $row["bookCondition"];
        $price = $row["price"];
        $description = $row["description"];

        // Render each listing with item-listing template.
        include "../snippets/item-listing.php";
    }
}
?>