<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/utils.php";

$from_name = get_required(from_name);
$email_address = get_required(email_address);
$subject = get_required(subject);
$body = get_required(body);

$response[success] = mailSend($from_name, $email_address, $subject, $body);

commit($response);