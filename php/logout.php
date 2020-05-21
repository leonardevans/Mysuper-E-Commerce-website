<?php

if (isset($_POST['key'])) {
    if ($_POST['key'] == "logout") {
        setcookie("mysuper_username", "", time() - 3600,'/');
        setcookie("mysuper_customerID", "", time() - 3600,'/');
        session_start();
        session_regenerate_id();
        session_destroy();
        echo "logged_out";
    }
}