<?php

class Solution extends AdventOfCode\Solution
{
    /**
     * Expected 6398608069280.
     */
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

        $emptyBlocks = array_filter($compacted, function ($block) {return '.' === $block; });
        $emptyBlocksIndex = array_keys($emptyBlocks);

        $nonEmptyblocks = array_filter($compacted, function ($block) {return '.' !== $block; });

        $j = 0;
        foreach (array_reverse($nonEmptyblocks) as $block) {
            $compacted[$emptyBlocksIndex[$j]] = $block;
            ++$j;
            if (!in_array('.', $compacted)) {
                break;
            }
        }
        $compacted = array_slice($compacted, 0, count($nonEmptyblocks));

        return $this->calculateChecksum($compacted);
    }

    public function second()
    {

        $input = $this->input->load();

        $diskMap = array_map(function ($item) {
            return (int) $item;
        }, str_split($input[0]));
        dump($diskMap);

        $reversed = array_reverse($diskMap, true);
        dump($reversed);

        foreach ($reversed as $key => $space) {
            $fileID = $key / 2;
            foreach ($diskMap as $index => $availableSpace) {
                if ($index % 2 === 1 && $availableSpace >= $space) {
                    
                }
            }
        }
        exit;



        return $this->calculateChecksum($compacted);
    }

    private function calculateChecksum(array $compacted)
    {
        $checksum = 0;
        for ($i = 0; $i < count($compacted); ++$i) {
            if ('.' === $compacted[$i]) {
                continue;
            }
            $checksum += (int) $compacted[$i] * $i;
        }

        return $checksum;
    }
}
