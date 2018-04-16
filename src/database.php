<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/3/18
 * Time: 8:18 AM
 */

class Database
{
    private static $connection = null;

    public static function GetConnection(){
        return self::$connection
            ?? (self::$connection = self::make_connection());
    }

    private static function make_connection(): mysqli {
        if($con = mysqli_connect
            ( getenv("DB_HOST")
            , getenv("DB_USER")
            , getenv("DB_PASSWORD")
            , getenv("DB_DATABASE")
        )){
            return $con;
        } else {
            http_redirect("error");
            die();
        }
    }

}