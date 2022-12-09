<?php
// Get username by id.
function get_username_by_id($user_id) {
    $db = get_mysqli_connection();
    $query = $db->prepare("CALL getUserNameById($user_id)");
    $query->execute();

    $result = $query->get_result();
    $row = $result->fetch_array(MYSQLI_ASSOC);
    return $row["username"];
}
?>