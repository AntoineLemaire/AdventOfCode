<?php

class Solution extends AdventOfCode\Solution
{
    private array $matrix = [];
    /** @var array|Antenna[] */
    private array $antennas = [];
    /** @var array|Coordinate[] */
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
                    $antinodes = array_merge(
                        $antenna1->coordinate->getNextAntinodesCoordinates($antenna2->coordinate, $this->matrix),
                        $antenna2->coordinate->getNextAntinodesCoordinates($antenna1->coordinate, $this->matrix)
                    );
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

        // Find antennas
        for ($y = 0; $y < count($this->matrix); ++$y) {
            for ($x = 0; $x < count($this->matrix[$y]); ++$x) {
                $cell = $this->matrix[$y][$x];
                if ('.' !== $cell) {
                    $this->antennas[] = new Antenna($cell, new Coordinate($x, $y));
                    $antinode = new Coordinate($x, $y);
                    $this->addAntinodeToMatrix($antinode);
                }
            }
        }

        // Find couples of antennas
        foreach ($this->antennas as $antenna1) {
            foreach ($this->antennas as $antenna2) {
                if ($antenna1->isCompatible($antenna2)) {
                    $antinodes = array_merge(
                        $antenna1->coordinate->getNextAntinodesCoordinates($antenna2->coordinate, $this->matrix, true),
                        $antenna2->coordinate->getNextAntinodesCoordinates($antenna1->coordinate, $this->matrix, true)
                    );
                    foreach ($antinodes as $antinode) {
                        $this->addAntinodeToMatrix($antinode);
                    }
                }
            }
        }

        return count($this->antinodes);
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

    /**
     * @return Coordinate[]
     */
    public function getNextAntinodesCoordinates(Coordinate $coordinate, array $matrix, bool $deep = false): array
    {
        $diffX = abs($coordinate->x - $this->x);
        $diffY = abs($coordinate->y - $this->y);

        $antinode = new Coordinate(
            $this->x < $coordinate->x ? $this->x - $diffX : $this->x + $diffX,
            $this->y < $coordinate->y ? $this->y - $diffY : $this->y + $diffY,
        );

        if ($antinode->isOutsideMatrix($matrix)) {
            return [];
        }

        $antinodes = [$antinode];

        if (true === $deep) {
            $deepAntinodes = $antinode->getNextAntinodesCoordinates($this, $matrix, true);
            $antinodes = array_merge($antinodes, $deepAntinodes);
        }

        return $antinodes;
    }
}
