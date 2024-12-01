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

// Calculate the similarity score
$score = 0;
foreach ($list1 as $index => $value1) {
    $count = array_reduce($list2, function ($carry, $value2) use ($value1) {
        if ($value1 === $value2) {
            return $carry + 1;
        }
        return $carry;
    }, 0);
    $score += $count * $value1;
}

echo $score;
