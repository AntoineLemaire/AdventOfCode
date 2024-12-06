<?php

class Solution extends AdventOfCode\Solution
{
    public function first()
    {
        $input = $this->input->load();

        $rules = $this->makeRules($input);

        $middles = 0;

        foreach ($this->getPageNumbers($input) as $row) {
            if (false !== strpos($row, ',')) {
                if ($this->isValidRow($row, $rules)) {
                    $numbers = explode(',', $row);
                    $midIndex = (int) round(count($numbers) / 2) - 1;
                    $middles += (int) $numbers[$midIndex];
                }
            }
        }

        return $middles;
    }

    public function second()
    {
        $input = $this->input->load();
        $rules = $this->makeRules($input);
        $pageNumbers = iterator_to_array($this->getPageNumbers($input));

        $newValidRows = [];
        foreach ($pageNumbers as $row) {
            if ($this->isValidRow($row, $rules)) {
                continue;
            }
            $numbers = explode(',', $row);
            usort($numbers, function ($a, $b) use ($rules) {
                if (in_array($b, $rules[$a][0])) {
                    return 1;
                }
                if (in_array($b, $rules[$a][1])) {
                    return -1;
                }

                return 0;
            });
            $newValidRows[] = implode(',', $numbers);
        }

        $middles = 0;
        foreach ($newValidRows as $row) {
            $numbers = explode(',', $row);
            $midIndex = (int) round(count($numbers) / 2) - 1;
            $middles += (int) $numbers[$midIndex];
        }

        return $middles;
    }

    private function getPageNumbers(array $input): Generator
    {
        foreach ($input as $row) {
            if (false !== strpos($row, ',')) {
                yield $row;
            }
        }

    }

    private function isValidRow(string $row, array $rules): bool
    {
        $numbers = explode(',', $row);

        // Verify each number of this row one by one
        for ($i = 0; $i < count($numbers); ++$i) {
            $number = $numbers[$i];
            // Do we have rules for this number?
            if (array_key_exists($number, $rules)) {
                $beforeRules = $rules[$number][0];
                $afterRules = $rules[$number][1];

                // Verify numbers after
                for ($y = $i + 1; $y < count($numbers); ++$y) {
                    if (!in_array($numbers[$y], $afterRules)) {
                        // INVALID
                        return false;
                    }
                }
                // Verify numbers before
                for ($y = $i - 1; $y > 0; --$y) {
                    if (!in_array($numbers[$y], $beforeRules)) {
                        // INVALID
                        return false;
                    }
                }
            }
        }

        return true;
    }

    private function makeRules(array $input): array
    {
        $rules = [];
        foreach ($input as $row) {
            if (false !== strpos($row, '|')) {
                [$a, $b] = explode('|', $row);
                if (!array_key_exists($a, $rules)) {
                    $rules[$a] = [0 => [], 1 => []];
                }
                if (!array_key_exists($b, $rules)) {
                    $rules[$b] = [0 => [], 1 => []];
                }
                // List of numbers that should be after $a
                $rules[$a][1][] = $b;
                // list of numbers that should be before $b
                $rules[$b][0][] = $a;
            }
        }

        return $rules;
    }
}
