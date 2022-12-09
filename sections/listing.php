<?php
// Use PDO since we cannot access Table Views in sql with mysqli.
require_once("../snippets/get-pdo-connection.php");
require_once("../snippets/table-maker.php");

$db = get_pdo_connection();
$sql = "SELECT * FROM UserListingsInfo";

$query = $db->prepare($sql);
$query->execute();

$rows = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
    // Show only available listings.    
    if (empty($row["endDate"])) {
        $username = $row["username"];
        $userRating = (empty($row["userRating"]) ? 0 : $row["userRating"]);
        $city = $row["city"];
        $state = $row["state"];
        $condition = $row["bookCondition"];
        $price = $row["price"];

        echo "<div class='list-item__listing'>
                <img 
                  src='https://res.cloudinary.com/deb6r2y8g/image/upload/v1670567692/44310989._UY500_SS500__cxo78c.jpg'
                >
                <p>$username ($userRating)</p>
                <p>$city, $state</p>
                <p>$condition: $price</p>
              </div>";
    }
}
?>