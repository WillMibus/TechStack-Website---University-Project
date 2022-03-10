let IN_STOCK = 30;

function changeProductImage(selection) {
    let img = document.getElementById("product-details-image");
    if (img == null) return;

    let oldSelected = document.getElementById("product-image-selected");
    if (img.parentElement == oldSelected) return;

    img.src = selection.src;

    if (oldSelected != null) oldSelected.removeAttribute("id");
    selection.parentElement.id = "product-image-selected";
}

let descriptionShown = true;
function showProductDescription() {
    let description = document.getElementById("product-details-description");
    let showButton = document.getElementById("product-details-show");

    if (description != null) {
        if ((descriptionShown = !descriptionShown)) {
            description.classList.remove("product-details-description-shown");
            showButton.textContent = "Show Less";
        } else {
            description.setAttribute("class","product-details-description-shown");
            showButton.textContent = "Show More";
        }
    }
}

function UpdateShoppingCart() {
    let cartCount = document.getElementById("cart-count");

    if (cartCount == null) return;
    shopping_cart = GetShoppingCart();

    if (shopping_cart != null && Object.keys(shopping_cart).length > 0) {
        cartCount.style.display = "block";
        cartCount.textContent = Object.keys(shopping_cart).length;
    }
    else {
        cartCount.style.display = "none";
    }
}
UpdateShoppingCart();

function GetShoppingCart() {
    let data = getCookie("shopping_cart");

    if (data == null || data.length < 3) return Object.create(null);
    let shopping_cart = JSON.parse(data);

    if (shopping_cart == null) {
        shopping_cart = Object.create(null);
    }

    return shopping_cart;
}

function AddToShoppingCart(id) {
    if (id == null) return false;

    let quantity = document.getElementById("quantity");
    if (quantity == null) quantity = 1; else quantity = quantity.value;

    if (quantity < 0) quantity = 1;
    if (quantity > IN_STOCK) {
        alert("Not enough quantity in stock!");
        return false;
    }

    let shopping_cart = GetShoppingCart();

    shopping_cart[id] = quantity;

    setCookie("shopping_cart", JSON.stringify(shopping_cart), 30);

    UpdateShoppingCart();

    return true;
}

function RemoveFromShoppingCart(id) {
    if (id == null) return;

    let shopping_cart = GetShoppingCart();
    if (shopping_cart.hasOwnProperty(id)) {
        delete shopping_cart[id];
    }

    if (!shopping_cart.hasOwnProperty(id)) {
        setCookie("shopping_cart", JSON.stringify(shopping_cart), 30);

        location.reload();
        
        return true;
    }
    return false;
}

function ChangeCartQuantity(amount,id,stock) {
    let shopping_cart = GetShoppingCart();

    if (shopping_cart.hasOwnProperty(id)) {
        let quantity = parseInt(shopping_cart[id]);

        if (quantity + amount > 0 && quantity + amount <= stock) {
            shopping_cart[id] = quantity + amount;

            setCookie("shopping_cart", JSON.stringify(shopping_cart), 30);

            location.reload();
        }
    }
}

/**
 *  CODE FROM w3schools.com for base cookie functions
 *  https://www.w3schools.com/js/js_cookies.asp
 *  Data Assessed: 17/10/2020
 */

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));

    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
  
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');

    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}