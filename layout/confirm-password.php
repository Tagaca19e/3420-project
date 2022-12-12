<?php
session_start();
require_once("../snippets/get-pdo-connection.php");
require_once("../assets/FormBuilder.php");
require_once("../snippets/table-maker.php");

if (empty($_SESSION["logged_in"])) {
    header("Location: login.php");
}

if ($_SESSION["logged_in"] == false) {
    header("Location: login.php");
}
if (isset($_POST["Confirmed"])){

    $password_username = $_SESSION["username"];
    $password_password = $_POST["change_password"];

    if (strlen($password_username) == 0 || strlen($password_password) == 0){
        $_SESSION["login_error"] = "Password cannot be empty.";
    }
    else {
        $db = get_pdo_connection();
        $query = $db->prepare("SELECT Password from User where username = ?");
        $query->bindParam(1, $password_username, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($results) > 0){
            $hash = $results[0]["Password"];
            
            if(password_verify($password_password, $hash)){
                $_SESSION["password_confirmed"] = true;
                header("Location: change-password.php");
            }
            else{
                $_SESSION["login_error"] = "Invalid password";
            }
        }else{
            $_SESSION["login_error"] = "Invalid password";
        }
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

    echo "<h2>Confirm Password</h2>";
    $password_form = new PhpFormBuilder();
    $password_form->set_att("method", "POST");
    $password_form->add_input("Password", array(
        "type" => "password",
        "required" => true
    ), "change_password");
    $password_form->add_input("Confirmed", array(
        "type" => "submit",
        "value" => "Confirm"
    ), "Confirmed");
    $password_form->build_form();


if (isset($_SESSION["login_error"])){
    echo $_SESSION["login_error"] . "<br>";
    unset($_SESSION["login_error"]);
}

?>
</body>
</html>