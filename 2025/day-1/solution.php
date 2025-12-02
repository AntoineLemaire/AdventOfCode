<?php

class Solution extends AdventOfCode\Solution
{
    public function first()
    {
        $input = $this->input->load();

        $password = 0;
        $dial = 50;

        foreach ($input as $value) {
            $sens = substr($value, 0, 1);
            $steps = (int) substr($value, 1);

            if ('L' === $sens) {
                $dial -= $steps;
                while ($dial < 0) {
                    $dial += 100;
                }
            } else {
                $dial += $steps;
                while ($dial >= 100) {
                    $dial -= 100;
                }
            }

            if (0 === $dial) {
                ++$password;
            }
        }

        return $password;
    }

    public function second()
    {
        $input = $this->input->load();

        $password = 0;
        $dial = 50;

        foreach ($input as $value) {
            $count = (int) substr($value, 1);
            $direction = substr($value, 0, 1);

            $password += intval($count / 100);

            if ('L' === $direction) {
                $dial -= $count % 100;

                if ($dial < 0) {
                    $dial += 100;
                    if (0 !== $dial && 0 !== $previous) {
                        ++$password;
                    }
                }
            } elseif ('R' === $direction) {
                $dial += $count % 100;

                if ($dial > 99) {
                    $dial = abs(100 - $dial);
                    if (0 !== $dial && 0 !== $previous) {
                        ++$password;
                    }
                }
            }
            if (0 === $dial) {
                ++$password;
            }

            $previous = $dial;
        }

        return $password;
    }
}
