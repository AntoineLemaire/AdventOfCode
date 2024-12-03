<?php

class Solution extends AdventOfCode\Solution
{
    public function first()
    {
        $input = $this->input->load();

        [$list1, $list2] = $this->initLists($input);

        // Sort the lists
        sort($list1);
        sort($list2);

        // Calculate the cumulative distance
        $distance = 0;
        foreach ($list1 as $index => $value1) {
            $value2 = $list2[$index];
            $distance += abs($value2 - $value1);
        }

        return $distance;
    }

    public function second()
    {
        $input = $this->input->load();

        [$list1, $list2] = $this->initLists($input);

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

        return $score;
    }



    private function initLists(array $input)
    {
        $list1 = [];
        $list2 = [];

        // Init the two lists from input
        foreach ($input as $line) {
            if (empty($line)) {
                continue;
            }
            $explode = explode('   ', $line);
            $list1[] = $explode[0];
            $list2[] = $explode[1];
        }

        return [$list1, $list2];
    }
}
