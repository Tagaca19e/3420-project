<?php
if (isset($_POST["listing_id"])) {
    $listing_id = $_POST["listing_id"];
} else {
    echo "No data provided.";
    return;
}

require_once("../snippets/get-mysqli-connection.php");

$db = get_mysqli_connection();
$sql = "CALL deleteListingsInfo(?)";

$query = $db->prepare($sql);
$query->bind_param("i", $listing_id);

if($query->execute()) {
    echo "Delete success!";
} else {
    echo "Delete failed!";
}
?>