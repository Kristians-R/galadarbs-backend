<?php

namespace response;

class RequestResponseXml implements RequestResponse
{
    public function __construct()
    {
    }

    public function respond(bool $status, $data): void
    {
        echo "XML response";
    }
}