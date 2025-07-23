<?php

namespace PhpWeb;

class Validator
{
    public function validate(array $user)
    {
        // BEGIN (write your solution here)
        $errors = [];
        if (empty($user['name'])) {
            $errors['name'] = "Can't be blank";
        }
        return $errors;
        // END
    }
}