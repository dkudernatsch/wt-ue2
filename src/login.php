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
        if($user = Database::GetConnection()->db_get_user($username, $pw)){
            $_SESSION['current_user'] = $user;

        }else if($user = ldap_get_user($username, $pw)){
            $_SESSION['current_user'] = $user;
            Database::GetConnection()->db_insert_ldap_user($user, $pw);
        }
    }

}

function logout()
{
    session_destroy();
    header("Location: /", true, 301);
    exit();
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