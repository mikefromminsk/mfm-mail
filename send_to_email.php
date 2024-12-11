<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/utils.php";

$template = get_required(template);
$site_domain = get_required(site_domain);
$name = get_required(name);
$email = get_required(email);
$lang = get_required(lang);
$now = get_required(now);

$body = fillTemplateFromObject($template, $lang, $site_domain, $object_id = trackObject([
    name => $name,
]));


if ($now == "1") {
    trackEvent("email", "send", $template, $email, $object_id);
    $response[success] = mailSend("Mike Haiduk", $email, $body);
    commit($response);
} else {
    header("Content-Type: text/html");
    echo $body;
}


