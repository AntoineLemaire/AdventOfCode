<?php

class Solution extends AdventOfCode\Solution
{
    private const NEXT_DIRECTIONS = [
        'top' => 'right',
        'right' => 'down',
        'down' => 'left',
        'left' => 'top',
    ];

    private ?string $initialDirection = null;
    private array $initialMap = [];
    private ?int $initialGuardX = null;
    private ?int $initialGuardY = null;

    private array $map = [];
    private array $visitedPositions = [];

    private string $guardDirection = 'top';
    private ?int $guardX = null;
    private ?int $guardY = null;

    public function first()
    {
        $input = $this->input->load();
        $this->initMap($input);
        $this->visitedPositions[] = [$this->guardX, $this->guardY];

        try {
            while (true) {
                $this->moveGuard();

                if (!in_array([$this->guardX, $this->guardY], $this->visitedPositions)) {
                    $this->visitedPositions[] = [$this->guardX, $this->guardY];
                }
            }
        } catch (LeftMapException $e) {
            // Guard reach end of the map
        }

        return count($this->visitedPositions);
    }

    // It takes a while (~800s), but it work...
    public function second()
    {
        $input = $this->input->load();

        $this->initMap($input);
        $this->initialDirection = $this->guardDirection;
        $this->initialMap = $this->map;
        $this->initialGuardX = $this->guardX;
        $this->initialGuardY = $this->guardY;

        $loopCount = 0;

        // We try to add a obstruction on each every position the guard already walked in the first step
        for ($i = 0; $i < count($this->visitedPositions); ++$i) {
            [$x, $y] = $this->visitedPositions[$i];

            $this->resetMap();
            $visited = [];

            // Add obstruction
            $this->map[$y][$x] = '#';

            try {
                while (true) {
                    $this->moveGuard();

                    // If the guard walked on the same position with the same direction, he's in a loop
                    if (in_array([$this->guardX, $this->guardY, $this->guardDirection], $visited)) {
                        ++$loopCount;
                        break;
                    } else {
                        $visited[] = [$this->guardX, $this->guardY, $this->guardDirection];
                    }

                }
            } catch (LeftMapException $e) {
                // Guard reach end of the map
            }
        }

        return $loopCount;
    }

    private function resetMap(): void
    {
        // Reset the map
        $this->map = $this->initialMap;
        $this->guardDirection = $this->initialDirection;
        $this->guardX = $this->initialGuardX;
        $this->guardY = $this->initialGuardY;
    }

    private function moveGuard(): void
    {
        [$nx, $ny] = $this->getNextPosition($this->guardX, $this->guardY, $this->guardDirection);

        if (!isset($this->map[$ny][$nx])) {
            throw new LeftMapException('Guard has left the map');
        }

        if ('#' === $this->map[$ny][$nx]) {
            // Guard change direction if next position is an obstruction, and don't move this time
            $this->guardDirection = self::NEXT_DIRECTIONS[$this->guardDirection];
        } else {
            $this->guardX = $nx;
            $this->guardY = $ny;
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

    private function getNextPosition(int $x, int $y, $direction)
    {
        $nx = match ($direction) {
            'top' => $x,
            'right' => $x + 1,
            'down' => $x,
            'left' => $x - 1,
            default => $x,
        };
        $ny = match ($direction) {
            'top' => $y - 1,
            'right' => $y,
            'down' => $y + 1,
            'left' => $y,
            default => $y,
        };

        return [$nx, $ny];
    }

    private function initMap(array $rows): void
    {
        $this->map = [];
        for ($y = 0; $y < count($rows); ++$y) {
            $row = str_split($rows[$y]);
            $this->map[] = $row;
            for ($x = 0; $x < count($row); ++$x) {
                if (in_array($row[$x], ['^', 'v', '>', '<'])) {
                    $this->guardDirection = match ($row[$x]) {
                        '^' => 'top',
                        '<' => 'left',
                        'v' => 'down',
                        '>' => 'right',
                    };
                    $this->guardX = $x;
                    $this->guardY = $y;
                }
            }
        }
    }
}


class LeftMapException extends Exception {}
