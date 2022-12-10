<?php
// require_once("config.php");
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $PROJECT_NAME ?></title>
    <link rel="stylesheet" href="./assets/tradespace.css">
</head>
<body>
<h1><?= $PROJECT_NAME?></h1>

<?php
if (isset($_SESSION["logged_in"])) {
    require_once("dashboard.php");
} else {
    require_once("login.php");
}

// TODO(etagaca): Support two different user groups.
// - Must support CRUD operations
// - Must support report generation
?>