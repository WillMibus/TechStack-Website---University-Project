<?php

class Product {
    public $id = -1;
    public $name = "Unknown";
    public $summary = "";
    public $description = "";
    public $category = "";
    public $price = 0;
    public $price_base = 0;
    public $discount = 0;
    public $image = "broken";
    public $images = null;

    public $quantity = 0;
    public $stock = 50;
    public $variant = "Silver";

    function __construct($row, $conn) {
        $this->id = $row["productID"];
        $this->name = $row["name"];
        $this->summary = $row["summary"];
        $this->description = $row["description"];
        $this->category = $row["category"];
        $this->price_base = $row["price"];
        $this->discount = $row["discount"];
        $this->price = number_format((float)($this->price_base * (1 - ($this->discount / 100))), 2, '.', '');

        $this->images = $this->GetImages($conn);

        if (($this->image = $this->images[0]) === null) {
            $this->image = "broken";
        }
    }

    function CreateListingHTML() {
        echo '
        <div class="product-listing">
            <a href="product.php?id='. $this->id .'">
                <div class="product-image-container">
                    <img src="images/products/'. $this->image .'.png" alt="Photo of '. $this->name .'">
                    <div class="discount-sticker" '. ($this->discount > 0 ? "style='display: block;'" : "") .'>%'. $this->discount .' OFF</div>
                </div>
                <div class="product-summary-container">
                    <h1>'. $this->name .'</h1>
                    <p>'. $this->summary .'</p>
                    <p>$'. $this->price .' AUD</p>
                </div>
            </a>
        </div>';
    }

    function CrateCartHTML() {
        echo '
        <li class="shopping-item" id="shopping-item-id-'. $this->id .'">
            <div class="shopping-item-thumbnail">
                <a href="/"><img src="images/products/'. $this->image.'.png" alt="Product item thumbnail of '. $this->name.'"></a>
            </div>
            <div class="shopping-item-details">
                <span class="shopping-item-title"><a href="/">'. $this->name .'</a></span><br>
                <span class="shopping-item-variants">'. $this->variant .' • 64 GB Storage</span>
            </div>
            <div class="shopping-item-quantity">
                <input type="button" value="+" onclick="ChangeCartQuantity(1,'. $this->id .','. $this->stock .')"></input>
                <input type="number" value ="'. $this->quantity .'" min=1 max='. $this->stock .'></input>
                <input type="button" value="-" onclick="ChangeCartQuantity(-1,'. $this->id .','. $this->stock .')"></input>
            </div>
            <div class="shopping-item-price">$'. $this->price .'</div>
            <div class="shopping-item-remove"><button onclick="RemoveFromShoppingCart('. $this->id .')">Remove</button></div>
        </li>
        ';
    }

    function CrateCheckoutHTML() {
        echo '
        <li class="shopping-item" id="shopping-item-id-'. $this->id .'">
            <div class="shopping-item-thumbnail">
                <a href="/"><img src="images/products/'. $this->image.'.png" alt="Product item thumbnail of '. $this->name.'"></a>
            </div>
            <div class="shopping-item-details">
                <span class="shopping-item-title"><a href="/">'. $this->name .'</a></span><br>
                <span class="shopping-item-variants">Gold • 64 GB Storage</span>
            </div>
            <div class="shopping-item-price">'. $this->quantity .'x</div>
            <div class="shopping-item-price">$'. $this->price .'</div>
        </li>
        ';
    }

    function GetImages($conn) {
        $images = array();

        $sql = "SELECT src FROM ProductImage WHERE productID = ". $this->id .";";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    array_push($images,$row["src"]);
                }
            } else {
                $images = null;
            }
        } else {
            $images = null;
        }

        mysqli_free_result($result);

//      mysqli_close($conn);

        return $images;
    }
}

function PopulateList($conn, $category, $limit, $exclude) {
    if ($category == null) {
        $sql = "SELECT productID, name, summary, description, category, price, discount FROM Product LIMIT $limit;";
    }
    else {
        if ($exclude == null) {
            $sql = "SELECT productID, name, summary, description, category, price, discount FROM Product WHERE category = '$category' LIMIT $limit;";
        }
        else {
            $sql = "SELECT productID, name, summary, description, category, price, discount FROM Product WHERE category = '$category' AND productID != $exclude LIMIT $limit;";
        }
        
    }

    

    $result = mysqli_query($conn, $sql);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo '<div id="grid">';
            while ($row = mysqli_fetch_assoc($result)) {
                $product = new Product($row,$conn);
    
                $product->CreateListingHTML();
            }
            echo "</div>";
        }

        mysqli_free_result($result);
    }
}

function GetProductByID($conn, $product_id) {
    $product = NULL;
    $sql = "SELECT productID, name, summary, description, category, price, discount FROM Product WHERE productID = ". $product_id ." LIMIT 1;";

    $result = mysqli_query($conn, $sql);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            
            $product = new Product($row,$conn);
        }

        mysqli_free_result($result);
    }

    return $product;
}

function GetShoppingCart($conn) {
    $cookie_name = "shopping_cart";
    $products = array();

    if(isset($_COOKIE[$cookie_name])) {
        $data = json_decode($_COOKIE[$cookie_name]);

        if ($data == null) return null;
        foreach($data as $key => $value) {
            $product = GetProductByID($conn, $key);
            
            if ($product != null) {
                $product->quantity = $value;
                array_push($products, $product);
            }
        }

        return $products;
    }

    return null;
}