<?php

namespace Auction\Tests\Service;

use Auction\Model\Auction;
use \Auction\Dao\Auction as AuctionDao;
use Auction\Service\Closer;
use PHPUnit\Framework\TestCase;



class CloserTest extends TestCase
{

    public function testAuctionsLongerThanAWeekMustBeClosed()
    {
        // Arrange
        $auction1 = new Auction('Camaro', new \DateTimeImmutable('8 days ago'));
        $auction2 = new Auction('Ferrari', new \DateTimeImmutable('10 days ago'));

        $auctionDao = $this->createMock(AuctionDao::class);
        // $auctionDao = $this->getMockBuilder(AuctionDao::class)->setConstructorArgs([new \PDO('sqlite::memory:')])->getMock();
        $auctionDao->method('retrieveNotFinished')->willReturn([$auction1, $auction2]);
        $auctionDao->method('retrieveFinalized')->willReturn([$auction1, $auction2]);
        $auctionDao->expects($this->exactly(2))->method('update');

        $auctionDao->save($auction1);
        $auctionDao->save($auction2);
        // Act
        $closer = new Closer($auctionDao);
        $closer->close();

        // Assert
        $auctions = [$auction1, $auction2];
        self::assertCount(2, $auctions);
        self::assertTrue($auctions[0]->isFinished());
        self::assertTrue($auctions[1]->isFinished());
    }
}
