<?php
require_once("config.php");

function get_user_listings($username){
	$db = get_pdo_connection();
	$query = $db->prepare("Select * FROM Listing natural join User where username = ? ");
	$query->bindParam(1, $username, PDO::PARAM_STR);
	$query->execute();
	$results = $query->fetchAll(PDO::FETCH_ASSOC);
		
    return $results;
}

if (isset($_POST["Login"])){
    $login_username = $_POST["login_username"];
    $login_password = $_POST["login_password"];

    if (strlen($login_username) == 0 || strlen($login_password) == 0){
        $_SESSION["login_error"] = "Username and Password cannot be empty.";
    }
    else {
		$db = get_pdo_connection();
		$query = $db->prepare("SELECT password from User where username = ?");
		$query->bindParam(1, $login_username, PDO::PARAM_STR);
		$query->execute();
		$results = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($results) > 0){
			$hash = $results[0]["password"];
			
			if($hash == $login_password){
				$_SESSION["logged_in"]=true;
				$_SESSION["username"]=$login_username;
				
				$listing = get_user_listings($login_username);
				
				if (count($listing)>0){
					$_SESSION["has_listing"] = true;
					$_SESSION["user_listing"] = $listing;
				}
				else {
					$_SESSION["has_listing"] = false;
				}
				header("Location: index.php");
			}
			else{
                $_SESSION["login_error"] = "Invalid username and password combination";
			}
		}else{
            $_SESSION["login_error"] = "Invalid username and password combination";
        }
	}
}
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $PROJECT_NAME . " login page" ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1><?= $PROJECT_NAME . " login page"?></h1>

<?php
$login_form = new PhpFormBuilder();
$login_form->set_att("method", "POST");
$login_form->add_input("Username", array(
    "type" => "text",
    "required" => true
), "login_username");
$login_form->add_input("Password", array(
    "type" => "password",
    "required" => true
), "login_password");
$login_form->add_input("Login", array(
    "type" => "submit",
    "value" => "Login"
), "Login");
$login_form->build_form();

if (isset($_SESSION["login_error"])){
    echo $_SESSION["login_error"] . "<br>";
    unset($_SESSION["login_error"]);
}
?>
</body>
</html>