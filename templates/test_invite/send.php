<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/utils.php";

$username = get_required(username);
$email = get_required(email);
$redirect = get_required(redirect);
$lang = get_string(lang, "index");

$template = file_get_contents("/mfm-mail/templates/test_invite/$lang.php");

$vars = [
    "USER_NAME" => $username,
    "SITE_DOMAIN" => $redirect,
];

foreach ($vars as $key => $value) {
    $template = str_replace("[$key]", $value, $template);
}

$response[success] = mailSend("Test invite", $template, $email);

