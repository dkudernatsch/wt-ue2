<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 30.03.18
 * Time: 17:49
 */

class User
{

    private $user_name;
    private $first_name;
    private $last_name;
    private $email;
    private $is_admin;
    private $is_ldap;

    function __construct(string $user, string $fname, string $lname, string $mail, bool $is_ldap, bool $is_admin)
    {
        $this->user_name = $user;
        $this->first_name = $fname;
        $this->last_name = $lname;
        $this->email = $mail;
        $this->is_admin = $is_admin;
        $this->is_ldap = $is_ldap;
    }


    public static function make_User($user, $info): User
    {
        if ($info['givenname']
            && $info['sn']
            && $info['mail']) {

            return new User($user, $info['givenname'][0], $info['sn'][0], $info['mail'][0], true, false);
        } else {
            return null;
        }
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->user_name;
    }

    /**
     * @return bool
     */
    public function isLdap(): bool
    {
        return $this->is_ldap;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }
}