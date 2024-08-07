<?php

namespace Auction\Tests\Service;

use PHPUnit\Framework\TestCase;
use Auction\Model\Auction;
use Auction\Model\Bid;
use Auction\Model\User;
use Auction\Service\Evaluator;

class EvaluatorTest extends TestCase
{
    private $evaluator;

    protected function setUp(): void
    {
        $this->evaluator = new Evaluator();
    }

    /**
     * @dataProvider auctionInAscedingOrder
     * @dataProvider auctionInDescendingOrder
     * @dataProvider auctionInRandomOrder
     */
    public function testEvaluatorMustFindTheHighestValueOfBids(Auction $auction): void
    {
        // Act - When
        $this->evaluator->evaluate($auction);
        $highestValue = $this->evaluator->getHighestValue();

        // Assert - Then
        self::assertEquals(4000, $highestValue);
    }

    /**
     * @dataProvider auctionInAscedingOrder
     * @dataProvider auctionInDescendingOrder
     * @dataProvider auctionInRandomOrder
     */
    public function testEvaluatorMustFindTheLowestValueOfBids(Auction $auction): void
    {
        // Act - When
        $this->evaluator->evaluate($auction);
        $lowestValue = $this->evaluator->getLowestValue();

        // Assert - Then
        self::assertEquals(1000, $lowestValue);
    }

    /**
     * @dataProvider auctionInAscedingOrder
     * @dataProvider auctionInDescendingOrder
     * @dataProvider auctionInRandomOrder
     */
    public function testEvaluatorMustFindTheTop3HighestBids(Auction $auction): void
    {
        // Act - When
        $this->evaluator->evaluate($auction);
        $topThreeBids = $this->evaluator->getBestBids();

        // Assert - Then
        self::assertCount(3, $topThreeBids);
        self::assertEquals(4000, $topThreeBids[0]->getValue());
        self::assertEquals(3000, $topThreeBids[1]->getValue());
        self::assertEquals(2000, $topThreeBids[2]->getValue());
    }

    /**
     * @dataProvider auctionInAscedingOrder
     * @dataProvider auctionInDescendingOrder
     * @dataProvider auctionInRandomOrder
     */
    public function testEvaluatorMustFindTheTop3LowestBids(Auction $auction): void
    {
        // Act - When
        $this->evaluator->evaluate($auction);
        $topThreeLowestBids = $this->evaluator->getWorstBids();

        // Assert - Then
        self::assertCount(3, $topThreeLowestBids);
        self::assertEquals(1000, $topThreeLowestBids[0]->getValue());
        self::assertEquals(2000, $topThreeLowestBids[1]->getValue());
        self::assertEquals(3000, $topThreeLowestBids[2]->getValue());
    }

    public function testAuctionWithoutBidsCannotBeEvaluted()
    {
        $this->expectException((\DomainException::class));
        $this->expectExceptionMessage("You can't evaluate an auction without bids");

        $auction = new Auction('Monza');
        $this->evaluator->evaluate($auction);
    }

    public function testFinalizedAuctionCannotBeFinalized()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("Auction is already finalized");

        $auction = new Auction('Monza');
        $auction->makeBid(new Bid(new User('Jose'), 1000));
        $auction->finish();

        $this->evaluator->evaluate($auction);
    }

    public function testFinishedAuctionCannotReceiveBids()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("The auction has already been concluded and cannot receive bids");

        $auction = new Auction('Monza');
        $auction->makeBid(new Bid(new User('Jose'), 1000));
        $auction->finish();
        $auction->makeBid(new Bid(new User('Pedro'), 1500));
        
    }

    /* ------ Arrange - Given ------ */
    public static function auctionInAscedingOrder(): array
    {
        $auction = new Auction('Lambo');
        $user1 = new User('Jose');
        $user2 = new User('Maria');
        $user3 = new User('John');
        $user4 = new User('Patricia');

        $auction->makeBid(new Bid($user1, 1000));
        $auction->makeBid(new Bid($user2, 2000));
        $auction->makeBid(new Bid($user3, 3000));
        $auction->makeBid(new Bid($user4, 4000));

        return [
            'Asceding Order' =>  [$auction]
        ];
    }

    public static function auctionInDescendingOrder(): array
    {
        $auction = new Auction('Lambo');
        $user1 = new User('Jose');
        $user2 = new User('Maria');
        $user3 = new User('John');
        $user4 = new User('Patricia');

        $auction->makeBid(new Bid($user1, 4000));
        $auction->makeBid(new Bid($user2, 3000));
        $auction->makeBid(new Bid($user3, 2000));
        $auction->makeBid(new Bid($user4, 1000));

        return [
            'Desceding Order' =>  [$auction]
        ];
    }

    public static function auctionInRandomOrder(): array
    {
        $auction = new Auction('Lambo');
        $user1 = new User('Jose');
        $user2 = new User('Maria');
        $user3 = new User('John');
        $user4 = new User('Patricia');

        $auction->makeBid(new Bid($user4, 1000));
        $auction->makeBid(new Bid($user1, 4000));
        $auction->makeBid(new Bid($user3, 2000));
        $auction->makeBid(new Bid($user2, 3000));

        return [
            'Random Order' =>   [$auction]
        ];
    }
}
