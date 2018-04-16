<div id="register" class="dark-overlay">
    <div class="container center col s12 m6 l3">
        <div id="login-container">
            <div id="login-box" class="z-depth-1 grey lighten-4 row">

                <form class="row" action="api/register" method="post">

                    <a class="col s1 waves-effect waves-red btn-flat right" onclick="hide('#register')">
                        <i class="grey-text material-icons">close</i>
                    </a>

                    <h3 class="col s12 blue-text text-darken-2">Register</h3>

                    <div class='col s12 m6 row'>
                        <div class='input-field col s12'>
                            <input name='username' type="text" id='username'/>
                            <label for='username'>Enter your username</label>
                        </div>
                    </div>
                    <div class='col s12 m6 row'>
                        <div class='input-field col s12'>
                            <input name='email' type="email" id='email'/>
                            <label for='email'>Enter your email</label>
                        </div>
                    </div>
                    <div class='col s12 m6 row'>
                        <div class='input-field col s12'>
                            <input name='firstname' type="text" id='firstname'/>
                            <label for='firstname'>Enter your first name</label>
                        </div>
                    </div>
                    <div class='col s12 m6 row'>
                        <div class='input-field col s12'>
                            <input name='lastname' type="text" id='lastname'/>
                            <label for='lastname'>Enter your last name</label>
                        </div>
                    </div>
                    <div class='col s12 m6 row'>
                        <div class='input-field col s12'>
                            <input class='validate' type='password' name='password' id='password'/>
                            <label for='password'>Enter your password</label>
                        </div>
                    </div>
                    <div class='col s12 m6 row'>
                        <div class='input-field col s12'>
                            <input class='validate' type='password' name='password_dup' id='password_dup'/>
                            <label for='password_dup'>Enter your password again</label>
                        </div>
                    </div>
                    <br/>
                    <p id="register-error" class="red-text">This is a error message<p>
                    <div class='row'>
                        <button type='submit' name='btn_login' class='col m6 s12 btn btn-large waves-effect indigo'>Submit registration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>