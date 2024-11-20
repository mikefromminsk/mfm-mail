<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/mfm-db/utils.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/mfm-analytics/utils.php";

spl_autoload_register(function ($class_name) {
    include $class_name . ".php";
});

use PHPMailer\PHPMailer;

function mailSend($subject, $body, $receivers)
{
    // Before send email:
    // 1. Enable "IMAP Access" at https://mail.google.com/mail/u/0/#settings
    $email_addr = get_config_required("email_addr");
    // 2. Create an app password at https://myaccount.google.com/apppasswords
    $email_pass = get_config_required("email_pass");
    $email_name = get_config_required("email_name");

    $mail = new PHPMailer(true);
    $mail->IsSMTP();
    $mail->SMTPDebug = 0; // set 1 for debugging
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "ssl";
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465;
    $mail->Username = $email_addr;
    $mail->Password = $email_pass;
    $mail->From = $email_addr;
    $mail->FromName = $email_name;
    $mail->isHTML(true);

    $addresses = explode(",", $receivers);
    foreach ($addresses as $address)
        $mail->addAddress($address);

    $mail->Subject = $subject;
    $mail->Body = $body;
    return $mail->send();
}