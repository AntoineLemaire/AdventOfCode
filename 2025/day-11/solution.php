<?php

class Solution extends AdventOfCode\Solution
{
    private array $data = [];

    public function first()
    {
        $input = $this->input->load();


        foreach ($input as $line) {
            [$source, $destinations] = explode(': ', $line);
            $destinations = explode(' ', $destinations);
            $this->data[$source] = $destinations;
        }

        return $this->countNumberOfPaths('you', 'out');
    }

    public function second()
    {
        $dacOut = $this->countNumberOfPaths('dac', 'out');
        $fftDac = $this->countNumberOfPaths('fft', 'dac');
        $svrFft = $this->countNumberOfPaths('svr', 'fft');
        $fftOut = $this->countNumberOfPaths('fft', 'out');
        $dacFft = $this->countNumberOfPaths('dac', 'fft');
        $svrDac = $this->countNumberOfPaths('svr', 'dac');

        return
            ($svrDac * $dacFft * $fftOut) + // Calculate all paths that goes through srv->dac->fft->out
            ($svrFft * $fftDac * $dacOut)   // And add all paths that goes through src->fft->dac->out
        ;
    }

    private function countNumberOfPaths(string $init, string $out, &$buffer = []): int
    {
        if (isset($buffer[$init])) {
            return $buffer[$init];
        }
        if (!isset($this->data[$init])) {
            return 0;
        }

        $count = 0;

        foreach ($this->data[$init] as $path) {
            if ($out === $path) {
                return 1;
            }
            $subPath = $this->countNumberOfPaths($path, $out, $buffer);
            $buffer[$path] = $subPath;
            $count += $subPath;
        }

        return $count;
    }
}
