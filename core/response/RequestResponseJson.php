<?php

namespace response;

class RequestResponseJson implements RequestResponse
{
    public bool $status;
    public $data;

    public function respond(bool $status, $data): void
    {
        $this->setHeaders();
        $this->status = $status;
        $this->data = $data;
        echo json_encode($this);
    }

    public function setHeaders(): void
    {
        header('Content-Type: application/json');
    }
}