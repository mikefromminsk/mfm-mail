<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/utils.php";

$address = get_required(address);
$subject = get_required(subject);
$body = get_required(body);

$response[success] = mailSendToAddress($address, $subject, $body);

commit($response);