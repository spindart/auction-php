<?php

namespace Auction\Service;

use Auction\Dao\Auction as AuctionDao;
use Exception;

/**
 * This class is responsible for closing auctions that have not been finished.
 */
class Closer
{
    private $auctionDao;
    private $emailSender;
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
    public function close()
    {

        $auctions = $this->auctionDao->retrieveNotFinished();

        foreach ($auctions as $auction) {
            if ($auction->hasMoreThanOneWeek()) {
                try {
                    $auction->finish();
                    $this->auctionDao->update($auction);
                    $this->emailSender->sendAuctionClosedEmail($auction); // Send email to auction owner about the closure.
                } catch (Exception $e) {
                    error_log($e->getMessage());
                }
            }
        }
    }
}
