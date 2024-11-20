<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/utils.php";

callLimitSec(60);

$base_path = $_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/ext/page3.json";
$page = file_get_contents($base_path);
$page = json_decode($page, true);

$langs = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/ext/langs.json");
$langs = json_decode($langs, true);


foreach ($page as &$item) {
    if (!isset($item[requested]) && $item[email] != null && $item[email] != "-1") {
        requestEquals("/mfm-mail/templates/test_invite/send.php", [
            subject => "Testinvite",
            utm_campaign => "test_invite1",
            username => $item[title],
            email => $item[email],
            lang => $langs[$item[localtion]],
        ]);

        $item[requested] = true;
        file_put_contents($base_path, json_encode($page, JSON_PRETTY_PRINT));
        echo json_encode($item, JSON_PRETTY_PRINT);
        die();
    }
}