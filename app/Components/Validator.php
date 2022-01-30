<?php

namespace App\Components;


class Validator
{
    /**
     * @return bool
     */

    public static function is_not_looged_in()
    {
        return empty($_SESSION['user']);
    }

    /**
     * @return bool
     */

    public static function is_admin() {
        return isset($_SESSION['user']['role']) &&  $_SESSION['user']['role'] == 'admin';
    }

    /**
     * @param $email
     * @return bool
     */

    public static function isEmail(string $email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @param array $required_fields
     * @return array
     */

    public static function requiredFields(array $required_fields) {
        $errorFields = [];

        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $errorFields[] = $field;
            }
        }

        return $errorFields;
    }

    public static function is_author($logged_user, $user){
        return $logged_user['id'] == $user['id'];
    }
}