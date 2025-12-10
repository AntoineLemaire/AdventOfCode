<?php

use Symfony\Component\Console\Helper\ProgressBar;

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

                    $area = $this->getArea($coordinates, $coordinates2);
                    $largestArea = $largestArea < $area ? $area : $largestArea;
                }
            }
        }

        return $largestArea;
    }

    public function second()
    {
        $input = $this->input->load();

        $points = [];

        foreach ($input as $line) {
            $coordinates = explode(',', $line);
            $points[] = $coordinates;
        }
        $this->output->writeln('');

//        $map = $this->makeMap($points);
//        $this->displayMap($map);

        $largestArea = 0;
        $progressBar = new ProgressBar($this->output, count($points));
        $progressBar->setFormat('debug');
        $progressBar->start();

        foreach ($points as $redTile) {
            foreach ($points as $redTile2) {
                if ($redTile === $redTile2) {
                    continue;
                }
                $area = $this->getArea($redTile, $redTile2);

                $reverseReTile = [$redTile[0], $redTile2[1]];
                $reverseReTile2 = [$redTile2[0], $redTile[1]];

                $intersect = false;
                for ($i = 0; $i < count($points); ++$i) {
                    $boundPoint = $points[$i > 0 ? ($i - 1) % count($points) : count($points) - 1];
                    $boundPoint2 = $points[$i];

                    // Search intersect between boundary segments
                    // & all segments of the rectangle and the diagonals
                    if (
                        // Rectangle
                        $this->isSegmentsIntersect($boundPoint, $boundPoint2, $redTile, $reverseReTile2) ||
                        $this->isSegmentsIntersect($boundPoint, $boundPoint2, $reverseReTile2, $redTile2) ||
                        $this->isSegmentsIntersect($boundPoint, $boundPoint2, $redTile2, $reverseReTile) ||
                        $this->isSegmentsIntersect($boundPoint, $boundPoint2, $reverseReTile, $redTile) ||
                        // Diagonals
                        $this->isSegmentsIntersect($boundPoint, $boundPoint2, $redTile, $redTile2) ||
                        $this->isSegmentsIntersect($boundPoint, $boundPoint2, $reverseReTile, $reverseReTile2)
                    ) {
                        $intersect = true;

                        break;
                    }
                }
                if (!$intersect) {
                    $largestArea = $largestArea < $area ? $area : $largestArea;
                }
            }
            $progressBar->advance();
        }
        $progressBar->finish();
        $this->output->writeln('');

        return $largestArea;
    }

    private function getArea($a, $b): int
    {
        $width = (
            $a[0] > $b[0] ?
                $a[0] - $b[0] :
                $b[0] - $a[0]
        ) + 1;
        $height = (
            $a[1] > $b[1] ?
                $a[1] - $b[1] :
                $b[1] - $a[1]
        ) + 1;

        return $height * $width;
    }

    private function isSegmentsIntersect($a, $b, $c, $d): bool
    {
        $o1 = $this->orient($a, $b, $c);
        $o2 = $this->orient($a, $b, $d);
        $o3 = $this->orient($c, $d, $a);
        $o4 = $this->orient($c, $d, $b);

        // Collinear => they don't intersect
        if (0 == $o1 && 0 == $o2 && 0 == $o3 && 0 == $o4) {
            return false;
        }

        // Intersect
        if (($o1 * $o2 < 0) && ($o3 * $o4 < 0)) {
            return true;
        }

        return false;
    }

    private function orient($p, $q, $r)
    {
        return ($q[0] - $p[0]) * ($r[1] - $p[1]) - ($q[1] - $p[1]) * ($r[0] - $p[0]);
    }

    private function makeMap(array $points): array
    {

        $maxX = array_reduce($points, function ($maxX, $point) {
            return $maxX < $point[0] ? $point[0] : $maxX;
        }, 0);
        $maxY = array_reduce($points, function ($maxY, $point) {
            return $maxY < $point[1] ? $point[1] : $maxY;
        }, 0);

        $map = [];
        for ($i = 0; $i < $maxY + 2; ++$i) {
            $map[] = array_fill(0, $maxX + 3, '.');
        }
        foreach ($points as $point) {
            $map[$point[1]][$point[0]] = '#';
        }

        for ($i = 0; $i < count($points); ++$i) {
            $point = $points[$i > 0 ? ($i - 1) % count($points) : count($points) - 1];
            $point2 = $points[$i];

            $x1 = $point[0] < $point2[0] ? $point[0] : $point2[0];
            $x2 = $point[0] < $point2[0] ? $point2[0] : $point[0];
            $y1 = $point[1] < $point2[1] ? $point[1] : $point2[1];
            $y2 = $point[1] < $point2[1] ? $point2[1] : $point[1];

            for ($x = $x1; $x <= $x2; ++$x) {
                for ($y = $y1; $y <= $y2; ++$y) {
                    if ('#' != $map[$y][$x]) {
                        $map[$y][$x] = 'X';
                    }
                }
            }
        }

        return $map;
    }

    private function displayMap(array $map)
    {
        foreach ($map as $row) {
            foreach ($row as $char) {
                echo $char;
            }
            echo "\n";
        }
        echo "\n";
    }
}
