<?php

$input = file_get_contents('input.txt');

$list = [];

// Init the reports list from input
foreach (explode("\n", $input) as $line) {
    if (empty($line)) {
        continue;
    }
    $list[] = explode(' ', $line);

}

$safeReports = array_reduce($list, function ($carry, $reports) {
    if (isSafeReport($reports)) {
        $carry++;
    }

    return $carry;
}, 0);

echo $safeReports;

function isSafeReport(array $reports) : bool
{
    $direction = null; //false = down, true = up
    $i = 1;
    while ($i < count($reports)) {
        if ($direction !== null && (
                ($direction && $reports[$i - 1] > $reports[$i]) ||
                (!$direction && $reports[$i - 1] < $reports[$i])
            )) {
            // unsafe because direction changed
            return false;
        } elseif ((abs($reports[$i] - $reports[$i - 1]) > 3) || ($reports[$i] === $reports[$i - 1])) {
            // unsafe because difference is too high or equal
            return false;
        }
        if ($direction === null) {
            // First two values: determine direction
            $direction = $reports[$i] > $reports[$i - 1];
        }
        $i++;
    }
    return true;
}
