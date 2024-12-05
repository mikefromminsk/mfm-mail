<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/mfm-db/utils.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/mfm-analytics/utils.php";

spl_autoload_register(function ($class_name) {
    include $class_name . ".php";
});

use PHPMailer\PHPMailer;

function mailSend($from_name, $email_address, $body)
{
    $subject = "";
    foreach (explode("\n", $body) as $line) {
        $subject = str_between($line, "class=\"subject\">", "</");
        if ($subject != "")
            break;
    }

    // Before send email:
    // 1. Enable "IMAP Access" at https://mail.google.com/mail/u/0/#settings
    $email_addr = get_config_required("email_addr");
    // 2. Create an app password at https://myaccount.google.com/apppasswords
    $email_pass = get_config_required("email_pass");
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
    $mail->FromName = $from_name;
    $mail->isHTML(true);
    $mail->addAddress($email_address);
    $mail->Subject = $subject;
    $mail->CharSet = "UTF-8";
    $mail->Body = $body;
    return $mail->send();
}

function mailGetReceiverInfo($address)
{
    $link_event = getEvent(ui, email_link, null, $address);
    if ($link_event == null) return null;
    $email_address = $link_event[value];
    $start_event = getEvent(ui, email_referer, null, $email_address);
    if ($start_event == null) return null;
    return getObject($start_event[value]);
}

function mailSendToAddress($from, $to_address, $message)
{
    $link_event = getEvent(ui, email_link, null, $to_address);
    if ($link_event == null) error("No email connection for $to_address");
    return mailSend($from, $link_event[value], $message);
}

function fillTemplateBody($template, $name, $email, $lang, $params = [])
{

    $template_file = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/templates/$template/$lang.html");
    if (!$template_file) {
        $template_file = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/templates/$template/en.html");
    }

    $object_id = trackObject([
        template => $template,
        name => $name,
        email => $email,
        lang => $lang,
    ]);

    function createLink($site_domain, $params = [])
    {
        $link = "https://$site_domain?";
        foreach ($params as $key => $value) {
            $value = urlencode($value);
            $link .= "$key=$value&";
        }
        $link = substr($link, 0, -1);
        return $link;
    }

    $site_domain = "mytoken.space";
    $base_params = [
        name => $name,
        site_domain => $site_domain,
        site_link => createLink($site_domain, [o => $object_id, email => $email, lang => $lang]),
        logo => createLink("$site_domain/mfm-mail/logo.php", [o => $object_id, email => $email]),
        unsubscribe_link => createLink($site_domain, [o => $object_id, unsubscribe => $email, lang => $lang]),
    ];

    foreach (array_merge($base_params, $params) as $key => $value) {
        $template_file = str_replace("[" . strtoupper($key) . "]", $value, $template_file);
    }

    $head = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/head.html");
    return $head . $template_file;
}