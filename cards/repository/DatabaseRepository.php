<?php

namespace cards;

use database\Database;
use LogicException;

require_once("cards/repository/DatabaseRepository.php");

class DatabaseRepository implements CardsRepository
{
    const TABLE_NAME = "cards";
    private Database $database;

    public function __construct()
    {
        $this->database = Database::getInstance();
    }

    public function findCards(): array
    {
        $result = [];
        $dbCards = $this->database->selectAll(self::TABLE_NAME);
        foreach ($dbCards as $dbCard) {
            if ($this->IsNotDeleted($dbCard)) {
                $result[] = $this->transformDbRowToCard($dbCard);
            }
        }
        return $result;
    }

    public function findCard(int $id): ?Cards
    {
        $where = [
            ["name" => "id", "operation" => "=", "val" => $id]
        ];
        $cardFromDb = $this->database->selectAllWhere(self::TABLE_NAME, $where);
        return empty($cardFromDb) ? null : $this->transformDbRowToCard($cardFromDb[0]);
    }

    public function cardsCreate(Cards $cards): ?Cards
    {
        $data = [
            ["val" => $cards->getNumber(), "type" => "char"],
            ["val" => $cards->getType(), "type" => "char"],
            ["val" => $cards->getPeriod(), "type" => "char"],
            ["val" => $cards->getIssued(), "type" => "char"],
            ["val" => $cards->getExpired(), "type" => "char"],
            ["val" => $cards->getSum(), "type" => "int"],
            ["val" => $cards->getStatus(), "type" => "char"],
        ];
        $fields = ["number", "type", "period", "issued", "expired", "sum", "status"];
        $isInserted = $this->database->insertInto(self::TABLE_NAME, $data, $fields);
        if(!$isInserted){
            throw new LogicException("Product already exists!");
        }
        $where = [
            ["name" => "number", "operation" => "=", "val" => $cards->getNumber()]
        ];
        $cardFromDb = $this->database->selectAllWhere(self::TABLE_NAME, $where);
        return empty($cardFromDb) ? null : $this->transformDbRowToCard($cardFromDb[0]);
    }

    private function transformDbRowToCard(array $dbCard): Cards
    {
        $card = new Cards(
            $dbCard["number"],
            $dbCard["type"],
            $dbCard["period"],
            $dbCard["issued"],
            $dbCard["expired"],
            $dbCard["sum"],
            $dbCard["status"]
        );
        $card->setId($dbCard["id"]);
        $card->setDeleted($dbCard["deleted"]);

        return $card;
    }

    public function update(Cards $cards): ?Cards
    {
        $data = [
            ["name" => "number", "val" => $cards->getNumber()],
            ["name" => "type", "val" => $cards->getType()],
            ["name" => "period", "val" => $cards->getPeriod()],
            ["name" => "issued", "val" => $cards->getIssued()],
            ["name" => "expired", "val" => $cards->getExpired()],
            ["name" => "sum", "val" => $cards->getSum()],
            ["name" => "status", "val" => $cards->getStatus()],
        ];
        $this->database->update(self::TABLE_NAME, $data, $cards->getId());

        $where = [
            ["name" => "id", "operation" => "=", "val" => $cards->getId()]
        ];
        $cardsFromDb = $this->database->selectAllWhere(self::TABLE_NAME, $where);

        return empty($cardsFromDb) ? null : $this->transformDbRowToCard($cardsFromDb[0]);
    }

    public function delete(Cards $cards): ?Cards
    {
        $data = [
            ["name" => "deleted", "val" => 1],
        ];
        $this->database->update(self::TABLE_NAME, $data, $cards->getId());

        $where = [
            ["name" => "id", "operation" => "=", "val" => $cards->getId()]
        ];
        $cardFromDb = $this->database->selectAllWhere(self::TABLE_NAME, $where);

        return empty($cardFromDb) ? null : $this->transformDbRowToCard($cardFromDb[0]);
    }

    public function isNotDeleted(array $dbCard): bool
    {
        return !$dbCard["deleted"];
    }
}