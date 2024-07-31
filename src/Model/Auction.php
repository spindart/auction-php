<?php

namespace Auction\Model;

class Auction
{
    /** @var Bid[] */
    private $bids;
    /** @var string */
    private $description;

    /**
     * Constructs a new Auction object.
     *
     * @param string $description A brief description of the auction.
     *
     * @return void
     */
    public function __construct(string $description)
    {
        $this->description = $description;
        $this->bids = [];
    }

    /**
     * Adds a new bid to the auction.
     *
     * @param Bid $lance The bid to be added.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the bid is not an instance of Auction\Model\Bid.
     */
    public function makeBid(Bid $lance)
    {
        $this->bids[] = $lance;
    }


    /**
     * Retrieves the list of bids made in the auction.
     *
     * @return Bid[] An array of Bid objects representing the bids made in the auction.
     */
    public function getBids(): array
    {
        return $this->bids;
    }
}
