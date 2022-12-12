<?php
require_once("config.php");

if (empty($_SESSION["logged_in"])) {
    header("Location: login.php");
}
if ($_SESSION["logged_in"] == false) {
    header("Location: login.php");
}

if(isset($_POST["sure"])){
    $db = get_pdo_connection();
    $query = $db->prepare("delete from User where userId = ?");
    $query->bindParam(1, $_SESSION["user_account"]["userId"], PDO::PARAM_INT);
    if ($query->execute()) {
        header( "Location: login.php");
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
echo "<h2>Are you Sure</h2>";
    $sure_form = new PhpFormBuilder();
    $sure_form->set_att("method", "POST");
    $sure_form->add_input("sure", array(
        "type" => "submit",
        "value" => "I'm Sure"
    ), "sure");
    $sure_form->build_form();
?>
</body>
</html>