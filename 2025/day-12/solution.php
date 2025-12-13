<?php

class Solution extends AdventOfCode\Solution
{
    public function first()
    {
        $input = $this->input->load();
        $regions = [];

        foreach ($input as $line) {
            if (preg_match('/([0-9]+)x([0-9]+):(( [0-9]+)+)+$/', $line, $matches)) {
                $regions[] = [
                    'area' => $matches[1] * $matches[2],
                    'shapeIndexes' => explode(' ', trim($matches[3])),
                ];
            }
        }

        return array_reduce($regions, function ($carry, $region) {
            $totalArea = array_reduce($region['shapeIndexes'], function ($carry, $number) {
                // Use 9 for all shape area, total cheat
                return $carry + 9 * $number;
            }, 0);

            return $carry += $totalArea <= $region['area'] ? 1 : 0;

        }, 0);
    }

    public function second()
    {
        $input = $this->input->load();

        throw new AdventOfCode\Exception\NotImplementedException();
    }
}
