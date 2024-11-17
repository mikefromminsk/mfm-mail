<?php

error_reporting(1);

$file = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/ext/langs.json");

$file = json_decode($file, true);

$langs = [];
foreach ($file as $location => $lang) {
    $lang = explode(',', $lang)[0];
    if (array_search($lang, $langs) !== false) continue;
    $langs[] = $lang;
}

echo json_encode($langs, JSON_PRETTY_PRINT);
