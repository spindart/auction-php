<?php

namespace Tdd\Auction\Model;

class Auction
{
    /** @var Bid[] */
    private $bids;
    /** @var string */
    private $description;

    public function __construct(string $description)
    {
        $this->description = $description;
        $this->bids = [];
    }

    public function makeBid(Bid $lance)
    {
        $this->bids[] = $lance;
    }

    /**
     * @return Bid[]
     */
    public function getBids(): array
    {
        return $this->bids;
    }
}
