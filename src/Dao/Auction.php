<?php

namespace Auction\Dao;

use Auction\Model\Auction as ModelAuction;

class Auction
{
    private $con;

    public function __construct(\PDO $con)
    {
        $this->con = $con;
    }

    /**
     * Saves a new auction to the database.
     *
     * @param ModelAuction $auction The auction object to be saved.
     * @return void
     */
    public function save(ModelAuction $auction): ModelAuction
    {
        $sql = 'INSERT INTO auctions (description, finished, initialDate) VALUES (?, ?, ?)';
        $stm = $this->con->prepare($sql);
        $stm->bindValue(1, $auction->getDescription(), \PDO::PARAM_STR);
        $stm->bindValue(2, $auction->isFinished(), \PDO::PARAM_BOOL);
        $stm->bindValue(3, $auction->getInitialDate()->format('Y-m-d'));
        $stm->execute();

        return
            new ModelAuction(
                $auction->getDescription(),
                $auction->getInitialDate(),
                $this->con->lastInsertId()
            );
    }

    /**
     * Retrieves all auctions that are not finished.
     *
     * @return ModelAuction[] An array of ModelAuction objects representing the not finished auctions.
     */
    public function retrieveNotFinished(): array
    {
        return $this->retrieveAuctionIfFinished(false);
    }

    /**
     * Retrieves all auctions that are finished.
     *
     * @return ModelAuction[] An array of ModelAuction objects representing the finished auctions.
     */
    public function retrieveFinished(): array
    {
        return $this->retrieveAuctionIfFinished(true);
    }

    /**
     * Retrieves auctions based on their finalization status.
     *
     * @param bool $finished Whether to retrieve finished (true) or not finished (false) auctions.
     * @return ModelAuction[] An array of ModelAuction objects representing the auctions with the specified finalization status.
     */
    private function retrieveAuctionIfFinished(bool $finished): array
    {
        $sql = 'SELECT * FROM auctions WHERE finished = ' . ($finished ? 1 : 0);
        $stm = $this->con->query($sql, \PDO::FETCH_ASSOC);

        $dataList = $stm->fetchAll();
        $auctions = [];
        foreach ($dataList as $data) {
            $auction = new ModelAuction($data['description'], new \DateTimeImmutable($data['initialDate']), $data['id']);
            if ($data['finished']) {
                $auction->finish();
            }
            $auctions[] = $auction;
        }

        return $auctions;
    }

    /**
     * Updates an existing auction in the database.
     *
     * @param ModelAuction $auction The auction object with updated information.
     * @return void
     */
    public function update(ModelAuction $auction): void
    {
        $sql = 'UPDATE auctions SET description = :description, initialDate = :initialDate, finished = :finished WHERE id = :id';
        $stm = $this->con->prepare($sql);
        $stm->bindValue(':description', $auction->getDescription());
        $stm->bindValue(':initialDate', $auction->getInitialDate()->format('Y-m-d'));
        $stm->bindValue(':finished', $auction->isFinished(), \PDO::PARAM_BOOL);
        $stm->bindValue(':id', $auction->getId(), \PDO::PARAM_INT);
        $stm->execute();
    }
}
