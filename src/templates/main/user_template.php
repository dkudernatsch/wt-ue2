
<?php
/**
 * @var User
 */
$user = $_SESSION['current_user'];
if(!$user->isLdap()) {
?>

    <div class="z-depth-1 grey lighten-4 row">

        <form class="row" action="api/update" method="post">

            <h3 class="col s12 blue-text text-darken-2">Update Account information</h3>

            <div class='col s12 m6 row'>
                <div class='input-field col s12'>
                    <input name='username' type="text" id='username' value="<? echo $user->getUserName() ?>" disabled/>
                    <label for="username">Your username</label>
                </div>
            </div>
            <div class='col s12 m6 row'>
                <div class='input-field col s12'>
                    <input name='email' type="email" id='email' value="<? echo $user->getEmail() ?>"/>
                    <label for='email'>Update email</label>
                </div>
            </div>
            <div class='col s12 m6 row'>
                <div class='input-field col s12'>
                    <input name='firstname' type="text" id='firstname' value="<? echo $user->getFirstname() ?>"/>
                    <label for='firstname'>Update first name</label>
                </div>
            </div>
            <div class='col s12 m6 row'>
                <div class='input-field col s12'>
                    <input name='lastname' type="text" id='lastname' value="<? echo $user->getLastname() ?>"/>
                    <label for='lastname'>Update last name</label>
                </div>
            </div>
            <div class='col s12 m6 row'>
                <div class='input-field col s12'>
                    <input name='password' type="password" id='password' value="<? echo "password" ?>"/>
                    <label for='password'>Update password</label>
                </div>
            </div>
            <br/>
            <div class='row'>
                <button type='submit' name='btn_login' class='col m6 s12 btn btn-large waves-effect indigo'>Update
                    Account
                </button>
            </div>
        </form>
    </div>
<?php
}else{
?>
    <div>Your Account type does not support changing information</div>
<?
}
?>