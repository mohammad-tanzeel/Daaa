<?php
require 'class.phpmailer.php';

define("USER_NAME", "zakirali1991@gmail.com");
define("PASSWORD", "ali9708180439");
define("FROM_EMAIL", "zakirali1991@gmail.com");
define("TO_EMAIL", "zakirali1991@gmail.com");
define("FROM_NAME", "Suncros");

function send_mail_custom($email_to = "", $subject, $body) {
    $mail = new PHPMailer();
    $mail->IsSMTP();
    //$mail->SMTPDebug = 2;
    //$mail->Mailer = "smtp";
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "ssl";  //ssl or tls
    $mail->Host = "smtp.gmail.com";

    $mail->Port = 465;              //set smtp port for the gmail
    $mail->Username = USER_NAME;    //yourname@yourdomain
    $mail->Password = PASSWORD;     //password
    
    if (empty($email_to)) {
        $email_to = TO_EMAIL;
    }
    //to
    $mail->AddAddress($email_to); //to:
    //subject
    $mail->Subject = $subject;
    //html body
    //$mail->IsHTML(true);
    $mail->Body = $body;
    //from
    $mail->From = FROM_EMAIL;
    $mail->FromName = FROM_NAME;
    $mail->wordWrap = 50;
    //send
    $mail->send();
//    if (!$mail->send()) {
//        print "Mail not send";
//    } else {
//        print "Mail send successfully";
//    }
}

?>