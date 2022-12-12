<?php
session_start();

// Check for logged in user.
if (isset($_SESSION["username"]) 
    && !in_array($_SESSION["username"], $_SESSION["admins"]) 
    || !isset($_SESSION["logged_in"])) {
    return;
}

require_once("../snippets/get-mysqli-connection.php");
require_once("../snippets/table-maker.php");


function query_analytics($query_str) {
    $db = get_mysqli_connection();

    $query = $db->prepare($query_str);
    $query->execute();

    $result = $query->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    return $rows;
}

$rows = query_analytics("SELECT city, COUNT(listingId) AS listings 
                         FROM Listing 
                         GROUP BY city");

$listings_by_city = "";
$by_city = $rows;
foreach ($rows as $row) {
    $listings_by_city .=  "{\"y\": " . $row["listings"] . ", \"label\": \"" . $row["city"] . "\"}|";
}

$rows = query_analytics("SELECT city, COUNT(listingId) AS listings 
                         FROM Listing 
                         GROUP BY state");
$$listings_by_state = "";
$by_state = $rows;

foreach ($rows as $row) {
    $listings_by_state .=  "{\"y\": " . $row["listings"] . ", \"label\": \"" . $row["state"] . "\"}|";
}
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
    echo make_table($by_city);
    echo make_table($by_state);
    ?>
</div>