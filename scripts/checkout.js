// Declare variables for appearing/disappearing summary section
let firstName;
let lastName;
let email;
let phone;
let firstNameDisplay = document.getElementById("first-name-display");
let lastNameDisplay = document.getElementById("last-name-display");
let nameDisplay = document.getElementById("name-display");
let emailDisplay = document.getElementById("email-display");
let phoneDisplay = document.getElementById("phone-display");
let personalDisplay = document.getElementById("personal-display");

let streetAddress;
let city;
let postcode;
let state;
let streetAddressDisplay = document.getElementById("street-address-display");
let cityDisplay = document.getElementById("city-display");
let postcodeDisplay = document.getElementById("postcode-display");
let stateDisplay = document.getElementById("state-display");
let shippingDisplay = document.getElementById("shipping-display");

// declare the change events when the summary section should be updated
document
  .getElementById("first-name")
  .addEventListener("change", updatePersonal);
document.getElementById("last-name").addEventListener("change", updatePersonal);
document.getElementById("email").addEventListener("change", updatePersonal);
document.getElementById("phone").addEventListener("change", updatePersonal);

document
  .getElementById("street-address")
  .addEventListener("change", updateShipping);
document.getElementById("city").addEventListener("change", updateShipping);
document.getElementById("postcode").addEventListener("change", updateShipping);
document.getElementById("state").addEventListener("change", updateShipping);

// divide different functionality into different functions
function updatePersonal() {
  savePersonal();
  displayPersonal();
  checkPersonal();
}

function updateShipping() {
  saveShipping();
  displayShipping();
  checkShipping();
}

function checkCheckout() {
  let cardExpiry = document.getElementById("card-expiry");

  if ((new Date(cardExpiry.value)) > (new Date())) {
    checkField(cardExpiry, false);
  } else { 
    return checkField(cardExpiry, true);
  }

  return true;
}

function checkField(element, error) {
  if (error) {
    element.setAttribute("class","checkout-error");
    console.warn("field is wrong");
    return false;
  }

  element.setAttribute("class","checkout-valid");

  return true;
}

// save variables to value of input fields
function savePersonal() {
  firstName = document.getElementById("first-name").value;
  lastName = document.getElementById("last-name").value;
  email = document.getElementById("email").value;
  phone = document.getElementById("phone").value;
}

function saveShipping() {
  streetAddress = document.getElementById("street-address").value;
  city = document.getElementById("city").value;
  postcode = document.getElementById("postcode").value;
  state = document.getElementById("state").value;
}

// set the summary section list fields to the variable inputs
function displayPersonal() {
  firstNameDisplay.textContent = firstName;
  lastNameDisplay.textContent = lastName;
  emailDisplay.textContent = email;
  phoneDisplay.textContent = phone;
}

function displayShipping() {
  streetAddressDisplay.textContent = streetAddress;
  cityDisplay.textContent = city;
  postcodeDisplay.textContent = postcode;
  stateDisplay.textContent = state;
}

function checkPersonal() {
  if (firstNameDisplay.textContent && lastNameDisplay.textContent) {
    nameDisplay.classList.remove("display-none");
    nameDisplay.classList.add("display-list-item");
  } else {
    nameDisplay.classList.remove("display-list-item");
    nameDisplay.classList.add("display-none");
  }

  if (emailDisplay.textContent) {
    emailDisplay.classList.remove("display-none");
    emailDisplay.classList.add("display-list-item");
  } else {
    emailDisplay.classList.remove("display-list-item");
    emailDisplay.classList.add("display-none");
  }

  if (phoneDisplay.textContent) {
    phoneDisplay.classList.remove("display-none");
    phoneDisplay.classList.add("display-list-item");
  } else {
    phoneDisplay.classList.remove("display-list-item");
    phoneDisplay.classList.add("display-none");
  }

  if (
    (firstNameDisplay.textContent && lastNameDisplay.textContent) ||
    emailDisplay.textContent ||
    phoneDisplay.textContent
  ) {
    personalDisplay.classList.remove("display-none");
    personalDisplay.classList.add("display-block");
  } else {
    personalDisplay.classList.remove("display-block");
    personalDisplay.classList.add("display-none");
  }
}

function checkShipping() {
  if (streetAddressDisplay.textContent) {
    streetAddressDisplay.classList.remove("display-none");
    streetAddressDisplay.classList.add("display-list-item");
  } else {
    streetAddressDisplay.classList.remove("display-list-item");
    streetAddressDisplay.classList.add("display-none");
  }

  if (cityDisplay.textContent) {
    cityDisplay.classList.remove("display-none");
    cityDisplay.classList.add("display-list-item");
  } else {
    cityDisplay.classList.remove("display-list-item");
    cityDisplay.classList.add("display-none");
  }

  if (postcodeDisplay.textContent) {
    postcodeDisplay.classList.remove("display-none");
    postcodeDisplay.classList.add("display-list-item");
  } else {
    postcodeDisplay.classList.remove("display-list-item");
    postcodeDisplay.classList.add("display-none");
  }

  if (stateDisplay.textContent) {
    stateDisplay.classList.remove("display-none");
    stateDisplay.classList.add("display-list-item");
  } else {
    stateDisplay.classList.remove("display-list-item");
    stateDisplay.classList.add("display-none");
  }

  if (
    streetAddressDisplay.textContent ||
    cityDisplay.textContent ||
    postcodeDisplay.textContent ||
    stateDisplay.textContent
  ) {
    shippingDisplay.classList.remove("display-none");
    shippingDisplay.classList.add("display-block");
  } else {
    shippingDisplay.classList.remove("display-block");
    shippingDisplay.classList.add("display-none");
  }
}