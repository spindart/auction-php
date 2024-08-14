<?php

namespace Auction\Tests\Integration\Web;

use PHPUnit\Framework\TestCase;

class RestTest extends TestCase
{
    public function testApiRestShouldReturnArrayActions(): void
    {
        // Fetch the response from the API endpoint
        $response = file_get_contents('http://localhost:8000/rest.php');
        // Ensure the HTTP request was successful
        self::assertStringContainsString('200 OK', $http_response_header[0]);
        // Decode the JSON response into an array of objects
        $responseArray = json_decode($response);
        // Assert that the response is an array
        self::assertIsArray($responseArray);
        // Loop through each auction in the response
        foreach ($responseArray as $auction) {
            self::assertIsObject($auction);
            // Assert required properties exist and have correct types
            self::assertTrue(property_exists($auction, 'description'));
            self::assertTrue(property_exists($auction, 'isFinished'));
            self::assertIsString($auction->description);
            self::assertIsBool($auction->isFinished);
            // Assert that the description is not empty
            self::assertNotEmpty($auction->description, 'Description should not be empty');
            // Assert that the all auction is not finished 
            self::assertFalse($auction->isFinished);
        }
        // Assert the number of auctions in the response array
        self::assertCount(4, $responseArray);
    }
}
