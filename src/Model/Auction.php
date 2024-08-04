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
            throw new \DomainException('User cannot make two consecutive bids.');
        }

        $totalBidsPerUser = $this->totalBidsPerUser($bid->getUser());

        if ($totalBidsPerUser >= 5) {
            throw new \DomainException('User cannot place more than five bids in the same auction.');
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
        $lastBid = $this->bids[array_key_last($this->bids)];
        return  $bid->getUser() == $lastBid->getUser();
    }

    /**
     * Calculates the total number of bids made by a specific user in the auction.
     *
     * @param User $user The user whose bids are to be counted.
     *
     * @return int The total number of bids made by the specified user.
     *
     * @throws \InvalidArgumentException If the provided user is not an instance of Auction\Model\User.
     */
    private function totalBidsPerUser(User $user): int
    {
        $totalBidsPerUser = array_reduce(
            $this->bids,
            function (int $totalAcc, Bid $currentBid) use ($user) {
                if ($currentBid->getUser() == $user) {
                    return $totalAcc + 1;
                }
                return $totalAcc;
            },
            0 // initial
        );

        return $totalBidsPerUser;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
