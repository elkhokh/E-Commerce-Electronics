<?php


namespace App;

class Validate
{
    private static function validateRequired($value, $fieldName)
    {
        return empty($value) ? "$fieldName is required" : null;
    }

    private static function  validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? null : "Invalid email";
    }
    private static function validatePassword($password)
    {
        if (strlen($password) < 6) {
            return "Password must be 6 char";
        }
        if (!preg_match("/[A-Z]/", $password)) {
            return "Password must contain uppercase";
        }
        if (!preg_match("/[a-z]/", $password)) {
            return "Password must contain lowercase";
        }
        if (!preg_match("/[0-9]/", $password)) {
            return "Password must contain number";
        }
        return null;
    }

    static function validateRegister($name, $email, $password): ?string
    {
        $date =
            [
                "name" => $name,
                "email" => $email,
                "password" => $password
            ];

        foreach ($date as $key => $value) {
            if ($error = self::validateRequired($value, $key)) {
                return $error;
            }
        }

        if ($error = self::validateEmail($email)) {
            return $error;
        }

        if ($error = self::validatePassword($password)) {
            return $error;
        }
        return null;
    }

    static function validate_Login($email, $password)
    {
        $date =
            [
                "email" => $email,
                "password" => $password
            ];

        foreach ($date as $key => $value) {
            if ($error = self::validateRequired($value, $key)) {
                return $error;
            }
        }

        if ($error = self::validateEmail($email)) {
            return $error;
        }

        return null;
    }
    static function validate_change_password($email, $password)
    {
        $date =
            [
                "email" => $email,
                "password" => $password
            ];

        foreach ($date as $key => $value) {
            if ($error = self::validateRequired($value, $key)) {
                return $error;
            }
        }
        if ($error = self::validatePassword($password)) {
            return $error;
        }

        if ($error = self::validateEmail($email)) {
            return $error;
        }

        return null;
    }
    static function validate_order($first_name, $last_name,$company,$country,$address_street,$address_apartment,$city,$state,$phone,$email)
    {
        $date =
            [
                "first_name" => $first_name,
                "last_name" => $last_name,
                "company" => $company,
                "country" => $country,
                "address_street" => $address_street,
                "address_apartment" => $address_apartment,
                "city" => $city,
                "state" => $state,
                "phone" => $phone,
                "email" => $email
            ];

        foreach ($date as $key => $value) {
            if ($error = self::validateRequired($value, $key)) {
                return $error;
            }
        }

        return null;
    }
    
}
