<?php

namespace Tdd\Auction\Tests\Service;

use PHPUnit\Framework\TestCase;
use Tdd\Auction\Model\Auction;
use Tdd\Auction\Model\Bid;
use Tdd\Auction\Model\User;
use Tdd\Auction\Service\Evaluator;

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
}
