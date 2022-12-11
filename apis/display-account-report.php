<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    return;
}

// Use PDO since we cannot access Table Views in sql with mysqli.
require_once("../snippets/get-pdo-connection.php");

$db = get_pdo_connection();
$sql = "SELECT * FROM MostListings";

$query = $db->prepare($sql);
$query->execute();

$rows = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
    // Show only available listings.    
        $username = $row["username"];
        $listingcount = $row["numberOfListings"];
        

}
$sql = "SELECT COUNT(userId) AS count
from User;";

$query = $db->prepare($sql);
$query->execute();

$rows = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
    // Show only available listings.    
        $usernumber = $row["count"];
}

$sql = "SELECT * FROM ListingPriceInfo;";

$query = $db->prepare($sql);
$query->execute();

$rows = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
    // Show only available listings.    
        $listingnum = $row["listingcount"];
        $highestprice = $row["highestprice"];
        $lowestprice = $row["lowestprice"];
        $averageprice = $row["averageprice"];
}
include "../snippets/user-report.php";
?>
