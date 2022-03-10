<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Shopping Cart - Tech Stack</title>
    <meta charset="UTF-8" />
    <meta name="author" content="Jeremy Webster, Keloth Justyn Dylan, Ben McIntyre, William Mibus" />
    <meta name="description" content="Assignment 2" />
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css"> <!-- Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"> <!-- Font -->
    <link rel="stylesheet" href="styles/style.css"/>
    <link rel="icon" href="images/favicon.png">
    <script src="scripts/script.js" defer></script>
</head>
<body>
    <?php require_once "inc/menu_compact.inc.php"; ?>
    
    <div id="content">
        <div id="shopping-container">
            <?php
                require_once "inc/dbconn.inc.php";
                require_once "inc/product.inc.php";
                $products = GetShoppingCart($conn);
                $totalCost = 0;

                if ($products != null) {
                    echo '
                    <div id="shopping-filled">
                        <div id="shopping-container-left">
                            <h1>Shopping Cart</h1>
                            <ul>
                                ';

                                foreach ($products as &$product) {
                                    $product->CrateCartHTML();
                                    $totalCost += $product->price * $product->quantity;
                                }

                            echo '
                            </ul>
                        </div>
                        <div id="shopping-container-right">
                            <div id="shopping-summary">
                                <h2>Cart Subtotal </h2>
                                <span id="shopping-summary-count">('. sizeof($products) .' Items)</span>
                                <span id="shopping-summary-total">$'. $totalCost .'</span>
                                <p>Shipping and tax will be calculated on checkout</p>
                                <a href="checkout.php">Proceed to Checkout <i class="fa fa-long-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    ';
                } else {
                    echo '
                    <div id="shopping-empty">
                        <img src="images/empty.png" alt="Icon for empty shopping cart">
                        <h2>Your shopping cart is empty.</h2>
                        <div id="shopping-button-wrap">
                            <a href="./">Go Shopping</a>
                        </div>
                    </div>
                    ';
                } 
            ?>
        </div>

        <div id="product-similar">
            <div id="product-similar-head">
                <h2>Maybe you are looking for:</h2>
                <a id="product-similar-more">View More <i class="fa fa-long-arrow-right"></i></a>
            </div>
            <div id="product-similar-list">
                <?php
                    PopulateList($conn,null, 8,null);
                ?>
            </div>
        </div>
    </div>
    <?php require_once "inc/footer.inc.php"; ?>
</body>
</html>
