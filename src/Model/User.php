<?php

namespace Auction\Model;

class User
{
    /**
     * @var string
     */
    private $name;

    /**
     * Constructs a new User object.
     *
     * @param string $name The name of the user.
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }
    /**
     * Returns the name of the user.
     *
     * @return string The name of the user.
     */
    public function getName(): string
    {
        return $this->name;
    }
}
