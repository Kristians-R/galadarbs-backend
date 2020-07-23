<?php

namespace response;

interface RequestResponse
{
    function respond(bool $status, $data): void;
}