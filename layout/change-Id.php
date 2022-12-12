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

if(isset($_POST["newConfirmed"])){
    $new_id = $_POST["new_id"];
    if (strlen($new_id) == 0){
        $_SESSION["error"] = "UserID cannot be empty!";
        header("Location: changeId.php");
    }

    $db = get_pdo_connection();
    $statement = $db->prepare("CALL updateUserId(?, ?)");
    $statement->bindParam(1, $_SESSION["user_id"], PDO::PARAM_INT);
    $statement->bindParam(2, $new_id, PDO::PARAM_INT);

    if($statement->execute()){
        echo "UserID Changed!";
        header("Location: account.php");
        $_SESSION["user_id"] = $new_id;
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
        <script src="https://js.upload.io/uploader/v2"></script>
        <script src="../assets/tradespace.js"></script>
</head>
<body>
<h1><?= $PROJECT_NAME?></h1>
<div class="nav">
<ul class="nav__primary-items">
<li class="nav__item" data-key="logout">Logout</li>
<li><a href="dashboard.php">Dashboard</a></li>
</ul>
</div>
<div class="tradespace__wrapper">
<div class="tradespace__user-profile">
    <div style = "text-align: center"> 
<?php
echo "<h3>Enter New UserID</h3>";
    $newid_form = new PhpFormBuilder();
    $newid_form->set_att("method", "POST");
    $newid_form->add_input("New User ID", array(
        "type" => "number",
        "required" => true,
    ), "new_id");
    $newid_form->add_input("newConfirmed", array(
        "type" => "submit",
        "value" => "Confirm"
    ), "newConfirmed");
    $newid_form->build_form();
?>
</div>
</div>
</div>
</body>
</html>