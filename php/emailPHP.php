<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './php/PHPMailer/src/Exception.php';
require './php/PHPMailer/src/PHPMailer.php';
require './php/PHPMailer/src/SMTP.php';

function sendEmail($email, $pw) {
    $mail = new PHPMailer(true);                            
    $mail->SMTPDebug = 0;                                 
    $mail->isSMTP();                                 
    $mail->Host = 'smtp.example.org'; //SMTP server           
    $mail->SMTPAuth = true;                          
    $mail->Username = 'no-reply@example.org'; //User          
    $mail->Password = 'pass'; //Pass       
    $mail->SMTPSecure = 'ssl';                          
    $mail->Port = 465;                               
    $mail->setFrom('no-reply@example.org', 'No-Reply Engagement'); //User + Name
    $mail->addAddress($email);              
    $mail->IsHTML(true);                              
    $mail->Subject = 'Engagement Password';
    $mail->Body    = 'Here is your password: ' . $pw;
    $mail->send();
}
?>
