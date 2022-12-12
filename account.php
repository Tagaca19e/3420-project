<?php
require_once("config.php");

if (empty($_SESSION["logged_in"])) {
    header("Location: login.php");
}
if ($_SESSION["logged_in"] == false) {
    header("Location: login.php");
}
if( isset($_POST["changeId"]) ){
    unset($_POST["changeId"]);
    header("Location: changeId.php");
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

echo "User Account ";
echo "<table>";
echo "<tr>";
echo "<th>UserID</th>";
echo "</tr>";
echo "<tr>";
echo "<td>";
echo $_SESSION["user_account"]["userId"];
$changeId_form = new PhpFormBuilder();
$changeId_form->set_att("method", "POST");
$changeId_form->add_input("changeId", array(
    "type" => "submit",
    "value" => "Change UserID"
), "changeId");
$changeId_form->build_form();
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<th>Username</th>";
echo "</tr>";
echo "<tr>";
echo "<td>";
echo $_SESSION["user_account"]["username"];
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<th>User Rating</th>";
echo "</tr>";
echo "<tr>";
echo "<td>";
echo $_SESSION["user_account"]["userRating"];
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<th>Password</th>";
echo "</tr>";
echo "<tr>";
echo "<td>";
echo "<div>";
if( isset($_POST["show"]) ){
    echo $_SESSION["user_account"]["Plaintext"];
    unset($_POST["show"]);
}
else {
    echo "********";
}
$show_form = new PhpFormBuilder();
$show_form->set_att("method", "POST");
$show_form->add_input("show", array(
    "type" => "submit",
    "value" => "Show"
), "show");
$show_form->build_form();
echo "</div>";
$changePassword_form = new PhpFormBuilder();
$changePassword_form->set_att("method", "POST");
$changePassword_form->add_input("changePassword", array(
    "type" => "submit",
    "value" => "Change Password"
), "changePassword");
$changePassword_form->build_form();
if( isset($_POST["changePassword"]) ){
    unset($_POST["changePassword"]);
    header("Location: changePassword.php");
}
echo "</td>";
echo "</tr>";
echo "</table>";
$delete_form = new PhpFormBuilder();
$delete_form->set_att("method", "POST");
$delete_form->add_input("delete", array(
    "type" => "submit",
    "value" => "Delete Account"
), "delete");
$delete_form->build_form();
if( isset($_POST["delete"]) ){
    unset($_POST["delete"]);
    header("Location: deleteAccount.php");
}
?>
</body>
</html>
