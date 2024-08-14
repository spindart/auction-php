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

    /**
     * @dataProvider auctions
     */
    public function testSearchNotFinishedAuctions(array $auctions)
    {
        // Arrange
        $auctionDao = new AuctionDao(self::$pdo);
        foreach ($auctions as $auction) {
            $auctionDao->save($auction);
        }
        // Act
        $auctions = $auctionDao->retrieveNotFinished();
        // Assert
        self::assertCount(1, $auctions);
        self::assertContainsOnlyInstancesOf(Auction::class, $auctions);
        self::assertSame('Mercedes', $auctions[0]->getDescription());
        self::assertFalse($auctions[0]->isFinished());
    }

    /**
     * @dataProvider auctions
     */
    public function testSearchFinishedAuctions(array $auctions)
    {
        // Arrange
        $auctionDao = new AuctionDao(self::$pdo);
        foreach ($auctions as $auction) {
            $auctionDao->save($auction);
        }
        // Act
        $auctions = $auctionDao->retrieveFinalized();
        // Assert
        self::assertCount(1, $auctions);
        self::assertContainsOnlyInstancesOf(Auction::class, $auctions);
        self::assertSame('Porsche', $auctions[0]->getDescription());
        self::assertTrue($auctions[0]->isFinished());
    }

    public function tearDown(): void
    {
        self::$pdo->rollBack();
    }

    /*----------------------------------------------------------------
    Data Providers
    ----------------------------------------------------------------*/
    public static function auctions(): array
    {
        $notFinished = new Auction('Mercedes');
        $finished = new Auction('Porsche');
        $finished->finish();

        return [
            'Auction finished and not finished' =>  [
                [
                    $notFinished,
                    $finished
                ]
            ]
        ];
    }
}
