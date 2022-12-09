<?php
require_once("config.php");
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $PROJECT_NAME ?></title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</head>
<body>
<h1><?= $PROJECT_NAME?></h1>

<div class="tradespace__wrapper">
    <div class="tradespace__message-wrapper">
        <div class="tradespace__list">
            <?php
            require_once("message.php");
            ?>
        </div>

        <div class="tradespace__messages">
            <div class="tradespace__messages-inner"></div>
            <input type="text" id="message-text" placeholder="Enter message">
        </div>
    </div>
    <div class="tradespace__listing-wrapper">
    </div>
</div>

<?php
if (!empty($_SESSION["affected_rows"])) {
    echo "Deleted " . $_SESSION["affected_rows"] . " rows";
    unset($_SESSION["affected_rows"]);
}
?>

<h2>SQL SELECT -> HTML Table using <a href="https://www.php.net/manual/en/book.mysqli.php">mysqli</a></h2>
<?php

// Selecting from table (User).
$db = get_mysqli_connection();
$query = $db->prepare("SELECT * FROM User");
$query->execute();

$result = $query->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);

echo makeTable($rows);
?>

<h2>SQL SELECT using input from form</h2>
<?php
$select_form = new PhpFormBuilder();
$select_form->set_att("method", "POST");

$select_form->add_input("user id to search for", array(
    "type" => "number"
), "search_id");

$select_form->add_input("username to search for", array(
    "type" => "text"
), "search_data");

$select_form->add_input("Submit", array(
    "type" => "submit",
    "value" => "Search"
), "search");

$select_form->build_form();

if (isset($_POST["search"])) {
    echo "searching...<br>";

    $db = get_mysqli_connection();
    $query = false;

    // Search for userId.
    if (!empty($_POST["search_id"])) {
        echo "searching by userId...";
        $query = $db->prepare("select * from User where userId = ?");
        $query->bind_param("i", $_POST["search_id"]);

    // Search for username. 
    } else if (!empty($_POST["search_data"])) {
        echo "searching by data...";
        $query = $db->prepare("select * from User where username = ?");
        $query->bind_param("s", $_POST["search_data"]);
    }

    if ($query) {
        $query->execute();
        $result = $query->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        echo makeTable($rows);
    } else {
        echo "Error executing query: " . mysqli_error();
    }
}
?>

<h2>SQL INSERT using input from form</h2>
<?php
// Inserting to table (User).
$insert_form = new PhpFormBuilder();
$insert_form->set_att("method", "POST");

echo "Create User";

$insert_form->add_input("username", array(
    "type" => "text"
), "insert_username");

$insert_form->add_input("password", array(
    "type" => "text"
), "insert_password");

$insert_form->add_input("Insert", array(
    "type" => "submit",
    "value" => "Insert"
), "insert");

$insert_form->build_form();

if (isset($_POST["insert"]) 
    && !empty($_POST["insert_username"]) 
    && !empty($_POST["insert_password"])) {
    $userToInsert = htmlspecialchars($_POST["insert_username"]);
    $passwordToInsert = htmlspecialchars($_POST["insert_password"]);
    echo "inserting $dataToInsert ...";

    $db = get_mysqli_connection();
    $query = $db->prepare("insert into User set username = ?, password = ?");
    $query->bind_param("ss", $userToInsert, $passwordToInsert);

    if ($query->execute()) {    
        header( "Location: " . $_SERVER['PHP_SELF']);
    }
    else {
        echo "Error inserting: " . mysqli_error();
    }
}
?>

<h2>SQL UPDATE using input from form</h2>
<?php
// Updating table (User).
$update_form = new PhpFormBuilder();
$update_form->set_att("method", "POST");

$update_form->add_input("user id to update password for", array(
    "type" => "number"
), "user_id");

$update_form->add_input("new password", array(
    "type" => "text"
), "new_password");

$update_form->add_input("Update", array(
    "type" => "submit",
    "value" => "Update"
), "update");

$update_form->build_form();

if (isset($_POST["update"]) 
    && !empty($_POST["user_id"])
    && !empty($_POST["new_password"])) {
    $user_id_to_update = htmlspecialchars($_POST["user_id"]);
    $new_password = htmlspecialchars($_POST["new_password"]);
    echo "updating $user_id_to_update ...";

    $db = get_mysqli_connection();
    $query = $db->prepare("update User set password = ? where userId = ?");
    $query->bind_param("si", $new_password, $user_id_to_update);

    if ($query->execute()) {    
        header( "Location: " . $_SERVER['PHP_SELF']);
    } else {
        echo "Error updating: " . mysqli_error();
    }
}
?>

<h2>SQL DELETE using input from form</h2>
<?php
// Deleting from table (User).
$delete_form = new PhpFormBuilder();
$delete_form->set_att("method", "POST");

$delete_form->add_input("user id to delete for", array(
    "type" => "number"
), "delete_id");

$delete_form->add_input("username to delete", array(
    "type" => "text"
), "delete_data");

$delete_form->add_input("Delete", array(
    "type" => "submit",
    "value" => "Delete"
), "delete");

if (isset($_POST["delete"])) {
    echo "deleting...<br>";
    $db = get_mysqli_connection();
    $query = false;

    if (!empty($_POST["delete_id"])) {
        echo "deleting by id...";
        $query = $db->prepare("delete from User where userId = ?");
        $query->bind_param("i", $_POST["delete_id"]);
    } else if (!empty($_POST["delete_data"])) {
        echo "deleting by username...";
        $query = $db->prepare("delete from User where username = ?");
        $query->bind_param("s", $_POST["delete_data"]);
    }

    if ($query->execute()) {
        header( "Location: " . $_SERVER['PHP_SELF']);
        $_SESSION["affected_rows"] = $db->affected_rows;
    } else {
        echo "Error executing delete query: " . mysqli_error();
    }
}

$delete_form->build_form();
?>