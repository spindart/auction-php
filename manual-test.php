<?php

use Auction\Model\Bid;
use Auction\Model\Auction;
use Auction\Model\User;
use Auction\Service\Evaluator;

require 'vendor/autoload.php';

// Arrange - Given
$auction = new Auction('Lambo');
$user1 = new User('Jose');
$user2 = new User('Maria');

$auction->makeBid(new Bid($user1, 2000));
$auction->makeBid(new Bid($user2, 3000));

$evaluator = new Evaluator();

// Act - When
$evaluator->evaluate($auction);
$highestValue = $evaluator->getHighestValue();

// Assert - Then
$expectedValue = 3000;
if ($highestValue == 3000) {
    echo "Test passed!";
} else {
    echo "Test failed: Expected highest value to be $expectedValue, but got $highestValue";
}
