<?php

class Solution extends AdventOfCode\Solution
{
    public function first()
    {
        $rows = $this->input->load();
        $input = array_reduce($rows, function ($carry, $row) {
            return $carry . $row;
        }, '');

        preg_match_all("/(mul\([0-9]{1,3}\,[0-9]{1,3}\))/", $input, $matches);

        return array_reduce($matches[0], function ($carry, $match) {
            $explode = explode(',', str_replace(['mul(', ')'], '', $match));

            return $carry + $explode[0] * $explode[1];
        }, 0);
    }

    public function second()
    {
        $rows = $this->input->load();
        $input = array_reduce($rows, function ($carry, $row) {
            return $carry . $row;
        }, '');

        preg_match_all("/(mul\([0-9]{1,3}\,[0-9]{1,3}\))|(do\(\))|(don\'t\(\))/", $input, $matches);

        $enabled = true;

        return array_reduce($matches[0], function ($carry, $match) use (&$enabled) {
            $enabled = match ($match) {
                'do()' => true,
                'don\'t()' => false,
                default => $enabled,
            };
            if ($enabled && preg_match("/mul\([0-9]{1,3}\,[0-9]{1,3}\)/", $match)) {
                $explode = explode(',', str_replace(['mul(', ')'], '', $match));

                return $carry + $explode[0] * $explode[1];
            }

            return $carry;
        }, 0);
    }
}
