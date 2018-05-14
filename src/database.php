<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/3/18
 * Time: 8:18 AM
 */

class Database
{
    private $connection = null;

    private static $instance = null;

    public static function GetConnection(){
        return self::$instance
            ?? (self::$instance = self::make_connection());
    }

    private static function make_connection(): Database {
        if($con = mysqli_connect
            ( getenv("DB_HOST")
            , getenv("DB_USER")
            , getenv("DB_PASSWORD")
            , getenv("DB_DATABASE")
        )){
            self::$instance = new Database($con);
            return self::$instance;
        } else {
            http_redirect("error");
            die();
        }
    }

    public function __construct(mysqli $conn)
    {
        $this->connection = $conn;
    }

    /**
     * @param string $username
     * @param mysqli $con
     * @return int
     * @throws Exception
     */
    function get_user_count(string $username) {
        if($ps = $this->connection->prepare("SELECT COUNT(*) AS count FROM user WHERE username = ?")){
            $ps->bind_param("s", $username);
            $ps->execute();
            if($result = $ps->get_result()){
                return $result->fetch_assoc()['count'];
            }
            $ps->close();
        }
        throw new Exception("Error while connecting to the database. Try again later");
    }

    function update_user() {
        if($user = $_SESSION['current_user']){
            if(   ($fname = $_REQUEST['firstname'])
                && ($lname = $_REQUEST['lastname'])
                && ($pw    = $_REQUEST['password'])
                && ($email = $_REQUEST['email'])){

                $ps = null;
                if($pw != 'password'){
                    if($ps = $this->connection->prepare("UPDATE webshop.user SET firstname=?, lastname=?, password=?, email=? WHERE username = ?")){
                        $ps->bind_param("sssss", $fname, $lname, password_hash($pw, PASSWORD_DEFAULT), $email, $user->getUserName());
                    }
                }else{
                    if($ps = $this->connection->prepare("UPDATE webshop.user SET firstname=?, lastname=?, email=? WHERE username = ?")){
                        $ps->bind_param("ssss", $fname, $lname, $email, $user->getUserName());
                    }
                }
                if($ps){
                    $ps->execute();
                    $_SESSION['current_user'] = $this->db_get_user($user->getUserName());
                }
            }
        }
        $_SESSION['invalidate_user'] = true;
        header('Location: /user', true, 303);
        die();
    }

    /**
     * @param $fname
     * @param $lname
     * @param $user
     * @param $pw
     * @param $email
     * @return bool
     * @throws Exception
     */
    function try_create_user(string $fname, string $lname, string $user, string $pw, string $email ): bool {

        if($this->get_user_count($user) === 0) {
            $hash = password_hash($pw, PASSWORD_DEFAULT);

            $ps = $this->connection->prepare("INSERT INTO user VALUE (?, ?, ?, ?, ?, FALSE , FALSE)");
            $ps->bind_param("sssss", $user, $hash, $fname, $lname, $email);

            return $ps->execute();
        } else {
            throw new Exception("Username $user already exists!");
        }

    }

    function db_insert_ldap_user(User $user, $pw): bool
    {
        $admin = 0;
        $ldap = 1;
        if($ps = $this->connection->prepare("INSERT INTO user VALUES (?, ?, ?, ?, ?, ?, ?)")){
            if( $ps->bind_param("sssssii",
                    $user->getUserName(),
                    password_hash($pw, PASSWORD_DEFAULT),
                    $user->getFirstName(),
                    $user->getLastName(),
                    $user->getEmail(),
                    $user->isAdmin(),
                    $ldap)){
                return $ps->execute();
            }
        }
        return false;
    }

    function db_get_user(string $user, string $pw = ''): ?User
    {

        if ($ps = $this->connection->prepare("SELECT username, password, firstname, lastname, email, is_admin, is_ldap FROM user WHERE username = ? AND is_ldap = false")) {
            if ($ps->bind_param("s", $user)) {
                if ($ps->execute()) {
                    if ($res = $ps->get_result()) {
                        if ($user_row = $res->fetch_assoc()) {
                            if($pw !== '') {
                                if (password_verify($pw, $user_row['password'])) {
                                    return new User($user_row['username'], $user_row['firstname'], $user_row['lastname'], $user_row['email'], $user_row['is_ldap'], $user_row['is_admin']);
                                } else {
                                    return null;
                                }
                            }else{
                                return new User($user_row['username'], $user_row['firstname'], $user_row['lastname'], $user_row['email'], $user_row['is_ldap'], $user_row['is_admin']);
                            }
                        }
                    }
                }
            }
        }
        return null;
    }

    function disconnect(){
        $this->connection->close();
        $this::$instance = null;
    }
}