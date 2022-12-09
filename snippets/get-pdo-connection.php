<?php
// Gets a connection to the database using PHP Data Objects (PDO).
function get_pdo_connection() {
    static $conn;

    if (!isset($conn)) {
        try {
            // Make persistent connection.
            $options = array(
                PDO::ATTR_PERSISTENT => true
            );

            $conn = new PDO(
                "mysql:host=localhost;dbname=tradespace",  // change dbname
                "etagaca",                                 // change username
                "Vuh9`Bipf",                                // change password
                $options
            );
        }
        catch (PDOException $pe) {
            echo "Error connecting: " . $pe->getMessage() . "<br>";
            die();
        }
    }

    if ($conn === false) {
        echo "Unable to connect to database<br/>";
        die();
    }
    return $conn;
}
?>