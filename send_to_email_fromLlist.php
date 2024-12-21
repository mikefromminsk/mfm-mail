<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/utils.php";

//limitSec(60);

$template = get_required(template);
$site_domain = get_required(site_domain);

$base_path = $_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/ext/page3.json";
$page = file_get_contents($base_path);
$page = json_decode($page, true);

//$langs = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/mfm-mail/ext/langs.json");
//$langs = json_decode($langs, true);


foreach ($page as &$item) {
    if (!isset($item[requested]) && $item[email] != null && $item[email] != "-1") {
        requestEquals("/mfm-mail/send_to_email", [
            template => $template,
            site_domain => $site_domain,
            now => "1",
            name => $item[title],
            email => $item[email],
            lang => "ru",
        ]);

        $item[requested] = true;
        file_put_contents($base_path, json_encode($page, JSON_PRETTY_PRINT));
        commit($item);
    }
}