<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 30.03.18
 * Time: 17:49
 */

class User
{

    private $first_name;
    private $last_name;
    private $email;

    function __construct(string $fname, string $lname, string $mail)
    {
        $this->first_name = $fname;
        $this->last_name = $lname;
        $this->email = $mail;
    }


    public static function make_User($info): User
    {
        if ($info['givenname']
            && $info['sn']
            && $info['mail']) {

            return new User($info['givenname'][0], $info['sn'][0], $info['mail'][0]);
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
}