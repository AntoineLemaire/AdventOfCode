<?php

class Solution extends AdventOfCode\Solution
{
	public function first()
	{
		$input = $this->input->load();

        $counts = array_count_values(str_split($input[0]));

        return $counts['('] - $counts[')'];
	}


	public function second()
	{
		$input = $this->input->load();

        $array = str_split($input[0]);
        $floor = 0;
        foreach ($array as $key => $value) {
            if ($value === '(') {
                $floor++;
            } else {
                if (--$floor === -1) {
                    return $key + 1;
                }
            }
        }
        throw new Exception("Basement not reached");
	}
}
