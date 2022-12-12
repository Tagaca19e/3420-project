<?php
if (!isset($_SESSION["logged_in"]) && !$_SESSION["logged_in"]) {
    echo "Request invalid!";
    return;
}

$new_password = $_POST["new_password"];
$hashed = password_hash($new_password, PASSWORD_DEFAULT);

$db = get_pdo_connection();
$query = $db->prepare("update User set Password = ? where userId = ?");

$query->bindParam(1, $hashed);
$query->bindParam(2, $_SESSION["user_account"]["userId"], PDO::PARAM_INT);

if ($query->execute()) {
    $db = get_pdo_connection();
    $query = $db->prepare("update User set Plaintext = ? where userId = ?");

    $query->bindParam(1, $new_password, PDO::PARAM_STR);
    $query->bindParam(2, $_SESSION["user_id"], PDO::PARAM_INT);

    if($query->execute()) {
        echo "Password Changed :)";
    } else {
        echo "Failed to update!";
    }
} else {
    echo "Error updating: " . $db->errorInfo();
}
?>