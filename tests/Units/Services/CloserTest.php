<?php

namespace Auction\Tests\Service;

use Auction\Model\Auction;
use \Auction\Dao\Auction as AuctionDao;
use Auction\Service\Closer;
use PHPUnit\Framework\TestCase;


class AuctionDaoMock extends AuctionDao
{
    private $auctions = [];

    public function save(Auction $auction): void
    {
        $this->auctions[] = $auction;
    }

    public function retrieveNotFinished(): array
    {
        return array_filter($this->auctions, function (Auction $auction) {
            return !$auction->isFinished();
        });
    }

    public function retrieveFinalized(): array
    {
        return array_filter($this->auctions, function (Auction $auction) {
            return $auction->isFinished();
        });
    }

    public function update(Auction $auction)
    {
    }
}

class CloserTest extends TestCase
{

    public function testAuctionsLongerThanAWeekMustBeClosed()
    {
        // Arrange
        $auction1 = new Auction('Camaro', new \DateTimeImmutable('8 days ago'));
        $auction2 = new Auction('Ferrari', new \DateTimeImmutable('10 days ago'));

        $auctionDao = new AuctionDaoMock();
        $auctionDao->save($auction1);
        $auctionDao->save($auction2);
        // Act
        $closer = new Closer($auctionDao);
        $closer->close();

        // Assert
        $auctions = $auctionDao->retrieveFinalized();
        self::assertCount(2, $auctions);
        self::assertEquals('Camaro', $auctions[0]->getDescription());
        self::assertEquals('Ferrari', $auctions[1]->getDescription());
    }
}
