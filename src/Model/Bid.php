<?php

/**
 * Represents a bid in an auction.
 *
 * @package Auction\Model
 */
namespace Auction\Model;
class Bid
{
    /** @var User The user who made the bid. */
    private $user;
    /** @var float The value of the bid. */
    private $value;

    /**
     * Bid constructor.
     *
     * @param User $user The user who made the bid.
     * @param float $value The value of the bid.
     */
    public function __construct(User $user, float $value)
    {
        $this->user = $user;
        $this->value = $value;
    }
    /**
     * Returns the user who made the bid.
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
    /**
     * Returns the value of the bid.
     *
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }
}
