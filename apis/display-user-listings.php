<?php
if (isset($_POST["user_id"])) {
    $user_logged_in_id = $_POST["user_id"];
} else {
    echo "No data provided.";
    return;
}

// Use PDO since we cannot access Table Views in sql with mysqli.
require_once("../snippets/get-pdo-connection.php");

$db = get_pdo_connection();
$sql = "SELECT * 
        FROM UserListingsInfo 
        WHERE userId = $user_logged_in_id
        AND endDate IS NULL";

$query = $db->prepare($sql);
$query->execute();

$rows = $query->fetchAll(PDO::FETCH_ASSOC);

if (empty($rows)) {
    echo "You have no listings at the moment :(";
} else {
    foreach ($rows as $row) {
        // Show only user listings that are active.    
        if (empty($row["endDate"])) {
            $userId = $row["userId"];
            $username = $row["username"];
            $userRating = (empty($row["userRating"]) ? 0 : $row["userRating"]);
            $city = $row["city"];
            $state = $row["state"];
            $condition = $row["bookCondition"];
            $price = $row["price"];
            $description = $row["description"];
            
            // Render each listing with item-listing template.
            include "../snippets/item-listing.php";
        }
    }
}
?>