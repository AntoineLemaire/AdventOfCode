<?php

class Solution extends AdventOfCode\Solution
{
    public function first()
    {
        $rows = $this->input->load();
        $columns = $this->getColumsAsString($rows);
        $diagonals = $this->getDiagonalsAsString($rows);

        $lines = array_merge($rows, $columns, $diagonals);

        $count = 0;
        foreach ($lines as $line) {
            $count += preg_match_all('/XMAS/i', $line, $matches);
            $count += preg_match_all('/SAMX/i', $line, $matches);
        }

        return $count;
    }

    public function second()
    {
        $rows = $this->input->load();

        $dataset = array_map(function ($line) {
            return str_split($line);
        }, $rows);

        $count = 0;
        for ($y = 1; $y < count($dataset) - 1; ++$y) {
            for ($x = 1; $x < count($dataset[0]) - 1; ++$x) {
                $mas = $sam = 0;
                if ('A' !== $dataset[$y][$x]) {
                    continue;
                }

                $mas += ('M' === $dataset[$y - 1][$x - 1] && 'S' === $dataset[$y + 1][$x + 1]) ? 1 : 0; // Bottom left + Top right
                $sam += ('S' === $dataset[$y - 1][$x - 1] && 'M' === $dataset[$y + 1][$x + 1]) ? 1 : 0; // Bottom left + Top right

                $sam += ('S' === $dataset[$y + 1][$x - 1] && 'M' === $dataset[$y - 1][$x + 1]) ? 1 : 0; // Top left + Bottom right
                $mas += ('M' === $dataset[$y + 1][$x - 1] && 'S' === $dataset[$y - 1][$x + 1]) ? 1 : 0; // Top left + Bottom right


                if (2 === $mas + $sam) {
                    ++$count;
                }
            }
        }

        return $count;
    }

    private function getColumsAsString(array $rows)
    {
        $columns = [];
        foreach ($rows as $line) {
            $chars = str_split($line);
            for ($i = 0; $i < count($chars); ++$i) {
                if (!array_key_exists($i, $columns)) {
                    $columns[$i] = [];
                }
                $columns[$i][] = $chars[$i];
            }
        }

        return array_map(function ($column) {
            return array_reduce($column, function ($carry, $char) {
                return $carry . $char;
            }, '');
        }, $columns);
    }

    private function getDiagonalsAsString(array $rows)
    {
        $dataset = array_map(function ($line) {
            return str_split($line);
        }, $rows);

        $l = strlen($rows[0]);
        $h = count($rows);


        $diagonalsLR = [];

        // Diagonals from top left to bottom right
        for ($i = (0 - $h + 1); $i < $l; ++$i) {
            $y = 0;
            $x = $i;
            $diagonalsLR[$i] = '';
            do {
                if (isset($dataset[$y]) && isset($dataset[$y][$x])) {
                    $diagonalsLR[$i] .= $dataset[$y][$x];
                }
                ++$y;
                ++$x;
            } while ($x < $l && $y < $h);
        }

        $diagonalsRL = [];
        // Diagonals from bottom right to top left
        for ($i = 0; $i < ($l + $h - 1); ++$i) {
            $y = 0;
            $x = $i;
            $diagonalsRL[$i] = '';

            do {
                if (isset($dataset[$y]) && isset($dataset[$y][$x])) {
                    $diagonalsRL[$i] .= $dataset[$y][$x];
                }
                ++$y;
                --$x;
            } while ($x < ($l + $h - 1) && $y <= $h);
        }

        return array_merge($diagonalsLR, $diagonalsRL);
    }
}
