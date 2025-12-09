<?php

class Solution extends AdventOfCode\Solution
{
    public function first()
    {
        $input = $this->input->load();
        $distances = [];
        $boxes = [];
        foreach ($input as $box) {
            foreach ($input as $box2) {
                if ($box2 === $box) {
                    continue;
                }
                if (!isset($distances["$box-$box2"]) && !isset($distances["$box2-$box"])) {
                    $coords = explode(',', $box);
                    $coords2 = explode(',', $box2);
                    $distances["$box-$box2"] = sqrt(
                        pow($coords[0] - $coords2[0], 2) +
                        pow($coords[1] - $coords2[1], 2) +
                      pow($coords[2] - $coords2[2], 2)
                    );
                }
            }
        }
        asort($distances);
        dump($distances);

        $count = 0;
        $boxNumber = 0;
        foreach ($distances as $key => $distance) {
            if (++$count > 10) {
                break;
            }
            [$coords1, $coords2] = explode('-', $key);



            if (!isset($boxes[$coords1]) && !isset($boxes[$coords2])) {
                $boxes[$coords1] = $boxNumber;
                $boxes[$coords2] = $boxNumber;
                ++$boxNumber;
            } else {
                if (isset($boxes[$coords1])) {
                    if (isset($boxes[$coords2])) {
                        // already connected to others
                        $keys = array_keys($boxes, $boxes[$coords2]);
                        foreach ($keys as $key) {
                            $boxes[$key] = $boxes[$coords1];
                        }
                    }
                    $boxes[$coords2] = $boxes[$coords1];
                }
                if (isset($boxes[$coords2])) {
                    if (isset($boxes[$coords1])) {
                        // already connected to others
                        $keys = array_keys($boxes, $boxes[$coords1]);
                        foreach ($keys as $key) {
                            $boxes[$key] = $boxes[$coords2];
                        }
                    }
                    $boxes[$coords2] = $boxes[$coords2];
                }
            }
        }
        dump($boxes);
        throw new AdventOfCode\Exception\NotImplementedException();
    }

    public function second()
    {
        throw new AdventOfCode\Exception\NotImplementedException();
    }
}
