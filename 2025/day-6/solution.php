<?php

class Solution extends AdventOfCode\Solution
{
    public function first()
    {
        $input = $this->input->load();

        $operators = explode(' ', preg_replace('/\s+/', ' ', trim(array_pop($input))));

        $results = [];
        foreach ($operators as $index => $operator) {
            switch ($operator) {
                case '+':
                    $results[$index] = 0;
                    break;
                case '*':
                    $results[$index] = 1;
                    break;
                default:
                    throw new Exception('Not implemented type: ' . $operator);
            }
        }

        for ($i = 0; $i < count($input); ++$i) {
            $line = array_map('intval', explode(' ', preg_replace('/\s+/', ' ', trim($input[$i]))));

            for ($j = 0; $j < count($line); ++$j) {
                switch ($operators[$j]) {
                    case '*':
                        $results[$j] *= $line[$j];
                        break;
                    case '+':
                        $results[$j] += $line[$j];
                        break;
                    default:
                        throw new Exception('Not implemented type: ' . $operators[$j]);
                }
            }
        }

        return array_reduce($results, function ($carry, $item) {
            return $carry + $item;
        }, 0);
    }

    public function second()
    {
        $input = $this->input->load();

        $matrix = array_map('str_split', $input);

        // Turn matrix 90° counterclockwise
        $matrix = array_reverse(array_map(null, ... $matrix));

        $results = 0;
        $tmp = [];

        for ($i = 0; $i < count($matrix); ++$i) {
            $lineValue = intval(array_reduce($matrix[$i], function ($carry, $item) {
                $carry .= trim($item, ' +*');

                return $carry;
            }, ''));

            if (0 === $lineValue) {
                continue;
            }

            $tmp[] = $lineValue;

            $lastCharacter = array_pop($matrix[$i]);

            // Found an operator, let's do the calculation of previous values
            if (in_array($lastCharacter, ['+', '*'])) {
                $results += array_reduce($tmp, function ($carry, $item) use ($lastCharacter) {
                    switch ($lastCharacter) {
                        case '+':
                            return $carry + $item;
                        case '*':
                            return $carry * $item;
                        default:
                            throw new Exception('Not implemented type: ' . $lastCharacter);
                    }
                }, '*' === $lastCharacter ? 1 : 0);

                // Reset TMP array of values
                $tmp = [];
            }
        }

        return $results;
    }

    // Debug
    private function printMatrix($matrix)
    {
        foreach ($matrix as $line) {
            foreach ($line as $character) {
                echo $character;
            }
            echo "\n";
        }
    }
}
