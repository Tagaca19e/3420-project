<?php
    session_start();
    echo "logout api";
    $_SESSION = array();
    session_destroy();
?>