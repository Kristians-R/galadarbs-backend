<?php

namespace validation;

class NumberValidator implements Validator
{
    public static function validate($input): bool
    {
        return is_numeric($input);
    }
}