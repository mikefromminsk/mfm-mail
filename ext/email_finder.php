<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/mfm-db/requests.php';

$page = file_get_contents('page2.json');
$page = json_decode($page, true);
$langs = file_get_contents('langs.json');
$langs = json_decode($langs, true);



foreach ($page as &$item) {
    if (isset($item[email])) continue;
    $response = http_post("https://linkedradar.com/api/lead/contact/find", [
        'linkedin_profile_uri' => $item[id]
    ], [
        'Authorization' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0b2tlbiI6IjNlODc3YjEzNjQ4ZGI1YmJjNTFmOTYyYjQyNzA4YjdlIiwicmVxX3RpbWUiOjE3MzE3NjgwMDUsImV4cCI6MTczNDM2MDAwNX0.HvP_4FSQa3BG5QywsoBqPxlweE_pDDKKN8ITJQBC7_E'
    ]);
    echo json_encode($response, JSON_PRETTY_PRINT);
    $item[email] = $response[data][email];

    http_post("/mfm-mail/templates/test_invite/send", [
        'username' => $item[name],
        'email' => $item[email],
        'redirect' => 'https://vavilon.org',
        'lang' => $langs[$item[location]]
    ]);
    break;
}

file_put_contents('page2.json', json_encode($page, JSON_PRETTY_PRINT));



