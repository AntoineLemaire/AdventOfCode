<?php

class Solution extends AdventOfCode\Solution
{
    private array $matrix = [];
    private array $antennas = [];
    private array $antinodes = [];

    public function first()
    {
        $input = $this->input->load();
        $this->matrix = array_map(function ($row) {
            return str_split($row);
        }, $input);

        // Find antennas
        for ($y = 0; $y < count($this->matrix); ++$y) {
            for ($x = 0; $x < count($this->matrix[$y]); ++$x) {
                $cell = $this->matrix[$y][$x];
                if ('.' !== $cell) {
                    $this->antennas[] = new Antenna($cell, new Coordinate($x, $y));
                }
            }
        }

        // Find couples of antennas
        foreach ($this->antennas as $antenna1) {
            foreach ($this->antennas as $antenna2) {
                if ($antenna1->isCompatible($antenna2)) {
                    $antinodes = $antenna1->getAntinodesCoordinates($antenna2);
                    foreach ($antinodes as $antinode) {
                        $this->addAntinodeToMatrix($antinode);
                    }
                }
            }
        }

        return count($this->antinodes);
    }

    public function second()
    {
        $input = $this->input->load();
        $this->matrix = array_map(function ($row) {
            return str_split($row);
        }, $input);

        throw new AdventOfCode\Exception\NotImplementedException();
    }

    private function addAntinodeToMatrix(Coordinate $coordinate)
    {
        if ($coordinate->x < 0
            || $coordinate->y < 0
            || $coordinate->y >= count($this->matrix)
            || $coordinate->x >= count($this->matrix[0])
        ) {
            return;
        }
        if ('.' === $this->matrix[$coordinate->y][$coordinate->x]) {
            $this->matrix[$coordinate->y][$coordinate->x] = '#';
        }

        if (!in_array($coordinate, $this->antinodes)) {
            $this->antinodes[] = $coordinate;
        }
    }

    private function displayMap(array $map)
    {
        foreach ($map as $row) {
            foreach ($row as $char) {
                echo $char;
            }
            echo "\n";
        }
        echo "***********************\n";
    }
}

class Antenna
{
    public function __construct(
        public string $type,
        public Coordinate $coordinate
    ) {}

    public function isCompatible(Antenna $antenna): bool
    {
        return $this->type === $antenna->type
            && !$this->coordinate->isEqual($antenna->coordinate);
    }

    /**
     * @return Coordinate[]
     */
    public function getAntinodesCoordinates(Antenna $antenna): array
    {
        $diffX = abs($antenna->coordinate->x - $this->coordinate->x);
        $diffY = abs($antenna->coordinate->y - $this->coordinate->y);

        return [
            new Coordinate(
                $this->coordinate->x < $antenna->coordinate->x ? $this->coordinate->x - $diffX : $this->coordinate->x + $diffX,
                $this->coordinate->y < $antenna->coordinate->y ? $this->coordinate->y - $diffY : $this->coordinate->y + $diffY,
            ),
            new Coordinate(
                $this->coordinate->x < $antenna->coordinate->x ? $antenna->coordinate->x + $diffX : $antenna->coordinate->x - $diffX,
                $this->coordinate->y < $antenna->coordinate->y ? $antenna->coordinate->y + $diffY : $antenna->coordinate->y - $diffY,
            ),
        ];
    }
}
class Coordinate
{
    public function __construct(
        public int $x,
        public int $y
    ) {}

    public function isEqual(Coordinate $coordinate): bool
    {
        return $coordinate->x === $this->x && $coordinate->y === $this->y;
    }

    public function isOutsideMatrix(array $matrix): bool
    {
        return $this->x < 0 || $this->y < 0 || $this->y > count($matrix) || $this->x > count($matrix[0]);
    }
}
