<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/utils.php";

$username = get_required(username);
$email = get_required(email);
$lang = get_required(lang, "index");
$utm_campaign = get_required(utm_campaign, date("m.d.y"));
$site_domain = get_string(site_domain, "mytoken.space");

$template = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/templates/test_invite/$lang.html");

$lines = explode("\n", $template);
foreach ($lines as $line) {
    $str = str_replace("<p class=\"subject\">", "", $line);
    if ($str != $line) {
        $str = str_replace("</p>", "", $str);
        $subject = trim($str);
        break;
    }
}

$mail_hash = md5($email);
$link_params = [
    utm_source => newsletter,
    utm_medium => email,
    utm_campaign => $utm_campaign,
    utm_content => $mail_hash,
];

trackEvent($link_params[utm_medium], $mail_hash, $link_params);

$link = "https://$site_domain?";
foreach ($link_params as $key => $value) {
    $value = urlencode($value);
    $link .= "$key=$value&";
}

$vars = [
    "USER_NAME" => $username,
    "SITE_DOMAIN" => $site_domain,
    "LINK" => $link,
];

foreach ($vars as $key => $value) {
    $template = str_replace("[$key]", $value, $template);
}

$response[success] = mailSend($subject, $template, $email);

echo json_encode($response, JSON_PRETTY_PRINT);

