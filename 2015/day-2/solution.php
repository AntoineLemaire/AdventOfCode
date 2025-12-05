<?php

class Solution extends AdventOfCode\Solution
{
    public function first()
    {
        $input = $this->input->load();

        return array_reduce($input, function ($carry, $item) {
            $dimensions = explode('x', $item);
            sort($dimensions);

            return $carry
                + 2 * ($dimensions[0] * $dimensions[1])
                + 2 * ($dimensions[0] * $dimensions[2])
                + 2 * ($dimensions[1] * $dimensions[2])
                + ($dimensions[0] * $dimensions[1]);
        }, 0);
    }

    public function second()
    {
        $input = $this->input->load();

        return array_reduce($input, function ($carry, $item) {
            $dimensions = explode('x', $item);
            sort($dimensions);

            return $carry
                + (2*$dimensions[0] + 2*$dimensions[1])
                + ($dimensions[0] * $dimensions[1] * $dimensions[2]);
        }, 0);
    }
}
