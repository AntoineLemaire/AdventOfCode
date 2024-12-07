<?php

class Solution extends AdventOfCode\Solution
{
    public function first()
    {
        $input = $this->input->load();

        $calibrationResult = 0;
        foreach ($input as $line) {
            $expected = (int) explode(': ', $line)[0];
            $numbers = explode(' ', explode(': ', $line)[1]);

            try {
                $this->doOperations($numbers, $expected, ['+', '*']);
            } catch (Exception $e) {
                $calibrationResult += $expected;
            }

        }

        return $calibrationResult;
    }

    public function second()
    {
        $input = $this->input->load();

        $calibrationResult = 0;
        foreach ($input as $line) {
            $expected = (int) explode(': ', $line)[0];
            $numbers = explode(' ', explode(': ', $line)[1]);

            try {
                $this->doOperations($numbers, $expected, ['+', '*', '||']);
            } catch (Exception $e) {
                $calibrationResult += $expected;
            }

        }

        return $calibrationResult;
    }

    private function doOperations(array $numbers, int $expected, array $operators)
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

                $total = $this->doOperations($newDataset, $expected, $operators);
            } elseif (2 === count($numbers) && $total === $expected) {
                throw new Exception('FOUND');
            }
        }

        return false;
    }
}

class FoundException extends Exception {}
