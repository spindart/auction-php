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
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('User cannot make two consecutive bids.');
        $auction = new Auction('BMW');

        $fernanda = self::createMock(User::class);
        $fernanda->method('getName')->willReturn('Fernanda');

        $bid1 = $this->getMockBuilder(Bid::class)->setConstructorArgs([$fernanda, 2000])->getMock();
        $bid1->method('getValue')->willReturn(2000.0);

        $bid2 = $this->getMockBuilder(Bid::class)->setConstructorArgs([$fernanda, 2500])->getMock();
        $bid2->method('getValue')->willReturn(2500.0);

        $auction->makeBid($bid1);
        $auction->makeBid($bid2);
    }

    public function testAuctionMustGetDescription(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('No description was given for the auction.');
        new Auction('');
    }
    public function testBidMustNotBeLowerThanPreviousBid(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('New bid must be higher than the current highest bid.');
        $auction = new Auction('Camaro');
        $fernanda = self::createMock(User::class);
        $fernanda->method('getName')->willReturn('Fernanda');
        $pedro = self::createMock(User::class);
        $pedro->method('getName')->willReturn('Pedro');


        $bid1 = $this->getMockBuilder(Bid::class)->setConstructorArgs([$fernanda, 1000])->getMock();
        $bid1->method('getValue')->willReturn(2000.0);

        $bid2 = $this->getMockBuilder(Bid::class)->setConstructorArgs([$pedro, 800])->getMock();
        $bid2->method('getValue')->willReturn(800.0);

        $auction->makeBid($bid1);
        $auction->makeBid($bid2);
    }

    public function testAuctionShouldNotAcceptMoreThanFiveBidsPerUser(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('User cannot place more than five bids in the same auction.');

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
