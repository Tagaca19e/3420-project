<?php
session_start();
include "../snippets/data-encoder.php";

if (isset($_POST["city"]) 
    && isset($_POST["state"]) 
    && isset($_POST["condition"]) 
    && isset($_POST["price"]) 
    && isset($_POST["description"])
    && isset($_POST["quantity"])
    && isset($_POST["user_id"])
    && isset($_POST["end_date"])
    && isset($_POST["photo_source"])
    && isset($_POST["listing_id"])) {

    $user_id = decode($_POST["user_id"]);
    $listing_id = $_POST["listing_id"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $condition = $_POST["condition"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $description = $_POST["description"];
    $photo_source = $_POST["photo_source"];
    $end_date = $_POST["end_date"];
} else {
    echo "No data provided.";
    return;
}

// Flag for checking admins.
$admin = false;
if (in_array($_SESSION["username"], $_SESSION["admins"])) {
    $admin = true;
} 

// Validate logged in user id. No need to validate for admins since admins have
// all access to database.
if ($user_id != $_SESSION["user_id"] && !$admin) {
    echo "Request invalid!";
    return;
}

require_once("../snippets/get-pdo-connection.php");

$db = get_pdo_connection();

if ($admin) {
    $query = $db->prepare("CALL adminUpdateListingsInfo(?, ?, ?, ?, ?, ?, ?, ?, ?)");
} else {
    $query = $db->prepare("CALL updateListingsInfo(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
}

// Move order of the sql procedure paramters depending on user priviledges.
// updateListings info is the same as 'adminUpdateListingsInfo' but user_id parameter
// is removed from admin procedure which has a position of 1. Implemetation was done
// to follow 'DRY' principle.
$i = ($admin) ? 0 : 1;
if ($i) {
    $query->bindParam($i, $user_id, PDO::PARAM_INT);
}
$query->bindParam($i + 1, $listing_id, PDO::PARAM_INT);
$query->bindParam($i + 2, $city, PDO::PARAM_STR);
$query->bindParam($i + 3, $state, PDO::PARAM_STR);
$query->bindParam($i + 4, $condition, PDO::PARAM_STR);
$query->bindParam($i + 5, $price, PDO::PARAM_STR);
$query->bindParam($i + 6, $quantity, PDO::PARAM_INT);
$query->bindParam($i + 7, $description, PDO::PARAM_STR);
$query->bindParam($i + 8, $photo_source, PDO::PARAM_STR);

// Use PARAM_NULL for empty/null end dates.
if (empty($end_date)) {
    $query->bindParam($i + 9, $end_date, PDO::PARAM_NULL);
} else {
    $query->bindParam($i + 9, $end_date, PDO::PARAM_STR);
}

if ($query->execute()) {
    echo "Update success!";
} else {
    echo "Update failed!";
}
?>