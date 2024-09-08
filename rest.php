<?php

use Auction\Dao\Auction as AuctionDao;
use Auction\Domain\Model\Auction;

require_once __DIR__ . '/vendor/autoload.php';

$pdo = new \PDO('sqlite::memory:');
$pdo->exec('create table auctions (
    id INTEGER primary key,
    description TEXT,
    finished BOOL,
    initialDate TEXT
);');
$auctionDao = new AuctionDao($pdo);

$auction1 = new Auction('Auction 1');
$auction2 = new Auction('Auction 2');
$auction3 = new Auction('Auction 3');
$auction4 = new Auction('Auction 4');

$auctionDao->save($auction1);
$auctionDao->save($auction2);
$auctionDao->save($auction3);
$auctionDao->save($auction4);

header('Content-type: application/json');
echo json_encode(array_map(function (Auction $auction) {
    return [
        'description' => $auction->getDescription(),
        'isFinished' => $auction->isFinished(),
    ];
}, $auctionDao->retrieveNotFinished()));
