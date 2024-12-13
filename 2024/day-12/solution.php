<?php

class Solution extends AdventOfCode\Solution
{
    private array $map = [];
    /**
     *  [
     *   'type' => 'A',
     *   'positions' => [
     *       [0,1], [2,5], ...
     *   ]
     * ].
     */
    private array $zones = [];
    private array $count = [];
    private int $height = 0;
    private int $width = 0;

    public function first()
    {
        $input = $this->input->load();
        $this->initMap($input);

        $this->initZones();


        $total = 0;
        foreach ($this->zones as $zone) {
            $area = $this->calculateFences($zone);
            $price = $area * count($zone['positions']);
            $total += $price;
        }

        return $total;
    }

    public function second()
    {
        $input = $this->input->load();
        $this->initMap($input);
        throw new AdventOfCode\Exception\NotImplementedException();
    }

    private function initZones()
    {
        for ($y = 0; $y < count($this->map); ++$y) {
            for ($x = 0; $x < count($this->map[$y]); ++$x) {
                if ($this->isPositionInKnownZone($x, $y)) {
                    continue;
                }

                $positions = [[$x, $y]];
                $this->findZonePositions($this->map[$y][$x], $x, $y, $positions);
                $zone = [
                    'type' => $this->map[$y][$x],
                    'positions' => $positions,
                ];
                $this->zones[] = $zone;
            }
        }
    }

    private function findZonePositions($type, $x, $y, &$knownPositions)
    {
        $searchCases = [
            [$x - 1, $y], // left
            [$x + 1, $y], // right
            [$x, $y - 1], // top
            [$x, $y + 1], // bottom
        ];
        foreach ($searchCases as $position) {
            $nx = $position[0];
            $ny = $position[1];

            if ($this->isOutsideMap($nx, $ny)) {
                // outside map
                continue;
            }

            if ($this->map[$ny][$nx] !== $type) {
                continue;
            }

            if (!in_array([$nx, $ny], $knownPositions)) {
                $knownPositions[] = [$nx, $ny];
                $this->findZonePositions($type, $nx, $ny, $knownPositions);
            }
        }
    }

    private function isOutsideMap($x, $y): bool
    {
        if (($x < 0) || ($y < 0)) {
            return true;
        }
        if ($y >= $this->height) {
            return true;
        }
        if ($x >= $this->width) {
            return true;
        }

        return false;
    }

    private function isPositionInKnownZone(int $x, int $y): bool
    {
        foreach ($this->zones as $zone) {
            if (in_array([$x, $y], $zone['positions'])) {
                return true;
            }
        }

        return false;
    }

    private function calculateFences(array $zone)
    {
        $plantType = $zone['type'];
        $area = 0;
        foreach ($zone['positions'] as $position) {
            $x = $position[0];
            $y = $position[1];

            $searchCases = [
                [$x - 1, $y], // left
                [$x + 1, $y], // right
                [$x, $y - 1], // top
                [$x, $y + 1], // bottom
            ];
            foreach ($searchCases as $case) {
                $nx = $case[0];
                $ny = $case[1];

                if ($this->isOutsideMap($nx, $ny) || $this->map[$ny][$nx] !== $plantType) {
                    ++$area;
                }
            }
        }

        return $area;
    }

    private function initMap(array $rows): void
    {
        $this->map = [];
        for ($y = 0; $y < count($rows); ++$y) {
            $row = str_split($rows[$y]);
            $this->map[] = $row;
        }
        $this->height = count($this->map);
        $this->width = count($this->map[0]);
    }
}
