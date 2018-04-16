<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 3/8/18
 * Time: 9:09 AM
 * @param Request $request
 */

function handle_login(Request $request)
{

    if ($request->location[0] === 'logout') {
        logout();
    }

    if ($request->location[0] === 'cart' && !$_SESSION['current_user']) {
        header("Location: /", true, 301);
        exit();
    }

    if (($pw = $_REQUEST['l_password']) && ($username = $_REQUEST['l_username'])) {
        if($user = db_get_user($username, $pw)){
            $_SESSION['current_user'] = $user;

        }else if($user = ldap_get_user($username, $pw)){
            $_SESSION['current_user'] = $user;
            db_insert_ldap_user($user, $pw);
        }
    }

}

function logout()
{
    session_destroy();
    header("Location: /", true, 301);
    exit();
}

function db_insert_ldap_user(User $user, $pw): bool
{
    if ($con = Database::GetConnection()) {
        $admin = 0;
        $ldap = 1;
        if($ps = $con->prepare("INSERT INTO user VALUES (?, ?, ?, ?, ?, ?, ?)")){
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
    }
    return false;
}

function db_get_user(string $user, string $pw = ''): ?User
{
    if ($con = Database::GetConnection()) {
        if ($ps = $con->prepare("SELECT username, password, firstname, lastname, email, is_admin, is_ldap FROM user WHERE username = ? AND is_ldap = false")) {
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
    }
    return null;
}

function ldap_get_user($user, $pw): ?User
{
    $ldapserver = $_ENV['LDAP_SERVER'];
    $searchbase = $_ENV['LDAP_SEARCHBASE'];

    $loginname = strtolower($user);

    $dn = "uid=$loginname,ou=People," . $searchbase;

    if ($ds = ldap_connect($ldapserver)) {

        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);

        if (ldap_start_tls($ds)) {
            if (@ldap_bind($ds, $dn, $pw)) {
                $record = ldap_search($ds, $dn, "(objectclass=*)");
                $info = ldap_get_entries($ds, $record);

                return User::make_User($user, $info[0]);

            } else {
                //bind failed
            }
        } else {
            //tls failed
        }
    } else {
        //connect failed
    }
    return null;

}