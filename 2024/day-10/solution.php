<?php

class Solution extends AdventOfCode\Solution
{
    private array $map = [];
    private array $trailheads = [];
    private array $reachableTop = [];

    /**
     * Expected 789.
     */
    public function first()
    {
        $input = $this->input->load();
        $this->initMap($input);

        $count = 0;
        foreach ($this->trailheads as $trailhead) {
            $this->reachableTop = [];
            $this->countHikingTrails($trailhead[0], $trailhead[1], 0);
            $count += count($this->reachableTop);
        }

        return $count;
    }

    /**
     * Expected 1735
     */
    public function second()
    {
        $input = $this->input->load();
        $this->initMap($input);

        $count = 0;
        foreach ($this->trailheads as $trailhead) {
            $this->reachableTop = [];
            $this->countHikingTrails($trailhead[0], $trailhead[1], 0, true);
            $count += count($this->reachableTop);
        }

        return $count;
    }

    private function countHikingTrails($x, $y, $height, bool $countAllPaths = false)
    {
        $positions = $this->getNextPositions($x, $y, $height + 1);
        foreach ($positions as $position) {
            if (9 === ($height + 1)) {
                if ($countAllPaths || !in_array($position, $this->reachableTop)) {
                    $this->reachableTop[] = $position;
                }
            } else {
                $this->countHikingTrails($position[0], $position[1], $height + 1, $countAllPaths);
            }
        }
    }

    private function getNextPositions(int $x, int $y, int $height): array
    {
        $positions = [];
        $searchCases = [
            [$x - 1, $y], // left
            [$x + 1, $y], // right
            [$x, $y - 1], // top
            [$x, $y + 1], // bottom
        ];
        foreach ($searchCases as $position) {
            $nx = $position[0];
            $ny = $position[1];
            //            dump("search $nx,$ny: height=$height");
            if (isset($this->map[$ny][$nx]) && $this->map[$ny][$nx] == $height) {
                //                dump('found');
                $positions[] = [$nx, $ny];
            }
        }

        return $positions;
    }

    private function initMap(array $rows): void
    {
        $this->map = [];
        $this->reachableTop = [];
        $this->trailheads = [];
        for ($y = 0; $y < count($rows); ++$y) {
            $row = str_split($rows[$y]);
            $this->map[] = $row;
            for ($x = 0; $x < count($row); ++$x) {
                if ('0' === $row[$x]) {
                    $this->trailheads[] = [$x, $y];
                }
            }
        }
    }
}
