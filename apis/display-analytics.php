<?php
session_start();

// Check for logged in user.
if (isset($_SESSION["username"]) 
    && !in_array($_SESSION["username"], $_SESSION["admins"]) 
    || !isset($_SESSION["logged_in"])) {
    return;
}

require_once("../snippets/get-pdo-connection.php");
require_once("../snippets/table-maker.php");

function query_analytics($query_str) {
    $db = get_pdo_connection();
    $sql = $query_str;

    $query = $db->prepare($sql);
    $query->execute();

    $rows = $query->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
}

$rows = query_analytics("SELECT city, COUNT(listingId) AS listings 
                         FROM Listing 
                         GROUP BY city");
$by_city = $rows;
$listings_by_city = "";
foreach ($rows as $row) {
    $listings_by_city .=  "{\"y\": " . $row["listings"] . ", \"label\": \"" . $row["city"] . "\"}|";
}

$rows = query_analytics("SELECT city, COUNT(listingId) AS listings 
                         FROM Listing 
                         GROUP BY state");
$by_state = $rows;
$$listings_by_state = "";
foreach ($rows as $row) {
    $listings_by_state .=  "{\"y\": " . $row["listings"] . ", \"label\": \"" . $row["state"] . "\"}|";
}

$rows = query_analytics("SELECT ROUND(SUM(price), 2) AS totalSales 
                         FROM UserListingsInfo
                         WHERE endDate IS NOT NULL");

$generated_sales = "<h3>TRANSACTIONS: $" . $rows[0]["totalSales"] . "</h3>";
echo $generated_sales;
?>
<div>
    <div 
        id="analytics__pie-chart--city" 
        data-key="<?= base64_encode($listings_by_city) ?>"
    >
    </div>

    <div 
        id="analytics__pie-chart--state" 
        data-key="<?= base64_encode($listings_by_state) ?>"
    >
    </div>
</div>
<button class="analytics__print-report">Print report</button>

<div id="content-report" style="visibility: hidden;">
    <?php
    echo $generated_sales;
    echo make_table($by_city);
    echo make_table($by_state);
    ?>
</div>