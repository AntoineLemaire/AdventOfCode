<?php

class Solution extends AdventOfCode\Solution
{
    public function first()
    {
        $input = $this->input->load();

        $diskMap = str_split($input[0]);

        $compacted = [];
        for ($i = 0; $i < count($diskMap); ++$i) {
            $block = 0 === $i % 2 ? $i / 2 : '.';

            for ($n = 0; $n < (int) $diskMap[$i]; ++$n) {
                $compacted[] = $block;
            }
        }

        dump($compacted);
        echo implode('', $compacted);

        $emptyBlocks = array_filter($compacted, function ($block) {return '.' === $block; });
        $emptyBlocksIndex = array_keys($emptyBlocks);

        $nonEmptyblocks = array_filter($compacted, function ($block) {return '.' !== $block; });

        dump('nonemptyblock',array_reverse($nonEmptyblocks));
        dump('emptyBlocksIndex',$emptyBlocksIndex);
        $j =0;

        foreach (array_reverse($nonEmptyblocks) as $block) {
            $compacted[$emptyBlocksIndex[$j]] = $block;
            array_pop($compacted);
            echo implode('', $compacted).PHP_EOL;
            $j++;

            if (!in_array('.', $compacted)) {
                break;
            }
        }

        // 009981118882777333644655556666
        // 0099811188827773336446555566

        echo implode('', $compacted);
        throw new AdventOfCode\Exception\NotImplementedException();
    }

    public function second()
    {
        $input = $this->input->load();

        throw new AdventOfCode\Exception\NotImplementedException();
    }
}
