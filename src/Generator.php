<?php

namespace Faaizz\PinGenerator;

use Exception;

class Generator
{
    private int $numDigits;
    private string $fmtStr;
    private array $obviousNumbers;

    public function __construct()
    {
        $this->numDigits = config('pingenerator.digits');
        $this->obviousNumbers = config('pingenerator.obvious_numbers');
        $this->fmtStr = '%0' . $this->numDigits . 'd';
    }

    // Get random number
    protected function randomNum(): int
    {
        $lowLim = 0;
        $upLim = pow(10, $this->numDigits) - 1;

        return random_int($lowLim, $upLim);
    }

    // Check obvious number
    protected function checkObvious($pin): bool
    {
        return in_array($pin, $this->obviousNumbers);
    }

    // Format pin into the appropriate number of digits by padding with zeros when necessary
    protected function format(int $pin): string
    {
        return sprintf($this->fmtStr, $pin);
    }

    // Generate PIN
    public function generatePin(): string
    {
        $pin = $this->randomNum();

        $safeguardCtr = 0;
        while ($this->checkObvious($pin)) {
            $pin = $this->randomNum();
            $safeguardCtr++;

            if ($safeguardCtr >= 100) {
                throw new Exception('unexpected error. 100 obvious numbers generated in sequence.');
            }
        }

        return $this->format($pin);
    }
}
