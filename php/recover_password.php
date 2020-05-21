<?php
require './db_connect.php';

try {
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $email = $_POST['email'];

    $sql = "SELECT * FROM customer WHERE email ='$email'";
    $customers = executer($sql);
    if (count($customers) > 0) {
        $letters = array('6', '7', '8', '9', '0', '&', 'A', 'a', 'B', 'b', 'C', 'c', 'D', 'd', 'E', 'e', 'F', 'f', 'G', 'g', 'H', 'h','K', 'k', 'L', 'M', 'm', 'N', 'n', 'O', 'o', 'P', 'p', 'Q', 'q', 'R', 'r', 'S', 's', 'T', 't', 'U', 'u', 'V', 'v', 'W', 'w', 'X', 'x', 'Y', 'y', 'Z', 'z', '1', '2', '3', '4', '5');
        $password = '';
        for ($x = 0; $x <= 8; $x++) {
            $index = rand(0, (count($letters) - 1));
            $password .= $letters[$index];
        }
        $encrypted_password = md5($password);

        
        

        date_default_timezone_set('Etc/UTC');

        // Edit this path if PHPMailer is in a different location.
        require '../PHPMailer/PHPMailerAutoload.php';

        $mail = new PHPMailer;
        $mail->isSMTP();

        /*
        * Server Configuration
        */

        $mail->Host = 'smtp.gmail.com'; // Which SMTP server to use.
        $mail->Port = 587; // Which port to use, 587 is the default port for TLS security.
        $mail->SMTPSecure = 'tls'; // Which security method to use. TLS is most secure.
        $mail->SMTPAuth = true; // Whether you need to login. This is almost always required.
        $mail->Username = "mysuperonlineshop@gmail.com"; // Your Gmail address.
        $mail->Password = "Mysuper2020"; // Your Gmail login password or App Specific Password.

        /*
        * Message Configuration
        */

        $mail->setFrom('mysuperonlineshop@gmail.com', 'Mysuper'); // Set the sender of the message.
        $mail->addAddress($email, ''); // Set the recipient of the message.
        $mail->Subject = 'Change password'; // The subject of the message.

        /*
        * Message Content - Choose simple text or HTML email
        */
        
        // Choose to send either a simple text email...
        $mail->Body = 'You requested to change password.Your new password is  ' . $password . '  .Please proceed to your profile to change password when you login.'; // Set a plain text body.

        // ... or send an email with HTML.
        //$mail->msgHTML(file_get_contents('contents.html'));
        // Optional when using HTML: Set an alternative plain text message for email clients who prefer that.
        //$mail->AltBody = 'This is a plain-text message body'; 

        // Optional: attach a file
        //$mail->addAttachment('images/phpmailer_mini.png');

        if ($mail->send()) {
            $sql = "UPDATE customer SET customer_password='$encrypted_password' WHERE email='$email'";
                    noResultQuery($sql);
                    exit("sent");
        } else {
        // echo "Mailer Error: " . $mail->ErrorInfo;
        exit('failed');
        }

    } else {
        exit('not_found');
    }

} catch (PDOException $e) {
    exit($e->getMessage());
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