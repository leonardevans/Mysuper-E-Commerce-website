<?php
session_start();
require './db_connect.php';
if (isset($_POST['key'])) {
    if ($_POST['key'] == "signup") {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        try {
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //check if username is exists
            $query = "SELECT * FROM customer WHERE username='$username'";
            $statement = $connect->prepare($query);
            if ($statement->execute()) {
                $users = $statement->fetchAll();
                if (count($users) > 0) {
                    echo "username_exist";
                } else {
                    //check if email exists
                    $query1 = "SELECT * FROM customer WHERE email='$email'";
                    $statement1 = $connect->prepare($query1);
                    if ($statement1->execute()) {
                        $users1 = $statement1->fetchAll();
                        if (count($users1) > 0) {
                            echo "email_exist";
                        } else {
                            //encrypt password
                            $encrypted_password = md5($password);
                            //add customer details to database
                            $query2 = "INSERT INTO customer (username,email,customer_password) VALUES ('$username','$email','$encrypted_password')";
                            $statement2 = $connect->prepare($query2);
                            if ($statement2->execute()) {
                                // header("location:../login/");
                                echo "created";
                            }

                        }
                    }

                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    } else if ($_POST['key'] == "login") {
        $password = $_POST['password'];
        $email = $_POST['email'];
        try {
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //check if user exists
            $encrypted_password = md5($password);
            $query = "SELECT * FROM customer WHERE email='$email' AND customer_password='$encrypted_password'";
            $statement = $connect->prepare($query);
            if ($statement->execute()) {
                $users = $statement->fetchAll();
                if (count($users) > 0) {
                    foreach ($users as $user) {
                        $_SESSION['mysuper_customerID'] = $user['customerID'];
                        $_SESSION['mysuper_username'] = $user['username'];
                        setcookie('mysuper_username', $_SESSION['mysuper_username'], time() + 43200, '/');
                        setcookie('mysuper_customerID', $_SESSION['mysuper_customerID'], time() + 43200, '/');
                        echo "logged_in";
                    }
                } else {
                    echo "no_such_user";
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}