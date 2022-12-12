<?php
session_start();
require_once("../snippets/get-pdo-connection.php");
require_once("../assets/FormBuilder.php");
require_once("../snippets/table-maker.php");

if (empty($_SESSION["logged_in"])) {
    header("Location: login.php");
}
if(empty($_SESSION["password_confirmed"])){
    header("Location: confirm-password.php");
}
if ($_SESSION["logged_in"] == false) {
    header("Location: login.php");
}

if(isset($_POST["newConfirmed"])){
    $new_password = $_POST["new_password"];
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $db = get_pdo_connection();
    $query = $db->prepare("update User set Password = ? where userId = ?");
    $query->bindParam(1, $hashed);
    $query->bindParam(2, $_SESSION["user_id"], PDO::PARAM_INT);
    if ($query->execute()) {
        $db = get_pdo_connection();
        $query = $db->prepare("update User set Plaintext = ? where userId = ?");
        $query->bindParam(1, $new_password, PDO::PARAM_STR);
        $query->bindParam(2, $_SESSION["user_id"], PDO::PARAM_INT);
        $query->execute();
        $_SESSION["Plaintext"] = $new_password;
        echo "Password Changed";
        header( "Location: account.php");
    }
    else {
        echo "Error updating: " . $db->errorInfo();
    }
}
?>



<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $PROJECT_NAME ?></title>
    <link rel="stylesheet" href="../assets/tradespace.css">
</head>
<body>
<h1><?= $PROJECT_NAME?><a href="../apis/logout.php">Log out</a></h1>

<?php
echo "<h2>Enter New Password</h2>";
    $newpassword_form = new PhpFormBuilder();
    $newpassword_form->set_att("method", "POST");
    $newpassword_form->add_input("Password", array(
        "type" => "password",
        "required" => true
    ), "new_password");
    $newpassword_form->add_input("newConfirmed", array(
        "type" => "submit",
        "value" => "Confirm"
    ), "newConfirmed");
    $newpassword_form->build_form();
?>
</body>
</html>