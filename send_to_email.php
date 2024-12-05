<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/utils.php";

$template = get_required(template);
$name = get_required(name);
$email = get_required(email);
$lang = get_required(lang);
$now = get_required(now);

$body = fillTemplateBody($template, $name, $email, $lang);

if ($now == "1") {
    trackEvent("email", "send", null, $email);
    $response[success] = mailSend("Mike Haiduk", $email, $body);
    commit($response);
} else {
    header("Content-Type: text/html");
    echo $body;
}


