<?php
function email($subject, $message,$toaddress){
require 'vendor/autoload.php';
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    
    // Use SMT
    $mail->isSMTP();
    
    // SMTP settings
    $mail->SMTPDebug = 0;
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = 'akhilachu200313@gmail.com';
    $mail->Password = 'rskq hfhp sxvm gphb';                 
    
    // Set 'from' email address and name
    $mail->setFrom('akhilachu200313@gmail.com', 'Krishnas Driving');
    
    // Add a recipient email address
    $mail->addAddress($toaddress);
    
    // Email subject and body
    $mail->Subject = $subject;
    $mail->Body = $message;
    
    // Send email
    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
       // echo 'Message sent!';
    }
}


    ?>





