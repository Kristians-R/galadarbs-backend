<?php

namespace validation;

class ErrorMessage
{
    public array $errors = array();

    public function findErrors($type, $value) {

        switch ($type) {
            case "wrongId":
                $this->addError("Please enter a valid ID  value");
                break;
            case "required":
                $this->addError("ID is required");
                break;
            case "noUser":
                $this->addError("There is no user with ID " . $value);
                break;

        }
    }

    public function addError($msg) {
        array_push($this->errors, $msg);
    }

    public function error() {
        return $this->errors;
    }
}