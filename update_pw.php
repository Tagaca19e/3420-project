<?php
	// update_pw.php
	// Traverses a MariaDB table and replaces a password column with the
	// result of PHP's password_hash() function

	function getInput($prompt = "Enter Input: "){
		echo $prompt;
		return trim(fgets(STDIN));		
	}
	function getPassword($prompt = "Enter Password: "){
		echo $prompt;
		
		system('stty -echo');
		
		$password = trim(fgets(STDIN));
		
		system('stty echo');
		
		echo "\n";
		
		return $password;
	
	}
	
	$user = getInput("Enter username: ")
	$dbpass = getPassword();
	$dbname = getInput("Enter database: ")

	$db = new mysqli("localhost", $user, $dbpass, $dbname);
	
	$tables = $db->prepare("show tables");
	$tables->execute();
	
	$tableNames = $tables->get_result();
	
	while ($table = $tableNames->fetch_assoc()){
		print_r($table);
	}

	$chosenTable = getInput("Enter a table name: ");
	
	$cq = $db->prepare("describe $chosenTable");
	$cq->execute();
	
	$describe = $cq->get_result();
	
	echo "Description of columns in $chosenTable\n";

	while ($d = $describe->fetch_assoc()){
		echo "Field: " . $d['Field'] . ", Type: " . $d['Type'] . " \n";
		//print_r($d);
	}

	$pkColumn = getInput("Enter a column to represent the primary key: ");
	$passwordColumn = getInput("Enter a column name to run through password_hash: ");

	$cq = $db->prepare("select * from $chosenTable");
	$cq->execute();

	$rows = $cq->get_result();

	$numRows = $rows->num_rows
	$input = getInput("Press enter to show $chosenTable contents ($numRows rows): ");

	$first = false;
	while ($row = $rows->fetch_assoc()){

		$id = $row[$pkColumn];
		$plaintext = $row[$passwordColumn]
		$hash = password_hash($plaintext, PASSWORD_DEFAULT);
		echo "$id: $plaintext -> $hash\n"


		$update = $db->prepare("UPDATE $chosenTable SET password = ? WHERE $pkColumn = ?");
		$update->bind_param("si", $hash, $id);
		$update->execute();

	}
?>
