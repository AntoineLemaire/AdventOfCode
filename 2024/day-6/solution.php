<?php

class Solution extends AdventOfCode\Solution
{
    private const DIRECTIONS = ['top', 'right', 'down', 'left'];

    private ?string $initialDirection = null;
    private array $initialMap = [];
    private array $visitedDirectedPositions = [];
    private array $visitedPositions = [];
    private array $map = [];
    private string $direction = 'top';

    public function first()
    {
        $input = $this->input->load();

        $this->initMap($input);
        [$x, $y] = $this->getSoldierPosition();
        $this->visitedPositions[] = "$x,$y";

        try {
            while (true) {
                [$x, $y] = $this->moveSoldier();

                if (!in_array("$x,$y", $this->visitedPositions)) {
                    //            dump("Soldier move to $nx, $ny");
                    $this->visitedPositions[] = "$x,$y";
                }

            }
        } catch (Exception $e) {
            // Soldier reach end of the map
        }

        return count($this->visitedPositions);
    }

    public function second()
    {
        throw new AdventOfCode\Exception\NotImplementedException('This solution gave wrong answer');

        $input = $this->input->load();

        $this->initMap($input);
        [$initialX, $initialY] = $this->getSoldierPosition();
        //        dump([$x, $y]);
        $this->initialDirection = $this->direction;
        $this->initialMap = $this->map;

        $loopCount = 0;

        // Run it one time to get all positions where we could put an obstruction
        try {
            while (true) {
                [$x, $y] = $this->moveSoldier();

                if (!in_array("$x,$y", $this->visitedPositions)) {
                    $this->visitedPositions[] = "$x,$y";
                }
            }
        } catch (Exception $e) {
            // Soldier reach end of the map
        }

        for ($i = 0; $i < count($this->visitedPositions); ++$i) {
            [$x, $y] = explode(',', $this->visitedPositions[$i]);
            // Avoid initial soldier place or already existing blocs
            if ('.' === $this->initialMap[$y][$x]) {
                // Reset the map
                $this->map = $this->initialMap;
                $this->direction = $this->initialDirection;
                $this->visitedDirectedPositions = [];
                // Add obstruction
                $this->map[$y][$x] = 'O';
                try {
                    while (true) {
                        $this->moveSoldier();
                    }
                } catch (LeftMapException $e) {
                    // Soldier reach end of the map
                } catch (LoopException $e) {
                    ++$loopCount;
                }
            }
        }

        return $loopCount;
    }

    private function moveSoldier(): array
    {
        [$x, $y] = $this->getSoldierPosition();
        [$nx, $ny] = $this->getNextPosition($x, $y);

        //        if ("$x,$y" === "4,6") {
        //            dump("COUCOU2");
        //            exit;
        //        }

        if (!isset($this->map[$ny][$nx])) {
            throw new LeftMapException('Soldier has left the map');
        }

        // Collision
        if (in_array($this->map[$ny][$nx], ['#', 'O'])) {
            // Change direction
            $this->direction = self::DIRECTIONS[
                (array_search($this->direction, self::DIRECTIONS) + 1) % 4
            ];

            if (in_array("$x,$y," . $this->direction, $this->visitedDirectedPositions)) {
                // It's a loop!
                //                $this->displayMap($this->map);
                //                                dump('LOOP1');
                //                exit;
                throw new LoopException();
            }
            [$nx, $ny] = $this->getNextPosition($x, $y);
        } else {
            //            dump($this->visitedDirectedPositions);
            if (in_array("$nx,$ny," . $this->direction, $this->visitedDirectedPositions)) {
                // It's a loop!
                //                                dump('LOOP2');
                //                exit;
                throw new LoopException();
            }
        }

        $this->map[$y][$x] = '.';
        $this->map[$ny][$nx] = match ($this->direction) {
            'top' => '^',
            'left' => '<',
            'down' => 'v',
            'right' => '>',
        };

        if (!in_array("$nx,$ny," . $this->direction, $this->visitedDirectedPositions)) {
            //            dump("Soldier move to $nx, $ny");
            $this->visitedDirectedPositions[] = "$nx,$ny," . $this->direction;
        }

        return [$nx, $ny];
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

    private function getNextPosition(int $x, int $y)
    {
        $nx = match ($this->direction) {
            'top' => $x,
            'right' => $x + 1,
            'down' => $x,
            'left' => $x - 1,
            default => $x,
        };
        $ny = match ($this->direction) {
            'top' => $y - 1,
            'right' => $y,
            'down' => $y + 1,
            'left' => $y,
            default => $y,
        };

        return [$nx, $ny];
    }

    private function initMap(array $lines): void
    {
        $this->map = [];
        foreach ($lines as $line) {
            $row = str_split($line);
            $this->map[] = $row;
            foreach ($row as $char) {
                if (in_array($char, ['^', 'v', '>', '<'])) {
                    $this->direction = match ($char) {
                        '^' => 'top',
                        '<' => 'left',
                        'v' => 'down',
                        '>' => 'right',
                    };
                }
            }
        }
    }

    private function getSoldierPosition(): array
    {
        for ($x = 0; $x < $this->getMapWidth(); ++$x) {
            for ($y = 0; $y < $this->getMapHeight(); ++$y) {
                if (in_array($this->map[$y][$x], ['^', 'v', '>', '<'])) {
                    return [$x, $y];
                }
            }
        }

        throw new InvalidArgumentException('Soldier not found');
    }

    private function getMapWidth(): int
    {
        return count($this->map);
    }

    private function getMapHeight(): int
    {
        return isset($this->map[0]) ? count($this->map[0]) : 0;
    }
}

class LoopException extends Exception {}
class LeftMapException extends Exception {}
