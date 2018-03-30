<?php
include_once "user.php";
session_start();
spl_autoload_register();
include_once "login.php";

$request = new \Request();
handle_login($request);
if($request->location[0] == 'api') include "api.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Hello World</title>

        <!--Import Google Icon Font-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!--Import materialize.css-->
        <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="css/dropify.min.css">
        <link type="text/css" rel="stylesheet" href="css/main.css">

        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body>
        <?php
            include_once "templates/nav_template.php";
            include_once "templates/header_template.php";
            include_once "templates/main_template.php";
            include_once "templates/footer_template.php";
        ?>
    </body>
    <!-- Compiled and minified JavaScript -->
    <script type="text/javascript" src="js/libs/jquery-3.3.1.min.js"></script>
    <script src="js/libs/materialize.min.js"></script>
    <script src="js/libs/imagesloaded.pkgd.min.js"></script>
    <script src="js/libs/masonry.min.js"></script>
    <script src="js/libs/dropify.min.js"></script>
    <script src="js/main.js"></script>

</html>