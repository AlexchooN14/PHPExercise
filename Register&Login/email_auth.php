<?php
    require_once('../Config/app.php');
    require_once('../functions.php');
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'C:\wamp64\PHPMailer\src\Exception.php';
    require 'C:\wamp64\PHPMailer\src\PHPMailer.php';
    require 'C:\wamp64\PHPMailer\src\SMTP.php';

    function sendVerificationEmail($email, $activation_code) {
        $user = getUserByEmail($email);
        $mail = new PHPMailer(true);
        
        // $activation_code = generateActivationCode();
        $activation_link = APP_URL . "/Register%20Form/Register&Login/activate.php?email=$email&activation_code=$activation_code";
            
        // $mail->SMTPDebug = 3;                               
        $mail->isSMTP();                                   
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;                            
        $mail->Username = SENDER_EMAIL_ADDRESS;                 
        $mail->Password = PASSWORD;                           
        $mail->SMTPSecure = "tls";                           
        $mail->Port = 587;                                   
        $mail->From = SENDER_EMAIL_ADDRESS;
        $mail->addAddress($user['email'], $user['name']);
        $mail->isHTML(true);
        $mail->Subject = "Please acivate your account";
        $mail->Body = "<i>Hello ".$user['name'].",<br>Please click the following link to activate your account:<br>
                        $activation_link</i>";

        try {
            $mail->send();
        } catch (Exception $e) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    }

?>