<?php

namespace Auction\Domain\Model;

class User
{
    private string $name;

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
