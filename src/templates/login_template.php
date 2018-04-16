<div id="login" class="dark-overlay">
    <div class="container center col s12 m6 l3">
        <div id="login-container">
            <div id="login-box" class="z-depth-1 grey lighten-4 row">

                <form action="<?php echo $_SERVER['REQUEST_URI']?>" method="post">

                    <a class="waves-effect waves-red btn-flat right" onclick="hide('#login')">
                        <i class="grey-text material-icons">close</i>
                    </a>

                    <h3 class="blue-text text-darken-2">Login</h3>

                    <div class='row'>
                        <div class='col s12'>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='input-field col s12'>
                            <input name='l_username' type="text" id='l_username'/>
                            <label for='l_username'>Enter your username</label>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='input-field col s12'>
                            <input class='validate' type='password' name='l_password' id='password'/>
                            <label for='l_password'>Enter your password</label>
                        </div>
                        <label style='float: right;'>
                            <a class='pink-text text-darken-4' href=''><b>Forgot Password?</b></a>
                        </label>
                    </div>
                    <br/>
                    <div class='row'>
                        <button type='submit' name='btn_login' class='col s12 btn btn-large waves-effect indigo'>Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>