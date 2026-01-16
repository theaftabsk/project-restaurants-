<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer/Exception.php';
require_once __DIR__ . '/../PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/SMTP.php';

function sendOTP($toEmail, $otp) {

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'aftabsk741156@gmail.com';
        $mail->Password   = 'hqkq jdui qvdb ocom';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('YOUR_GMAIL@gmail.com', 'Restaurant SaaS');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = 'OTP Verification';
        $mail->Body    = "<h3>Your OTP: <b>$otp</b></h3><p>Valid for 10 minutes.</p>";

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}
function sendMail($to,$subject,$message){
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: Restaurant SaaS <no-reply@upgradeachiever.in>\r\n";

    mail($to,$subject,$message,$headers);
}

?>
