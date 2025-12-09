<?php

class Solution extends AdventOfCode\Solution
{
    public function first()
    {
        $input = $this->input->load();

        $largestArea = 0;

        foreach ($input as $line) {
            foreach ($input as $line2) {
                if ($line != $line2) {
                    $coordinates = explode(',', $line);
                    $coordinates2 = explode(',', $line2);
                    $width = (
                        $coordinates[0] > $coordinates2[0] ?
                        $coordinates[0] - $coordinates2[0] :
                        $coordinates2[0] - $coordinates[0]
                    ) + 1;
                    $height = (
                        $coordinates[1] > $coordinates2[1] ?
                        $coordinates[1] - $coordinates2[1] :
                        $coordinates2[1] - $coordinates[1]
                    ) + 1;

                    $area = ($height * $width);
                    $largestArea = $largestArea < $area ? $area : $largestArea;
                }
            }
        }

        return $largestArea;
    }

    public function second()
    {
        $input = $this->input->load();

        throw new AdventOfCode\Exception\NotImplementedException();
    }
}
