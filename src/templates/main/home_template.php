
<section id="home" class="col l6 m8 s12">
    <h1 class="center-align">Home</h1>
    <?php
    if($_SESSION['current_user']) {
        echo "hello " . $_SESSION["current_user"]->getFirstName() . "<br> have a nice day!";
    }
    ?>
</section>