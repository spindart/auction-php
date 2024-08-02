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
     * @param Bid $bid The bid to be added.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the bid is not an instance of Auction\Model\Bid.
     */
    public function makeBid(Bid $bid)
    {
        if (!empty($this->bids) && $this->isFromLastUser($bid)) {
            return;
        }
        $this->bids[] = $bid;
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
    /**
     * Checks if the bid is from the same user as the last bid.
     *
     * @access private
     * 
     * @param Bid $bid The bid to be checked.
     *
     * @return bool Returns true if the bid is from the same user as the last bid, false otherwise.
     *
     * @throws \InvalidArgumentException If the bid is not an instance of Auction\Model\Bid.
     */
    private function isFromLastUser(Bid $bid): bool
    {
        $lastBid = $this->bids[count($this->bids) - 1];
        return  $bid->getUser() == $lastBid->getUser();
    }
}
