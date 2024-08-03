<?php

namespace Auction\Tests\Model;

use Auction\Model\Auction;
use Auction\Model\Bid;
use Auction\Model\User;
use PhpParser\Node\Stmt\UseUse;
use PHPUnit\Framework\TestCase;

class AuctionTest extends TestCase
{
    /**
     * @dataProvider makeBids
     */
    public function testAuctionMustReceiveBids(
        int $bidsQuantity,
        Auction $auction,
        array $values
    ): void {

        static::assertCount($bidsQuantity, $auction->getBids());

        foreach ($values as $i => $valueExpected) {
            static::assertEquals($valueExpected, $auction->getBids()[$i]->getValue());
        }
    }

    public function testAuctionMustNotReceiveRepeatBids(): void
    {
        $auction = new Auction('BMW');
        $user = new User('Francisca');

        $auction->makeBid(new Bid($user, 2000));
        $auction->makeBid(new Bid($user, 2500));

        static::assertCount(1, $auction->getBids());
        static::assertEquals(2000, $auction->getBids()[0]->getValue());
    }

    public function testAuctionShouldNotAcceptMoreThanFiveBidsPerUser(): void
    {
        $auction = new Auction('Camaro');
        $user1 = new User('Fernanda');
        $user2 = new User('Pedro');

        $auction->makeBid(new Bid($user1, 1000));
        $auction->makeBid(new Bid($user2, 1500));
        $auction->makeBid(new Bid($user1, 2000));
        $auction->makeBid(new Bid($user2, 3000));
        $auction->makeBid(new Bid($user1, 4000));
        $auction->makeBid(new Bid($user2, 5000));
        $auction->makeBid(new Bid($user1, 6000));
        $auction->makeBid(new Bid($user2, 7000));
        $auction->makeBid(new Bid($user1, 7500));
        $auction->makeBid(new Bid($user2, 8000));

        $auction->makeBid(new Bid($user1, 9000));

        static::assertCount(10, $auction->getBids());
        static::assertEquals(8000, $auction->getBids()[array_key_last($auction->getBids())]->getValue());
    }

    public static  function makeBids(): array
    {
        $auction = new Auction('Ferrari');

        $auction->makeBid(new Bid(new User('Fernanda'), 2000));
        $auction->makeBid(new Bid(new User('Pedro'), 3000));
        $auction->makeBid(new Bid(new User('Paula'), 4000));

        $auction2 = new Auction('Fusca');
        $auction2->makeBid(new Bid(new User('Maria'), 8000));

        return [
            '3 Bids' => [3, $auction, [2000, 3000, 4000]],
            '1 Bid' => [1, $auction2, [8000]]
        ];
    }
}
