<?php

use Tdd\Auction\Model\Bid;
use Tdd\Auction\Model\Auction;
use Tdd\Auction\Model\User;
use Tdd\Auction\Service\Appraiser;

require 'vendor/autoload.php';

// Arrange - Given
$auction = new Auction('Lambo');
$user1 = new User('Jose');
$user2 = new User('Maria');

$auction->makeBid(new Bid($user1, 2000));
$auction->makeBid(new Bid($user2, 3000));

$appraiser = new Appraiser();

// Act - When
$appraiser->evaluate($auction);
$highestValue = $appraiser->getHighestValue();

// Assert - Then
$expectedValue = 3000;
if ($highestValue == 3000) {
    echo "Test passed!";
} else {
    echo "Test failed: Expected highest value to be $expectedValue, but got $highestValue";
}
