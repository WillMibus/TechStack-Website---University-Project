<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Checkout - Tech Stack</title>
    <meta charset="UTF-8" />
    <meta name="author" content="Jeremy Webster, Keloth Justyn Dylan, Ben McIntyre, William Mibus" />
    <meta name="description" content="Assignment 2" />
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css"> <!-- Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"> <!-- Font -->
    <link rel="stylesheet" href="styles/style.css"/>
    <link rel="icon" href="images/favicon.png">
    <script src="scripts/script.js" defer></script>
    <script src="scripts/checkout.js" defer></script>
</head>
<body>
    <?php require_once "inc/menu_compact.inc.php"; ?>
    <div id="content">

        <div id="checkout">
            <div id="form">
                <h2>Checkout</h2>
                <p>Please note all fields are required.</p>
                <form action="inc/complete_purchase.inc.php" method="POST" onsubmit="return checkCheckout()">
                    <div id="personal-info">
                        <h3>Personal Information</h3>
                        <div class="indent">
                            <label for="first-name">First Name:</label>
                            <input type="text" name="first-name" id ="first-name" required></input>
                            <label for="last-name">Last Name:</label>
                            <input type="text" name="last-name" id ="last-name" required></input>
                            <label for="last-name">Email:</label>
                            <input type="email" name="email" id ="email" required></input>
                            <label for="phone">Phone Number:</label>
                            <input type="tel" name="phone" id ="phone" required></input>
                        </div>
                    </div>
                    <div id="shipping-address">
                    <h3>Shipping Address</h3>
                        <div class="indent">
                            <label for="street-address">Street Address:</label>
                            <input type="text" name="street-address" id ="street-address" required></input>
                            <div class="smaller-inputs">
                                <div>
                                    <label for="city">City:</label>
                                    <input type="text" name="city" id ="city" required></input>
                                </div>
                                <div>
                                    <label for="postcode">Postcode:</label>
                                    <input type="number" name="postcode" id ="postcode" required></input>
                                </div>
                                <div>
                                    <label for="state">State:</label>
                                    <select name="state" id="state" required>
                                        <option value="">--- Please Select ----</option>
                                        <option>New South Wales</option>
                                        <option>Victoria</option>
                                        <option>Queensland</option>
                                        <option>Western Australia</option>
                                        <option>South Australia</option>
                                        <option>Tasmania</option>
                                        <option>Australian Capital Territory</option>
                                        <option>Northern Territory</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="payment-info">
                        <h3>Payment Information</h3>
                        <div class="indent">
                            <label for="cardholder-name">Cardholder Name:</label>
                            <input type="text" name="cardholder-name" id ="cardholder-name" required></input>
                            <label for="card-number">Card Number:</label>
                            <input type="text" name="card-number" id ="card-number" pattern="[0-9]{16}" required></input>
                            <div class="smaller-inputs">
                                <div>
                                    <label for="cvc">CVC:</label>
                                    <input type="text" name="cvc" id ="cvc" pattern="[0-9]{3}" required></input>
                                </div>
                                <div>
                                    <label for="card-expiry">Card Expiry:</label>
                                    <input type="month" name="card-expiry" id ="card-expiry" required></input>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="indent">
                        <p>Thank you for shopping with Tech Stack!</p>
                        <input type="submit" value="Order"></input>
                    </div>
                </form>
            </div>
            <div id="summary">
                <h2>Order Summary</h2>
                <h3>Products</h3>
                <a href="shopping_cart.php" id="checkout-modify">Modify Order</a>
                <?php
                    require_once "inc/dbconn.inc.php";
                    require_once "inc/product.inc.php";
                    $products = GetShoppingCart($conn);
                    $totalCost = 0;

                    if ($products != null) {
                        echo '<ul>';
                        foreach ($products as &$product) {
                            $product->CrateCheckoutHTML();
                            $totalCost += $product->price * $product->quantity;
                        }
                        echo '</ul>';
                    }

                    echo '
                        <h2>Cart Subtotal </h2>
                        <span id="shopping-summary-count">('. sizeof($products) .' Items)</span>
                        <span id="shopping-summary-total">$'. $totalCost .'</span>
                    ';
                ?>
                <div id="personal-display" class="display-none">
                    <h3>Personal Information</h3>
                    <ul>
                        <li class="display-none" id="name-display"><span id="first-name-display"></span> <span id="last-name-display"></span></li>
                        <li class="display-none" id="email-display"></li>
                        <li class="display-none" id="phone-display"></li>
                    </ul>
                </div>
                <div id="shipping-display" class="display-none">
                    <h3>Shipping Information</h3>
                    <ul>
                        <li class="display-none" id="street-address-display"></li>
                        <li class="display-none" id="city-display"></li>
                        <li class="display-none" id="postcode-display"></li>
                        <li class="display-none" id="state-display"></li>
                    </ul>
                </div>
            </div>
        </div>
        
        
    </div>
    <?php require_once "inc/footer.inc.php"; ?>
</body>
</html>
