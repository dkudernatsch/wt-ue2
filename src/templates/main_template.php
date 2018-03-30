<main>
    <?php
        switch ($request->location[0]){

            case 'products': include "main/products_template.php";
                break;

            case '':
            case 'home': include 'main/home_template.php';
                break;
            case 'gallery': include 'main/gallery_template.php';
                break;
            case 'cart': include 'main/shopping_cart.php';
                break;
        }
    ?>
</main>