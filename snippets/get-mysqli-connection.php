<?php
function get_mysqli_connection() {
    static $connection;
    
    if (!isset($connection)) {
        $connection = mysqli_connect(
            'localhost', // the server address (don't change)
            'etagaca',   // the MariaDB username
            'Vuh9`Bipf', // the MariaDB username's password
            'tradespace' // the MariaDB database name
        ) or die(mysqli_connect_error());
    }
    if ($connection === false) {
        echo "Unable to connect to database<br/>";
        echo mysqli_connect_error();
    }
    return $connection;
}
?>