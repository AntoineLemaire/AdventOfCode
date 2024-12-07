<?php

class Solution extends AdventOfCode\Solution
{
    public function first()
    {
        $input = $this->input->load();

        return $this->doFileOperations($input, ['+', '*']);
    }

    public function second()
    {
        $input = $this->input->load();

        return $this->doFileOperations($input, ['+', '*', '||']);
    }

    private function doFileOperations(array $input, array $operators)
    {
        $calibrationResult = 0;
        foreach ($input as $line) {
            $expected = (int) explode(': ', $line)[0];
            $numbers = explode(' ', explode(': ', $line)[1]);

            try {
                $this->doRowOperations($numbers, $expected, $operators);
            } catch (FoundException) {
                $calibrationResult += $expected;
            }

        }

        return $calibrationResult;
    }

    private function doRowOperations(array $numbers, int $expected, array $operators)
    {
        foreach ($operators as $operator) {
            $copy = $numbers;
            $number1 = array_shift($copy);
            $number2 = array_shift($copy);

            switch ($operator) {
                case '+':
                    $total = $number1 + $number2;
                    break;
                case '*':
                    $total = $number1 * $number2;
                    break;
                case '||':
                    $total = (int) "$number1$number2";
                    break;
            }

            if (count($numbers) > 2) {
                $newDataset = array_merge([$total], $copy);
                // Recursive operation with only the rest of the row + the result of first operation
                $total = $this->doRowOperations($newDataset, $expected, $operators);
            } elseif (2 === count($numbers) && $total === $expected) {
                throw new FoundException();
            }
        }

        return false;
    }
}

class FoundException extends Exception {}
