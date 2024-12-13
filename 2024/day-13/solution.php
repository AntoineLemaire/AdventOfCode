<?php

class Solution extends AdventOfCode\Solution
{
    private array $machines = [];

    /**
     * Excepted: 29436
     */
    public function first()
    {
        $input = $this->input->load();

        $countLines = count($input);

        for ($i = 0; $i < $countLines; $i += 4) {
            preg_match('/Button A: X\+([0-9]+), Y\+([0-9]+)/', $input[$i], $matchesBtnA);
            preg_match('/Button B: X\+([0-9]+), Y\+([0-9]+)/', $input[$i + 1], $matchesBtnB);
            preg_match('/Prize: X=([0-9]+), Y=([0-9]+)/', $input[$i + 2], $matchesPrize);
            $this->machines[] = new Machine(
                new Button($matchesBtnA[1], $matchesBtnA[2]),
                new Button($matchesBtnB[1], $matchesBtnB[2]),
                new Prize($matchesPrize[1], $matchesPrize[2]),
            );
        }

        $tokens = 0;
        foreach ($this->machines as $machine) {
            $tokens += (int) $machine->countMinimumTokens();
        }

        return $tokens;
    }

    public function second()
    {
        // Not working
        throw new AdventOfCode\Exception\NotImplementedException();

        $input = $this->input->load();

        $countLines = count($input);

        for ($i = 0; $i < $countLines; $i += 4) {
            preg_match('/Button A: X\+([0-9]+), Y\+([0-9]+)/', $input[$i], $matchesBtnA);
            preg_match('/Button B: X\+([0-9]+), Y\+([0-9]+)/', $input[$i + 1], $matchesBtnB);
            preg_match('/Prize: X=([0-9]+), Y=([0-9]+)/', $input[$i + 2], $matchesPrize);

            $this->machines[] = new Machine(
                new Button($matchesBtnA[1], $matchesBtnA[2]),
                new Button($matchesBtnB[1], $matchesBtnB[2]),
                new Prize(
                    intval('10000000000000' . $matchesPrize[1]),
                    intval('10000000000000' . $matchesPrize[2]),
                ),
                100000000
            );
        }

        $tokens = 0;
        foreach ($this->machines as $machine) {
            $tokens += (int) $machine->countMinimumTokens();
        }

        return $tokens;
    }
}

class Machine
{
    // A: 3 tokens
    // B: 1 token
    public function __construct(
        public Button $a,
        public Button $b,
        public Prize $prize,
        private int $maxPress = 100
    ) {}

    public function countMinimumTokens(): ?int
    {
        $minimum = null;
        for ($a = 0; $a <= $this->maxPress; ++$a) {
            for ($b = 0; $b <= $this->maxPress; ++$b) {
                $x = $a * $this->a->x + $b * $this->b->x;
                $y = $a * $this->a->y + $b * $this->b->y;
                if ($x > $this->prize->x) {
                    continue 2;
                }
                if ($y > $this->prize->y) {
                    continue 2;
                }
                if ($x === $this->prize->x && $y === $this->prize->y) {
                    $tokens = $a * 3 + $b;
                    if (null === $minimum) {
                        $minimum = $tokens;
                    } elseif ($minimum > $tokens) {
                        $minimum = $tokens;
                    }
                }
            }
        }

        return $minimum;
    }
}

class Button
{
    public function __construct(
        public int $x,
        public int $y,
    ) {}
}

class Prize
{
    public function __construct(
        public int $x,
        public int $y,
    ) {}
}
