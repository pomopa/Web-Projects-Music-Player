<?php

namespace App\Validation;

class CustomRules
{
    /**
     * Check if the password has a number, an upperCase and a lowerCase
     *
     * @param string $str The input value.
     * @return bool
     */
    public function special_password_rule(string $str): bool
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $str);
    }

    /**
     * Check if the email is from one of the accepted domains
     *
     * @param string $str The input value.
     * @return bool
     */
    public function is_from_domain(string $str): bool
    {
        return (str_ends_with($str, '@students.salle.url.edu') ||
                str_ends_with($str, '@ext.salle.url.edu') ||
                str_ends_with($str, '@salle.url.edu'));
    }

    /**
     * Check if string is a number or is empty
     *
     * @param string $str The input value.
     * @return bool
     */
    public function is_numberOrEmpty(string $str): bool
    {
        if (empty($str)) {
            return true;
        }

        if (preg_match('/^[0-9]+$/', $str)) {
            return true;
        }

        return false;

    }

    /**
     * Check if the email is unique
     *
     * @param string $str The input value.
     * @return bool
     */
    public function is_email_unique(string $str): bool
    {
        $userModel = new \App\Models\UserModel();

        $user = $userModel->where('email', $str)->first();

        if ($user) {
            return false;
        }
        return true;
    }

    /**
     * Check if the email is unique
     *
     * @param string $str The input value.
     * @return bool
     */
    public function is_email_in_system(string $str): bool
    {
        $userModel = new \App\Models\UserModel();

        $user = $userModel->where('email', $str)->first();

        if ($user) {
            return true;
        }
        return false;
    }

    /**
     * Check if the email is unique
     *
     * @param string $str The input value.
     * @return bool
     */
    public function number_positive(string $str): bool
    {
        $numero = trim($str);
        if (is_numeric($numero) && $numero > 0) {
            return true;
        }
        return false;
    }

}
