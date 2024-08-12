<?php

namespace Auction\Tests\Integration\Dao;

use Auction\Dao\Auction as AuctionDao;
use Auction\Infra\ConnectionCreator;
use Auction\Model\Auction;
use PHPUnit\Framework\TestCase;

class AuctionDaoTest extends TestCase
{
    private \PDO $pdo;
    public function setUp(): void
    {
        $this->pdo = ConnectionCreator::getConnection();
        $this->pdo->beginTransaction();
    }

    public function testInsertionAndFetchMustWork()
    {
        // Arrange
        $auction = new Auction('Mercedes');
        $auctionDao = new AuctionDao($this->pdo);
        $auctionDao->save($auction);
        // Act
        $auctions = $auctionDao->retrieveNotFinished();
        // Assert
        self::assertCount(1, $auctions);
        self::assertContainsOnlyInstancesOf(Auction::class, $auctions);
        self::assertSame('Mercedes', $auctions[0]->getDescription());
    }

    public function tearDown(): void
    {
        $this->pdo->rollBack();
    }
}
