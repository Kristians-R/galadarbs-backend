<?php

namespace validation;

class StringValidator implements Validator
{
    public static function validate($input): bool
    {
        return is_string($input);
    }
}