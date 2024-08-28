<?php

namespace Auction\Service;

use Auction\Model\Auction;

class EmailSender
{
    /**
     * Sends an email notification to the specified recipient when an auction is closed.
     * @param Auction $auction The auction object for which the email notification is being sent.
     * @return void
     * @throws \Exception If the email fails to send.
     */
    public function sendAuctionClosedEmail(Auction $auction): void
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
