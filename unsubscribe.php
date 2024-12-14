<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/utils.php";

$object_id = get_required(o);

trackEvent(email, unsubscribe, null, null, $object_id);

commit();


