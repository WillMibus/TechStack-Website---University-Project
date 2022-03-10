<?php

if (isset($_POST["first-name"]) && isset($_POST["last-name"]) && isset($_POST["email"]) && isset($_POST["phone"])
    && isset($_POST["street-address"]) && isset($_POST["city"]) && isset($_POST["postcode"]) && isset($_POST["state"])
    && isset($_POST["cardholder-name"]) && isset($_POST["card-number"]) && isset($_POST["cvc"]) && isset($_POST["card-expiry"])) {

    require_once "dbconn.inc.php";
    require_once "product.inc.php";
    $products = GetShoppingCart($conn);

    if ($products == null) {
        echo '<script>alert("ERROR NO PRODUCTS IN SHOPPING CART CART!")</script>';
        echo "<script>history.go(-1)</script>";
        return;
    }

    $totalCost = 0;

    if ($products != null) {
        foreach ($products as &$product) {
            $totalCost += $product->price * $product->quantity;
        }
    }

    /****************************************************************
    *                          Account
    ****************************************************************/

    $sql = "INSERT INTO Account(fName,lName,email,phone) VALUES(?,?,?,?);";

    $statement = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($statement, $sql);
    mysqli_stmt_bind_param($statement, 'sssd', htmlspecialchars($_POST["first-name"]),htmlspecialchars($_POST["last-name"]),htmlspecialchars($_POST["email"]),htmlspecialchars($_POST["phone"]));
    $user_id = -1;

    if (mysqli_stmt_execute($statement)) {
        $user_id = $conn->insert_id;
    } else {
        echo mysqli_error($conn);
        return;
    }

    mysqli_stmt_close($statement);

    /****************************************************************
    *                        OrderRecord
    ****************************************************************/

    $sql = "INSERT INTO OrderRecord(userID,cost) VALUES(?,?);";

    $statement = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($statement, $sql);
    mysqli_stmt_bind_param($statement, 'dd', htmlspecialchars($user_id),htmlspecialchars($totalCost));
    $order_id = -1;

    if (mysqli_stmt_execute($statement)) {
        $order_id = $conn->insert_id;
    } else {
        echo mysqli_error($conn);
        return;
    }

    mysqli_stmt_close($statement);


    /****************************************************************
    *                      ShippingAddress
    ****************************************************************/

    $sql = "INSERT INTO ShippingAddress(orderID,street,postcode,suburb,state,country) VALUES (?,?,?,?,?,?);";

    $statement = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($statement, $sql);
    mysqli_stmt_bind_param($statement, 'dsdsss', htmlspecialchars($order_id),htmlspecialchars($_POST["street-address"]),htmlspecialchars($_POST["postcode"]),htmlspecialchars($_POST["city"]),htmlspecialchars($_POST["state"]),htmlspecialchars("Australia"));

    if (mysqli_stmt_execute($statement)) {

    } else {
        echo mysqli_error($conn);
        return;
    }

    mysqli_stmt_close($statement);

    /****************************************************************
    *                         Payment
    ****************************************************************/

    $sql = "INSERT INTO Payment(orderID,cardNumber,cvc,expiryDate) VALUES(?,?,?,?);";

    $statement = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($statement, $sql);

    $key1 = hash('sha512',htmlspecialchars($_POST["card-number"]));
    $key2 = hash('sha512',htmlspecialchars($_POST["cvc"]));

    mysqli_stmt_bind_param($statement, 'dsss', htmlspecialchars($order_id),$key1,$key2,htmlspecialchars($_POST["card-expiry"] .'-1 00:00:00'));

    if (mysqli_stmt_execute($statement)) {

    } else {
        echo mysqli_error($conn);
        return;
    }

    mysqli_stmt_close($statement);

    /****************************************************************
    *                   OrderProduct / Stock
    ****************************************************************/

    if ($products != null) {
        foreach ($products as &$product) {
            $sql = "INSERT INTO OrderProduct(orderID,productID,quantity,variant) VALUES(?,?,?,?);";

            $statement = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($statement, $sql);
            mysqli_stmt_bind_param($statement, 'ddds', htmlspecialchars($order_id), htmlspecialchars($product->id),htmlspecialchars($product->quantity),htmlspecialchars($product->variant));

            if (mysqli_stmt_execute($statement)) {
        
            } else {
                echo mysqli_error($conn);
                return;
            }
        
            mysqli_stmt_close($statement);

            $sql = "UPDATE Stock SET quantity = ? WHERE productID = ? AND variant = ?;";
            $statement = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($statement, $sql);
            mysqli_stmt_bind_param($statement, 'dds', htmlspecialchars($product->stock - 1), htmlspecialchars($product->id),htmlspecialchars($product->variant));

            if (mysqli_stmt_execute($statement)) {
        
            } else {
                echo mysqli_error($conn);
                return;
            }
        
            mysqli_stmt_close($statement);
        }
    }

    // Clear the shopping cart of order contents
    unset($_COOKIE['shopping_cart']);
    setcookie('shopping_cart', '', time() - 3600, '/');

    header("location: ../index.php");
}