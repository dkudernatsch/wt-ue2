<div class="navbar-fixed">
    <nav class="blue darken-2 z-depth-3">
        <div class="nav-wrapper">

            <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
            <ul class="left hide-on-med-and-down">
                <li <?php echo $request->location[0] === 'home' ||
                   $request->location[0] === '' ? "class='active'" : ""; ?>><a href="home">Home</a></li>

                <li <?php echo $request->location[0] == 'products' ? "class='active'" : ""; ?>><a href="products">Products</a></li>
                <li <?php echo $request->location[0] == 'gallery' ? "class='active'" : ""; ?>><a href="gallery">Gallery</a></li>
                <li><a href="news">News</a></li>

            </ul>
            <ul class="side-nav z-depth-5" id="mobile-demo">
                <li><a href="home">Home</a></li>
                <li><a href="products">Products</a></li>
                <li><a href="gallery">Gallery</a></li>
                <li><a href="news">News</a></li>

            </ul>

            <!-- show login btn or logout btn -->
            <?php if ($_SESSION['current_user']) { ?>
                <ul class="right">
                    <li>
                        <span><a href="user"><?php echo $_SESSION['current_user']->getFirstName()."&nbsp;";?></a></span>
                        <a id="login-activator" class="right btn-block waves-effect waves-block waves-light"
                           href="logout">
                            <i class="material-icons">exit_to_app</i>
                        </a>
                    </li>
                </ul>
            <?php } else { ?>
                <ul class="right">
                    <li>
                        <a id="register-activator" class="right btn-block waves-effect halfway-fab waves-block waves-light"
                           onclick="show('#register')">
                            Register
                        </a>
                    </li>
                </ul>
                <ul class="right">
                    <li>
                        <a id="login-activator" class="right btn-block waves-effect halfway-fab waves-block waves-light"
                           onclick="show('#login')">
                            <i class="material-icons">input</i>
                        </a>
                    </li>
                </ul>
            <?php } ?>

            <!-- show shopping cart only when logged in -->
            <?php if ($_SESSION['current_user']) { ?>
                <div class="fixed-action-btn">
                    <a class="btn-floating btn-large halfway-fab waves-effect waves-light blue darken-2" href="/cart">
                        <i class="material-icons">shopping_cart</i>
                    </a>
                </div>
            <?php } ?>


        </div>
    </nav>
</div>