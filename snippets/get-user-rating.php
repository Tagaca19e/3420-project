<?php
// Get user rating by user id.
function get_user_rating($user_id) {
    // Use PDO since we cannot access Table Views in sql with mysqli.
    require_once("../snippets/get-pdo-connection.php");

    $db = get_pdo_connection();
    $sql = "SELECT userRating FROM UserAndRating WHERE userId = ?";

    $query = $db->prepare($sql);
    $query->bindParam(1, $user_id, PDO::PARAM_INT);
    $query->execute();

    $rows = $query->fetchAll(PDO::FETCH_ASSOC);

    $result = $rows[0]["userRating"];
    $rating = ($result) ? $result : 0;
    echo "Rating: " . $rating;
}
?>