<?php
session_start();
require './db_connect.php';
if (isset($_POST['key'])) {
    if ($_POST['key'] == "signup") {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $securityCode = $_POST['securityCode'];
        try {
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM security_codes WHERE s_code='$securityCode'";
            $securityCodes = executer($sql);
            if (count($securityCodes) > 0) {
                $sql = "SELECT * FROM admins WHERE username='$username'";
                $admins = executer($sql);
                if (count($admins) > 0) {
                    exit('username_exist');
                } else {
                    $sql = "SELECT * FROM admins WHERE email='$email'";
                    $admins = executer($sql);
                    if (count($admins) > 0) {
                        exit('email_exist');
                    } else {
                        $encrypted_password = md5($password);

                        $sql = "INSERT INTO admins (username,email,admin_password) VALUES ('$username','$email','$encrypted_password')";
                        if (noResultQuery($sql) == 'done') {
                            exit('created');
                        } else {
                            exit('failed');
                        }
                    }
                }
            } else {
                exit('invalid_code');
            }

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    } else if ($_POST['key'] == "signin") {
        $password = $_POST['password'];
        $email = $_POST['email'];
        try {
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //check if user exists
            $encrypted_password = md5($password);
            $sql = "SELECT * FROM admins WHERE email='$email' AND admin_password='$encrypted_password'";
            $admins = executer($sql);
            if (count($admins) > 0) {
                $admin = $admins[0];
                $_SESSION['mysuper_adminID'] = $admin['adminID'];
                $_SESSION['mysuper_admin_username'] = $admin['username'];
                exit('signed_in');

            } else {
                exit('no_such_user');
            }

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

function executer($sql)
{
    global $connect;
    $statement = $connect->prepare($sql);
    if ($statement->execute()) {
        $result = $statement->fetchAll();
        return $result;
    }
}

function noResultQuery($sql)
{
    global $connect;
    $statement = $connect->prepare($sql);
    if ($statement->execute()) {
        return "done";
    } else {
        return "failed";
    }
}