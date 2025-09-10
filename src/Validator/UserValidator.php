<?php

namespace PhpWeb\Validator;

use PhpWeb\Repository\UserRepository;

class UserValidator
{
    public function validateEmpty(array $user): array
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


        return $errors;
        // END
    }

    public function validateUniqEmail($email, UserRepository $dao): array
    {
        $errors = [];
        if ($dao->findByEmail($email)) {
            $errors['email'] = 'Email is busy';
        }

        return $errors;
    }
}