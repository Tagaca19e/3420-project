<h1>Create a new account:</h1>
<form action = "register.php" method= "post">
    Username: <input type="text" name="username" required><br/>
    Password: <input type="password" name="password" required><br/>
    <input type = "submit" name ="Register" value = "Register">
</form>
<p>Already have an account? <a href="./login.php">login</a></p>
<?php
require_once("../snippets/get-mysqli-connection.php");

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
        $result_obj = $statement->get_result();
        $result = $result_obj->fetch_assoc();

        // If user id is null, then something went wrong in registration. Put
        // error in the session and redirect back to this page for the user to
        if (is_null($result["userId"])) {
            $_SESSION["error"] = $result["Error"];
            header("Location: register.php");
        } else {
            echo "Registered!"; // User won't see this, header() redirects.
            header("Location: login.php");
        }
    }
}