<?php

$html = $_POST[html];

file_put_contents('page.json', json_encode(json_decode($html), JSON_PRETTY_PRINT), FILE_APPEND | LOCK_EX);

/*
$lines = explode("\n", $html);
file_put_contents('page.html', $html);

function get_string_between($string, $start, $end)
{
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
$results = [];
foreach ($lines as $line) {
    $id = get_string_between($line, "https://www.linkedin.com/in/", "?");
    if ($id !== '' && strlen($id) != 39 && strpos($id, '/') === false) {
        $results[$id] = true;
    }
}
$append_to_file = implode(PHP_EOL, array_keys($results));
file_put_contents('ids.txt', $append_to_file , FILE_APPEND | LOCK_EX);

echo json_encode([success => true]);*/