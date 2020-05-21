<?php

if (isset($_POST['key'])) {
    if ($_POST['key'] == "logout") {
        session_start();
        session_regenerate_id();
        session_destroy();
        echo "logged_out";
    }
}