<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/mfm-analytics/utils.php";

$object_id = get_required(o);

trackEvent(email, readed, null, null, $object_id);

header("Content-Type: image/png");
echo file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/mfm-wallet/logo.png");

commit();