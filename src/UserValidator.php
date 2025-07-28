<?php

namespace PhpWeb;

class UserValidator
{
    public function validate(array $user, UserDAO $dao): array
    {
        // BEGIN (write your solution here)
        $errors = [];

        foreach ($user as $field => $value) {
            if (empty($value)) {
                $errors[$field] = "Can't be blank";
            }
        }

        if ($user['email'] && !str_contains($user['email'], '@')) {
            $errors['email'] = "Must contains '@";
        }

        if ($dao->findByEmail($user['email'])) {
            $errors['email'] = 'Email is busy';
        }
        return $errors;
        // END
    }
}