<?php

namespace Auction\Service;

use Auction\Dao\Auction as AuctionDao;

/**
 * This class is responsible for closing auctions that have not been finished.
 */
class Closer
{
    private $auctionDao;
    public function __construct(AuctionDao $auctionDao)
    {
        $this->auctionDao = $auctionDao;
    }
    /**
     * Closes auctions that have not been finished for more than one week.
     *
     * @return void
     */
    public function close()
    {

        $auctions = $this->auctionDao->retrieveNotFinished();

        foreach ($auctions as $auction) {
            if ($auction->hasMoreThanOneWeek()) {
                $auction->finish();
                $this->auctionDao->update($auction);
            }
        }
    }
}
