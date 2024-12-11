<?php

class Solution extends AdventOfCode\Solution
{
    /*
     * Expected 193607
     */
    public function first()
    {
        $input = $this->input->load();
        $stones = explode(' ', $input[0]);
        $stones = array_map('intval', $stones);


        for ($i = 0; $i < 25; ++$i) {
            $stones = $this->applyRules($stones);
        }

        return count($stones);
    }

    public function second()
    {
        // Not working
        throw new AdventOfCode\Exception\NotImplementedException();

        $input = $this->input->load();
        $stones = explode(' ', $input[0]);
        $stones = array_map('intval', $stones);

        $count = $this->applyRecursiveRules($stones, 0, 75);

        return $count;
    }

    private function applyRecursiveRules($stones, int $depth, int $maxDepth)
    {
        $count = 0;
        $stones = $this->applyRules($stones);
        if ($maxDepth === $depth + 1) {
            $count += count($stones);
        } else {
            foreach ($stones as $stone) {
                $count += $this->applyRecursiveRules([$stone], $depth + 1, $maxDepth);
            }
        }

        return $count;
    }

    private function applyRules(array $stones): array
    {
        $newStones = [];
        foreach ($stones as $number) {
            if (0 === $number) {
                $newStones[] = '1';
            } elseif (0 === strlen($number) % 2) {
                $newStones[] = (int) substr($number, 0, strlen($number) / 2);
                $newStones[] = (int) substr($number, strlen($number) / 2);
            } else {
                $newStones[] = $number * 2024;
            }
        }

        return $newStones;
    }

    private function displayStones(array $stones): void
    {
        echo implode(' ', $stones) . PHP_EOL;
    }
}
