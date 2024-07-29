<?php

namespace Tdd\Auction\Service;

use Tdd\Auction\Model\Auction;

class Evaluator
{
    private $highestValue = -INF;
    private $lowestValue = INF;
    public function evaluate(Auction $auction): void
    {
        foreach ($auction->getBids() as $bid) {
            if ($bid->getValue() > $this->highestValue) {
                $this->highestValue = $bid->getValue();
            }
            
            if ($bid->getValue() < $this->lowestValue) {
                $this->lowestValue = $bid->getValue();
            }
        }
    }
    public function getHighestValue(): float
    {
        return $this->highestValue;
    }

    public function getLowestValue(): float
    {
        return $this->lowestValue;
    }
}
