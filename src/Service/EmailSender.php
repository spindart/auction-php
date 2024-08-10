<?php

namespace Auction\Service;

use Auction\Model\Auction;

class EmailSender
{

    public function sendAuctionClosedEmail(Auction $auction)
    {
        $success  = mail(
            'user@mail.com',
            'Auction Finished',
            'Auction for ' . $auction->getDescription() . ' is finished.'
        );

        if (!$success) {
            throw new \Exception('Error sending email.');
        }
    }
}
