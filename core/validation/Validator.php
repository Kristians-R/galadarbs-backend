<?php

namespace validation;

interface Validator
{
    public static function validate($input): bool;
}