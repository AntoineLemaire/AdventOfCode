<?php

$input = file_get_contents('input.txt');

$list1 = [];
$list2 = [];

// Init the two lists from input
foreach (explode("\n", $input) as $line) {
    if (empty($line)) {
        continue;
    }
    $explode = explode('   ', $line);
    $list1[] = $explode[0];
    $list2[] = $explode[1];
}

// Sort the lists
sort($list1);
sort($list2);

// Calculate the cumulative distance
$distance = 0;
foreach ($list1 as $index => $value1) {
    $value2 = $list2[$index];
    $distance += abs($value2 - $value1);
}

echo $distance;
