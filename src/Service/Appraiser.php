<?php

namespace Tdd\Auction\Service;

use Tdd\Auction\Model\Auction;

class Appraiser
{
    private $highestValue;
    public function evaluate(Auction $auction): void
    {
        $bids = $auction->getBids();
        $lastBid = $bids[count($bids) - 1];
        $this->highestValue = $lastBid->getValue();
    }

    public function getHighestValue(): float
    {
        return $this->highestValue;
    }
}
