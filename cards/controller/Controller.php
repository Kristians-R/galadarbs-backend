<?php

namespace cards;

use request\Request;
use response\RequestResponse;
use validation\Deleted;
use validation\NumberValidator;
use LogicException;
use validation\StringValidator;

class Controller
{
    private CardsRepository $cardsRepository;
    private RequestResponse $requestResponse;

    public function __construct(CardsRepository $cardsRepository, $response)
    {
        $this->cardsRepository = $cardsRepository;
        $this->requestResponse = $response;
    }

    public function cardsList()
    {
        $cards = new \stdClass();
        $obtainedCards = $this->cardsRepository->findCards();
        foreach ($obtainedCards as $card) {
            $cards->cards[] = $card->serialize();
        }
        $this->requestResponse->respond(true, $cards);
    }

    public function selectCard()
    {
        $id = Request::getRequestParam("id");
        if (!NumberValidator::validate($id) ) {
            $this->requestResponse->respond(false, "Invalid input");
            return;
        }
        $foundCard = $this->cardsRepository->findCard($id);
        if ($foundCard !== null) {
            $this->requestResponse->respond(true, $foundCard->serialize());
            return;
        } else {
            $this->requestResponse->respond(false, "Card not found in database!");
            return;
        }
    }

    public function cardsCreate(): void
    {
        $number = Request::getRequestParam("number");
        $type = Request::getRequestParam("type");
        $period = Request::getRequestParam("period");
        $issued = Request::getRequestParam("issued");
        $expired = Request::getRequestParam("expired");
        $sum = Request::getRequestParam("sum");
        $status = Request::getRequestParam("status");
        if (!StringValidator::validate($number)) {
            $this->requestResponse->respond(false, "Invalid input");
        }

        $cards = new Cards($number, $type, $period, $issued, $expired, $sum, $status);

        try {
            $createCard = $this->cardsRepository->cardsCreate($cards);
        } catch (LogicException $exception) {
            $this->requestResponse->respond(false, $exception->getMessage());
            return;
        }

        if ($createCard !== null) {
            $this->requestResponse->respond(true, $createCard->serialize());
            return;
        }
        $this->requestResponse->respond(false, null);
    }

    public function cardsUpdate(): void
    {
        $id = Request::getRequestParam("id");
        if (!NumberValidator::validate($id)) {
            $this->requestResponse->respond(false, "Invalid input");
            return;
        }

        $foundCard = $this->cardsRepository->findCard($id);
        if ($foundCard !== null) {
            $updatedCard = $this->cardsRepository->update($this->updateFromRequestCard($foundCard));
            $this->requestResponse->respond(true, $updatedCard->serialize());
            return;
        } else {
            $this->requestResponse->respond(false, "Card not found");
            return;
        }
    }

    private function updateFromRequestCard(Cards $cards): Cards
    {
        $number = Request::getRequestParam("number") ?? $cards->getNumber();
        $type = Request::getRequestParam("type") ?? $cards->getType();
        $period = Request::getRequestParam("period") ?? $cards->getPeriod();
        $issued = Request::getRequestParam("issued") ?? $cards->getIssued();
        $expired = Request::getRequestParam("expired") ?? $cards->getExpired();
        $sum = Request::getRequestParam("sum") ?? $cards->getSum();
        $status = Request::getRequestParam("status") ?? $cards->getStatus();

        $newCards = new Cards($number, $type, $period, $issued, $expired, $sum, $status);
        $newCards->setId($cards->getId());
        $newCards->setDeleted($cards->getDeleted());

        return $newCards;
    }

    public function cardDelete(): void
    {
        $id = Request::getRequestParam("id");
        if (!NumberValidator::validate($id)) {
            $this->requestResponse->respond(false, "Invalid input");
            return;
        }
        $foundCard = $this->cardsRepository->findCard($id);
        if ($foundCard !== null) {
            $this->cardsRepository->delete($foundCard);
            $this->requestResponse->respond(true, "Card deleted");
            return;
        } else {
            $this->requestResponse->respond(false, "Card not found in database!");
            return;
        }
    }
}