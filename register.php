
<body>

Create a new account:
<form action = "register.php" method= "post">
Username: <input type = "text" name ="username" required><br/>
Password: <input type = "password" name = "password" required><br/>
<input type = "submit" name ="Register" value = "Register">
</form>

<?php

require_once "config.php";

if (isset($_SESSION["error"])) {
    echo "Something went wrong!<br>";
    echo $_SESSION["error"];
    unset($_SESSION["error"]);
    die();
}

if (isset($_POST['Register'])){
    unset($_POST['Register']);
    $db = get_mysqli_connection();;
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (strlen($username) == 0 || strlen($password) == 0){
        $_SESSION["error"] = "Username and/or pasword cannot be empty!";
        header("Location: register.php");
    }
    
    $statement = $db->prepare("CALL RegisterUser(?, ?)");
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $statement->bind_param('ss', $username, $hashed);

    if($statement->execute()){
        // Two options to retrieve data from a query that returns rows
        // Option 1: bind results to variables and call fetch. This fetches a 
        // single row anad binds the values to the variables you specify
        /*
        //mysqli_stmt_bind_result($statement, $res_id, $res_error);
        //$statement->fetch();
        */

        // Option 2: get the results to retrieve the result set, then call
        // fetch_array or fetch_assoc on it to get one row at a time
        $resultObj = $statement->get_result();
        $result = $resultObj->fetch_assoc();

        // If user id is null, then something went wrong in registration. Put
        // error in the session and redirect back to this page for the user to
        if (is_null($result["userId"])) {
            $_SESSION["error"] = $result["Error"];
            header("Location: register.php");
        }
        else{
            echo "Registered!"; //User won't see this, header() redirects
            header("Location: login.php");
        }
    }
}