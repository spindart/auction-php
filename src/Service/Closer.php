<?php

namespace Auction\Service;

use Auction\Dao\Auction as AuctionDao;

/**
 * This class is responsible for closing auctions that have not been finished.
 */
class Closer
{
    /**
     * Closes auctions that have not been finished for more than one week.
     *
     * @return void
     */
    public function close()
    {
        $dao = new AuctionDao();
        $auctions = $dao->retrieveNotFinished();

        foreach ($auctions as $auction) {
            if ($auction->hasMoreThanOneWeek()) {
                $auction->finish();
                $dao->update($auction);
            }
        }
    }
}
