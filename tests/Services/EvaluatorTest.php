<?php

namespace Auction\Tests\Service;

use PHPUnit\Framework\TestCase;
use Auction\Model\Auction;
use Auction\Model\Bid;
use Auction\Model\User;
use Auction\Service\Evaluator;

class EvaluatorTest extends TestCase
{
    public function testEvaluatorMustFindTheHighestValueOfBidsInAscendingOrder()
    {
        // Arrange - Given
        $auction = new Auction('Lambo');
        $user1 = new User('Jose');
        $user2 = new User('Maria');
        $user3 = new User('John');
        $user4 = new User('Patricia');

        $auction->makeBid(new Bid($user1, 1000));
        $auction->makeBid(new Bid($user2, 2000));
        $auction->makeBid(new Bid($user3, 3000));
        $auction->makeBid(new Bid($user4, 4000));

        $evaluator = new Evaluator();

        // Act - When
        $evaluator->evaluate($auction);
        $highestValue = $evaluator->getHighestValue();

        // Assert - Then
        self::assertEquals(4000, $highestValue);
    }
    public function testEvaluatorMustFindTheHighestValueOfBidsInDescendingOrder()
    {
        // Arrange - Given
        $auction = new Auction('Lambo');
        $user1 = new User('Jose');
        $user2 = new User('Maria');
        $user3 = new User('John');
        $user4 = new User('Patricia');

        $auction->makeBid(new Bid($user1, 4000));
        $auction->makeBid(new Bid($user2, 3000));
        $auction->makeBid(new Bid($user3, 2000));
        $auction->makeBid(new Bid($user4, 1000));

        $evaluator = new Evaluator();

        // Act - When
        $evaluator->evaluate($auction);
        $highestValue = $evaluator->getHighestValue();

        // Assert - Then
        self::assertEquals(4000, $highestValue);
    }

    public function testEvaluatorMustFindTheLowestValueOfBidsInAscedingOrder()
    {
        // Arrange - Given
        $auction = new Auction('Lambo');
        $user1 = new User('Jose');
        $user2 = new User('Maria');
        $user3 = new User('John');
        $user4 = new User('Patricia');

        $auction->makeBid(new Bid($user1, 1000));
        $auction->makeBid(new Bid($user2, 2000));
        $auction->makeBid(new Bid($user3, 3000));
        $auction->makeBid(new Bid($user4, 4000));

        $evaluator = new Evaluator();

        // Act - When
        $evaluator->evaluate($auction);
        $lowestValue = $evaluator->getLowestValue();

        // Assert - Then
        self::assertEquals(1000, $lowestValue);
    }

    public function testEvaluatorMustFindTheLowestValueOfBidsInDescendingOrder()
    {
        // Arrange - Given
        $auction = new Auction('Lambo');
        $user1 = new User('Jose');
        $user2 = new User('Maria');
        $user3 = new User('John');
        $user4 = new User('Patricia');

        $auction->makeBid(new Bid($user1, 4000));
        $auction->makeBid(new Bid($user2, 3000));
        $auction->makeBid(new Bid($user3, 2000));
        $auction->makeBid(new Bid($user4, 1000));



        $evaluator = new Evaluator();

        // Act - When
        $evaluator->evaluate($auction);
        $lowestValue = $evaluator->getLowestValue();

        // Assert - Then
        self::assertEquals(1000, $lowestValue);
    }

    public function testEvaluatorMustFindTheTop3HighestBids()
    {
        // Arrange - Given
        $auction = new Auction('Lambo');
        $user1 = new User('Jose');
        $user2 = new User('Maria');
        $user3 = new User('John');
        $user4 = new User('Patricia');
        $user5 = new User('Patricia');
        $user6 = new User('Sheila');
        $user7 = new User('Carlos');
        $user8 = new User('Carol');

        $auction->makeBid(new Bid($user1, 4000));
        $auction->makeBid(new Bid($user2, 3000));
        $auction->makeBid(new Bid($user3, 2000));
        $auction->makeBid(new Bid($user4, 1000));
        $auction->makeBid(new Bid($user5, 6000));
        $auction->makeBid(new Bid($user6, 7000));
        $auction->makeBid(new Bid($user7, 8000));
        $auction->makeBid(new Bid($user8, 9000));

        $evaluator = new Evaluator();

        // Act - When
        $evaluator->evaluate($auction);
        $topThreeBids = $evaluator->getBestBids();

        // Assert - Then
        self::assertCount(3, $topThreeBids);
        self::assertEquals(9000, $topThreeBids[0]->getValue());
        self::assertEquals(8000, $topThreeBids[1]->getValue());
        self::assertEquals(7000, $topThreeBids[2]->getValue());
    }

    public function testEvaluatorMustFindTheTop3LowestBids()
    {
        // Arrange - Given
        $auction = new Auction('Lambo');
        $user1 = new User('Jose');
        $user2 = new User('Maria');
        $user3 = new User('John');
        $user4 = new User('Patricia');
        $user5 = new User('Patricia');
        $user6 = new User('Sheila');
        $user7 = new User('Carlos');
        $user8 = new User('Carol');

        $auction->makeBid(new Bid($user1, 4000));
        $auction->makeBid(new Bid($user2, 3000));
        $auction->makeBid(new Bid($user3, 2000));
        $auction->makeBid(new Bid($user4, 1000));
        $auction->makeBid(new Bid($user5, 6000));
        $auction->makeBid(new Bid($user6, 7000));
        $auction->makeBid(new Bid($user7, 8000));
        $auction->makeBid(new Bid($user8, 9000));

        $evaluator = new Evaluator();

        // Act - When
        $evaluator->evaluate($auction);
        $topThreeLowestBids = $evaluator->getWorstBids();

        // Assert - Then
        self::assertCount(3, $topThreeLowestBids);
        self::assertEquals(1000, $topThreeLowestBids[0]->getValue());
        self::assertEquals(2000, $topThreeLowestBids[1]->getValue());
        self::assertEquals(3000, $topThreeLowestBids[2]->getValue());
    }
}
