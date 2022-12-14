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

// Use PDO since we cannot access Table Views in sql with mysqli.
require_once("../snippets/get-pdo-connection.php");

$db = get_pdo_connection();

// Check for admin privileges. Enables edit for all listings when user is
// in session admins list.
if (in_array($_SESSION["username"], $_SESSION["admins"])) {
    $sql = "SELECT * FROM UserListingsInfo";
} else {
    $sql = "SELECT * 
        FROM UserListingsInfo 
        WHERE userId = ?
        AND endDate IS NULL";
}

$query = $db->prepare($sql);
$query->bindParam(1, $user_logged_in_id, PDO::PARAM_INT);

$query->execute();

$rows = $query->fetchAll(PDO::FETCH_ASSOC);

if (empty($rows)) {
    echo "You have no listings at the moment :(";
} else {
    $idx = count($rows);
    while ($idx) {
    $row = $rows[--$idx];
        // Show only user listings that are active.    
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
            $listing_id = $row["listingId"];
            $photo_source = $row["photoSource"];
            
            // Render each listing with item-listing template.
            include "../snippets/item-listing-edit.php";
        }
    }
}
?>