<?php

namespace cards;

class Cards
{
    private int $id;
    private string $number;
    private string $type;
    private string $period;
    private string $issued;
    private string $expired;
    private int $sum;
    private string $status;
    private bool $deleted;

    public function __construct(string $number, string $type, string $period, string $issued, string $expired, string $sum, string $status)
    {
        $this->number = $number;
        $this->type = $type;
        $this->period = $period;
        $this->issued = $issued;
        $this->expired = $expired;
        $this->sum = $sum;
        $this->status = $status;
        $this->deleted = false;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNumber(): string
    {
        $generate = "PC00000000";
        $addGenerate = substr($generate,2)+1;
        $generate = "PC" . Str_pad($addGenerate,8,0,STR_PAD_LEFT);
        return $this->number = $generate . $this->type . $this->period;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPeriod(): string
    {
        return $this->period;
    }

    public function getIssued(): string
    {
        $today = date("j-n-Y");
        $this->issued = $today;
        return $this->issued;
    }

    public function getExpired(): string
    {
        $today = date("j-n-Y");
        $addMonths = strtotime("+$this->period months",strtotime("$today"));
        $expiredDate = date("j-n-Y",$addMonths);;
        $this->expired = $expiredDate;
        return $this->expired;
    }

    public function getSum(): int
    {
        return $this->sum;
    }

    public function getStatus(): string
    {
        return $this->status = "Active";
    }

    public function getDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): void
    {
        $this->deleted = $deleted;
    }

    public function serialize(): CardsDto
    {
        $cardDto = new CardsDto();
        $cardDto->id = $this->id;
        $cardDto->number = $this->number;
        $cardDto->type = $this->type;
        $cardDto->period = $this->period;
        $cardDto->issued = $this->issued;
        $cardDto->expired = $this->expired;
        $cardDto->sum = $this->sum;
        $cardDto->status = $this->status;

        return $cardDto;
    }
}