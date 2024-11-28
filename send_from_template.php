<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/utils.php";

$template = get_required(template);
$username = get_required(username);
$email = get_required(email);
$lang = get_required(lang);
$utm_campaign = get_required(utm_campaign, date("m.d.y"));
$site_domain = get_string(site_domain, "mytoken.space");
$send_permanently = get_required(send_permanently);

$head = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/head.html");
$template = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/templates/$template/$lang.html");
if (!$template) {
    $template = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/templates/$template/en.html");
}

$subject = "";
foreach (explode("\n", $template) as $line) {
    $subject = str_between($line, "class=\"subject\">", "</");
    if ($subject != '')
        break;
}

$mail_hash = md5($email);
$link_params = [
    utm_source => newsletter,
    utm_medium => email,
    utm_campaign => $utm_campaign,
    utm_content => $mail_hash,
    lang => $lang,
];

function createLink($link_params, $redirect = null)
{
    $site_domain = get_string(site_domain, "mytoken.space");
    $link = "https://$site_domain?";
    foreach ($link_params as $key => $value) {
        $value = urlencode($value);
        $link .= "$key=$value&";
    }
    if ($redirect != null) {
        $link .= "redirect=$redirect";
    }
    return $link;
}


$vars = [
    "USER_NAME" => $username,
    "SITE_DOMAIN" => $site_domain,
    "LINK" => createLink($link_params),
    "TG_LINK" => createLink($link_params, "https://t.me/mytoken_space_bot"),
    "UNSUBSCRIBE_LINK" => createLink($link_params, "https://$site_domain/unsubscribe/$mail_hash"),
];

foreach ($vars as $key => $value) {
    $template = str_replace("[$key]", $value, $template);
}

$body = $head . $template;

if ($send_permanently == "1") {
    trackEvent($link_params[utm_medium], $mail_hash, $link_params);
    $response[success] = mailSend($subject, $body, $email);
    echo json_encode($response, JSON_PRETTY_PRINT);
} else {
    header("Content-Type: text/html");
    echo $body;
}


