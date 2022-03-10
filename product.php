<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Product Overview - Tech Stack</title>
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
        <div id="product-details">
            <?php
                require_once "inc/dbconn.inc.php";
                require_once "inc/product.inc.php";

                $product = GetProductByID($conn, $_GET["id"]);
                $images = $product->images;

                echo '
                <div id="product-details-left">
                <div id="product-image-selection">
                    <ul>';
                        for ($i = 0;$i < count($images);$i++) {
                            echo '<li><button '. ($i === 0 ? 'id="product-image-selected"' : "") .'"><img src="images/products/'. $images[$i] .'.png" alt="Product image of '. $product->name .'" onmouseover="changeProductImage(this)"></button></li>';
                        }
                echo '</ul>
                </div>
                <img src="images/products/'. $product->image .'.png" alt="Product image of '. $product->name .'" id="product-details-image">
                <div id="product-socials">
                    <div>
                        <span>Share to:</span>
                        <a href="" class="product-socials-btn" style="background-color: rgb(66,103,178)"><i class="fa fa-facebook-f fa-lg"></i></a>
                        <a href="" class="product-socials-btn" style="background-color: rgb(29,161,242)"><i class="fa fa-twitter fa-lg"></i></a>
                        <a href="" class="product-socials-btn" style="background-color: rgb(219,68,55)"><i class="fa fa-google-plus fa-lg"></i></a>
                        <a href="" class="product-socials-btn" style="background-color: rgb(102,102,102)"><i class="fa fa-envelope fa-lg"></i></a>
                    </div>
                </div>
            </div>
            <div id="product-details-right">
                <h1>'. $product->name .'</h1>
                <p>from <a href="/">Apple</a></p>
                <div id="product-details-description">
                    <p>'. $product->description .'</p>
                </div>
                <p><button onclick="showProductDescription()" id="product-details-show">Show More</button></p>
                <form action="shopping_cart.php" onsubmit="return AddToShoppingCart('. $product->id .')" method="POST">
                    <div id="product-details-cart">
                        <div id="product-details-pricing">
                            <p><b>Price:</b><br>
                            <span id="product-details-price">AU $'. $product->price .'</span>
                            <span id="product-details-price-old">AU $'. $product->price_base .'</span>
                            <span id="product-details-discount"><b>%'. $product->discount .'</b> Off</span></p>
                        </div>
                        <div id="product-details-quantity">
                            <label for="quantity"><b>Quantity:</b></label><br>
                            <input type="number" id="quantity" name="quantity" value=1 min=1 max='. $product->stock .'>
                        </div>
                    </div>
                    <input type="submit" value="Add to cart">
                </form>
                <p>
            </div>
                ';
            ?>
        </div>
        <div id="product-similar">
            <div id="product-similar-head">
                <h2>Other popular <?php echo '<a href="index.php?category='. strtolower($product->category) .'">'. $product->category;?>'s</a> you might like:</h2>
                <a id="product-similar-more">View More <i class="	fa fa-long-arrow-right"></i></a>
            </div>
            <div id="product-similar-list">
                <?php
                    PopulateList($conn,$product->category,8,$product->id);
                ?>
            </div>
        </div>
    </div>
    <?php require_once "inc/footer.inc.php"; ?>
</body>
</html>
