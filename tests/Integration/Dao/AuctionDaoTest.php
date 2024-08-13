<?php

namespace Auction\Tests\Integration\Dao;

use Auction\Dao\Auction as AuctionDao;
use Auction\Infra\ConnectionCreator;
use Auction\Model\Auction;
use PHPUnit\Framework\TestCase;

class AuctionDaoTest extends TestCase
{
    private static \PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = new \PDO('sqlite::memory:');
        $sql = 'CREATE TABLE auctions (
                id INTEGER PRIMARY KEY,
                description TEXT,
                finished BOOLEAN,
                initialDate DATE
                );';
        self::$pdo->exec($sql);
    }
    public function setUp(): void
    {
        self::$pdo->beginTransaction();
    }

    public function testInsertionAndFetchMustWork()
    {
        // Arrange
        $auction = new Auction('Mercedes');
        $auctionDao = new AuctionDao(self::$pdo);
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
        self::$pdo->rollBack();
    }
}
