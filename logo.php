<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/mfm-analytics/utils.php";

$object_id = get_string(o);
$email = get_string(email);

trackEvent(email, readed, $object_id, $email);

header("Content-Type: image/png");
echo file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/mfm-wallet/logo.png");