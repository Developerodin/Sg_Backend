<?php

namespace App\Validation;

use App\Models\UserModel;
use Exception;

class UserRules
{
    public function validateUser(array $input): bool
    {



        try {
            $model = new UserModel();
            $user = $model->findUserByUserName($input['user_name']);
            return password_verify($input['pin'], $user['pin']);
        } catch (Exception $e) {
            return false;
        }
    }
}
