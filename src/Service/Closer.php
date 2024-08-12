<?php

namespace Auction\Service;

use Auction\Dao\Auction as AuctionDao;
use Auction\Model\Auction;
use Exception;

/**
 * This class is responsible for closing auctions that have not been finished.
 */
class Closer
{
    private AuctionDao $auctionDao;
    private EmailSender $emailSender;
    public function __construct(AuctionDao $auctionDao, EmailSender $emailSender)
    {
        $this->auctionDao = $auctionDao;
        $this->emailSender = $emailSender;
    }
    /**
     * Closes auctions that have not been finished for more than one week.
     *
     * @return void
     */
    public function close(): void
    {
        $auctions = $this->auctionDao->retrieveNotFinished();

        foreach ($auctions as $auction) {
            if ($auction->hasMoreThanOneWeek()) {
                $this->closeAuctionMoreThanOneWeek($auction);
            }
        }
    }

    /**
     * Closes an auction that has not been finished for more than one week.
     *
     * @param Auction $auction The auction to be closed.
     *
     * @return void
     *
     * @throws Exception If an error occurs while closing the auction.
     */
    private function closeAuctionMoreThanOneWeek(Auction $auction): void
    {
        try {
            $auction->finish();
            $this->auctionDao->update($auction);
            $this->emailSender->sendAuctionClosedEmail($auction); // Send email to auction owner about the closure.
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}
