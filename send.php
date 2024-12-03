<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/utils.php";

$subject = get_required(subject);
$body = get_required(body);
$receivers = get_required(receivers);

$response[success] = mailSend($subject, $body, $receivers);

commit($response);