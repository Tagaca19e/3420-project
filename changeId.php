<?php
require_once("config.php");

if (empty($_SESSION["logged_in"])) {
    header("Location: login.php");
}
if ($_SESSION["logged_in"] == false) {
    header("Location: login.php");
}

if(isset($_POST["newConfirmed"])){
    $new_id = $_POST["new_id"];
    if (strlen($new_id) == 0){
        $_SESSION["error"] = "UserID cannot be empty!";
        header("Location: changeId.php");
    }

    $db = get_pdo_connection();
    $query = $db->prepare("select * User where userId = ?");
    $query->bindParam(1, $new_id);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    $db = get_mysqli_connection();;
    $statement = $db->prepare("CALL updateUserId(?, ?)");
    $statement->bind_param('ii', $_SESSION["user_account"]["userId"], $new_id);

    if($statement->execute() && count($results) == 0){
        echo "UserID Changed!";
        header("Location: account.php");
        $_SESSION["user_account"]["userId"] = $new_id;
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1><?= $PROJECT_NAME?><a href="logout.php">Log out</a></h1>

<?php
echo "<h2>Enter New UserID</h2>";
    $newid_form = new PhpFormBuilder();
    $newid_form->set_att("method", "POST");
    $newid_form->add_input("new_id", array(
        "type" => "number",
        "required" => true
    ), "new_id");
    $newid_form->add_input("newConfirmed", array(
        "type" => "submit",
        "value" => "Confirm"
    ), "newConfirmed");
    $newid_form->build_form();
?>
</body>
</html>