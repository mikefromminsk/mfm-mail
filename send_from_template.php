<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/utils.php";

$template = get_required(template);
$username = get_required(username);
$email = get_required(email);
$lang = get_required(lang);
$site_domain = get_string(site_domain, "mytoken.space");
$send_permanently = get_required(send_permanently);

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


$object_id = trackObject([
    template => $template,
    username => $username,
    email => $email,
    lang => $lang,
]);

function createLink($params = [])
{
    $params[lang] = get_required(lang);
    $site_domain = get_string(site_domain);
    $link = "https://$site_domain?";
    foreach ($params as $key => $value) {
        $value = urlencode($value);
        $link .= "$key=$value&";
    }
    $link = substr($link, 0, -1);
    return $link;
}

$vars = [
    "USER_NAME" => $username,
    "SITE_DOMAIN" => $site_domain,
    "LINK" => createLink([o => $object_id]),
    "TG_LINK" => createLink([o => $object_id, redirect => "https://t.me/mytoken_space_bot"]),
    "UNSUBSCRIBE_LINK" => createLink([o => $object_id, unsubscribe => $email]),
];

foreach ($vars as $key => $value) {
    $template = str_replace("[$key]", $value, $template);
}

$head = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/head.html");
$body = $head . $template;


if ($send_permanently == "1") {
    trackEvent("email", "send", $object_id);
    $response[success] = mailSend($subject, $body, $email);
    echo json_encode($response, JSON_PRETTY_PRINT);
} else {
    header("Content-Type: text/html");
    echo $body;
}


