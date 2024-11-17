<?php

error_reporting(1);

$file = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/ext/page2.json");

$file = json_decode($file, true);

$locations = [];
foreach ($file as $item) {
    if (array_search($item[location], $locations) !== false) continue;
    $locations[] = $item[location];
}

echo json_encode($locations, JSON_PRETTY_PRINT);
