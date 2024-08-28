<?php

namespace Auction\Service;

use Auction\Model\Auction;
use Auction\Model\Bid;

class Evaluator
{
    private float $highestValue = -INF;
    private float $lowestValue = INF;

    private array $bestBids = [];
    private array $worstBids = [];

    /**
     * Evaluates an auction to determine the highest and lowest bid values.
     * @param Auction $auction The auction to evaluate.
     * @return void
     */
    public function evaluate(Auction $auction): void
    {

        if ($auction->isFinished()) {
            throw new \DomainException('Auction is already Finished');
        }

        if (empty($auction->getBids())) {
            throw new \DomainException("You can't evaluate an auction without bids");
        }

        foreach ($auction->getBids() as $bid) {
            if ($bid->getValue() > $this->highestValue) {
                $this->highestValue = $bid->getValue();
            }

            if ($bid->getValue() < $this->lowestValue) {
                $this->lowestValue = $bid->getValue();
            }
        }

        $bestBids = $auction->getBids();
        // Sort bids in ascending order based on their values
        usort($bestBids, function (Bid $bid1, Bid $bid2) {
            return $bid2->getValue() - $bid1->getValue();
        });

        $this->bestBids = array_slice($bestBids, 0, 3); // get the top 3 highest bids


        $worstBids = $auction->getBids();
        // Sort bids in descending order based on their values
        usort($worstBids, function (Bid $bid1, Bid $bid2) {
            return $bid1->getValue() - $bid2->getValue();
        });

        $this->worstBids = array_slice($worstBids, 0, 3); // get the top 3 lowest bids


    }

    /**
     * Retrieves the highest bid value from the evaluated auction.
     * @return float The highest bid value.
     */
    public function getHighestValue(): float
    {
        return $this->highestValue;
    }

    /**
     * Retrieves the lowest bid value from the evaluated auction.
     * @return float The lowest bid value.
     */
    public function getLowestValue(): float
    {
        return $this->lowestValue;
    }

    /**
     * Retrieves the top 3 highest bids from the evaluated auction.
     * @return array An array of Bid objects representing the top 3 highest bids.
     *               The array is sorted in ascending order based on the bid values.
     */
    public function getBestBids(): array
    {
        return $this->bestBids;
    }

    /**
     * Retrieves the top 3 lowest bids from the evaluated auction.
     * @return array An array of Bid objects representing the top 3 lowest bids.
     *               The array is sorted in ascending order based on the bid values.
     */
    public function getWorstBids(): array
    {
        return $this->worstBids;
    }
}
