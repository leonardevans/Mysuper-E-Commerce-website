<?php
if (isset($_POST['key'])) {
    if ($_POST["key"] == "send mail") {
        $name = $_POST['name'];
        $senderMail = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        

        $mailBody = "You have received an email from: " . $name . "\n\n" . $message;


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
        $mail->addAddress('evansmutugi2017@gmail.com', ''); // Set the recipient of the message.
        $mail->ClearReplyTos();
        $mail->addReplyTo($senderMail, $name);
        $mail->Subject =  $subject; // The subject of the message.

        /*
        * Message Content - Choose simple text or HTML email
        */
        
        // Choose to send either a simple text email...
        $mail->Body = $mailBody; // Set a plain text body.

        // ... or send an email with HTML.
        //$mail->msgHTML(file_get_contents('contents.html'));
        // Optional when using HTML: Set an alternative plain text message for email clients who prefer that.
        //$mail->AltBody = 'This is a plain-text message body'; 

        // Optional: attach a file
        //$mail->addAttachment('images/phpmailer_mini.png');

        if ($mail->send()) {
            $mail1 = new PHPMailer;
            $mail1->isSMTP();
            $mail1->Host = 'smtp.gmail.com'; // Which SMTP server to use.
            $mail1->Port = 587; // Which port to use, 587 is the default port for TLS security.
            $mail1->SMTPSecure = 'tls'; // Which security method to use. TLS is most secure.
            $mail1->SMTPAuth = true; // Whether you need to login. This is almost always required.
            $mail1->Username = "mysuperonlineshop@gmail.com"; // Your Gmail address.
            $mail1->Password = "Mysuper2020"; // Your Gmail login password or App Specific Password.
            $mail1->setFrom('mysuperonlineshop@gmail.com', 'Mysuper'); // Set the sender of the message.
            $mail1->ClearReplyTos();
            $mail1->addReplyTo('mysuperonlineshop@gmail.com', 'Mysuper');
            $mail1->addAddress($senderMail, $name); // Set the recipient of the message.
            $mail1->Subject =  'Mail Received'; // The subject of the message.
            $mail1->Body = 'Hello '. $name . ', We have received your request. Thank you for contacting us.';
            $mail1->send();
            
            exit("success");
        } else {
        // echo "Mailer Error: " . $mail->ErrorInfo;
        exit('failed');
        }


    }
}