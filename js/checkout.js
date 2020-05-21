const checkoutOverlay = document.querySelector(".checkout-overlay");
const checkoutDOM = document.querySelector(".checkout");
const closeCheckoutBtn = document.querySelector(".close-checkout");
const checkoutContent = document.querySelector(".checkout-content");
const deliveryHeader = document.querySelector(".delivery-header");
const paymentHeader = document.querySelector(".payment-header");
const statementHeader = document.querySelector(".statement-header");
const deliverHomeDOM = document.querySelector(".deliver-home");
const deliveryDOM = document.querySelector(".delivery");
const pickFromStoreDOM = document.querySelector(".pick-from-store");
const deliveryOptions =
  document.forms["checkout-form"].elements["delivery-option"];
const paymentMethods =
  document.forms["checkout-form"].elements["payment-method"];
let paymentMethod = "";
const paymentDOM = document.querySelector(".payment");
const statementDOM = document.querySelector(".statement");
const deliveryMethodDOM = document.querySelector(".delivery-method");

const proceedBtn = document.querySelector(".proceed-btn");
const checkoutTotal = document.querySelector(".checkout-total");
const shoppingFee = document.querySelector(".shopping-fee");
const shippingFee = document.querySelector(".shipping-fee");
const grandTotalDOM = document.querySelector(".grand-total");
let grandTotal;
let deliveryOption = "";
customerID = "";
const savedAddressesDOM = document.querySelector(".saved-addresses");
const customerAddressesDOM = document.querySelector(".customer-addresses");
const homeAddressTable = document.querySelector(".home-address-table");
const addAddressError = document.querySelector(".add-address-error");
const submitAddressBtn = document.querySelector(".submit-address");
let address = "";
let customerAddresses = "";
const checkoutErrorDOM = document.querySelector(".checkout-error");
const showAddressDetails = document.querySelector(".address-chosen");
const showDeliveryDetails = document.querySelector(".delivery-details");
const showPaymentDetails = document.querySelector(".payment-details");

const ui = new UI();

class CheckoutUI {
  eventListeners() {
    checkoutBtn.addEventListener("click", () => {
      customerID = document.querySelector("#customerID").value;
      if (cart.length == 0) {
        ui.hideCart();
        signInFirstBtn.innerHTML = "Ok";
        signInFirstBtn.removeAttribute("href");
        signInFirstBtn.onclick = () => {
          modal.style.display = "none";
        };
        modalBody.innerHTML =
          "<h4>Your cart is empty! Add items to cart first to proceed to checkout.</h4>";
        modal.style.display = "block";
      } else if (customerID != "" && cart.length != 0) {
        CheckoutApp.checkoutSetup();
        this.showCheckout();
        CheckoutApp.getAddresses();
      } else if (customerID == "") {
        ui.hideCart();
        signInFirstBtn.href = "../login/";
        signInFirstBtn.innerHTML = "Sign in";

        modalBody.innerHTML = "<h4>Sign in to proceed to checkout</h4>";
        modal.style.display = "block";
      }
    });
    closeCheckoutBtn.addEventListener("click", () => {
      this.hideCheckout();
      CheckoutApp.resetCheckout();
    });
    window.addEventListener("click", e => {
      if (e.target.matches(".checkout-overlay")) {
        this.hideCheckout();
        CheckoutApp.resetCheckout();
      }
    });
    for (let i = 0; i < deliveryOptions.length; i++) {
      deliveryOptions[i].onclick = e => {
        let option = e.target.value;

        if (option == "pick-up") {
          deliveryOption = "pick-up";
          pickFromStoreDOM.style.display = "block";
          deliverHomeDOM.style.display = "none";
        } else if (option == "deliver-home") {
          deliveryOption = "deliver-home";
          deliverHomeDOM.style.display = "block";
          pickFromStoreDOM.style.display = "none";
        } else if (option == "") {
          deliveryOption = "";
          pickFromStoreDOM.style.display = "none";
          deliverHomeDOM.style.display = "none";
        }
        CheckoutApp.checkoutSetup();
      };
    }

    for (let i = 0; i < paymentMethods.length; i++) {
      paymentMethods[i].onclick = e => {
        paymentMethod = e.target.value;
      };
    }

    proceedBtn.addEventListener("click", e => {
      e.preventDefault();
      let button = e.target;
      if (button.classList.contains("proceed-payment")) {
        if (deliveryOption != "") {
          checkoutErrorDOM.innerHTML = "";
          if (deliveryOption == "deliver-home") {
            if (address != "") {
              checkoutErrorDOM.innerHTML = "";
              this.proceedPayment();
            } else {
              checkoutErrorDOM.innerHTML = "Please select address";
            }
          } else if (deliveryOption == "pick-up") {
            checkoutErrorDOM.innerHTML = "";
            this.proceedPayment();
          }
        } else {
          checkoutErrorDOM.innerHTML = "Please select delivery method";
        }
      } else if (button.classList.contains("proceed-statement")) {
        if (paymentMethod != "") {
          checkoutErrorDOM.innerHTML = "";
          this.proceedStatement();
        } else {
          checkoutErrorDOM.innerHTML = "Please select payment method!";
        }
      } else if (button.classList.contains("place-order")) {
        CheckoutApp.placeOrder();
      }
    });
    deliveryHeader.addEventListener("click", () => {
      proceedBtn.classList.remove("proceed-statement");
      proceedBtn.classList.remove("place-order");
      proceedBtn.classList.add("proceed-payment");
      proceedBtn.value = "proceed payment";
      statementHeader.classList.remove("active");
      statementHeader.classList.remove("active-now");
      statementHeader.classList.add("disabled");
      paymentHeader.classList.remove("active");
      paymentHeader.classList.remove("active-now");
      paymentHeader.classList.add("disabled");
      deliveryHeader.classList.add("active-now");
      statementDOM.style.display = "none";
      paymentDOM.style.display = "none";
      deliveryDOM.style.display = "block";
    });
    paymentHeader.addEventListener("click", () => {
      if (proceedBtn.classList.contains("place-order")) {
        proceedBtn.classList.add("proceed-statement");
        proceedBtn.classList.remove("place-order");
        proceedBtn.classList.add("proceed-statement");
        proceedBtn.value = "proceed statement";
        statementHeader.classList.remove("active");
        deliveryHeader.classList.remove("active-now");
        statementHeader.classList.remove("active-now");
        statementHeader.classList.add("disabled");
        paymentHeader.classList.remove("disabled");
        paymentHeader.classList.add("active");
        paymentHeader.classList.add("active-now");
        statementDOM.style.display = "none";
        paymentDOM.style.display = "block";
        deliveryDOM.style.display = "none";
      }
    });

    customerAddressesDOM.addEventListener("click", e => {
      let element = e.target;
      if (element.matches(".fa-plus")) {
        element.classList.remove("fa-plus");
        element.classList.add("fa-minus");
        homeAddressTable.style.display = "block";
      } else if (element.matches(".fa-minus")) {
        element.classList.remove("fa-minus");
        element.classList.add("fa-plus");
        homeAddressTable.style.display = "none";
      } else if (element.matches("#delete-address")) {
        CheckoutApp.deleteAddress(element.dataset.id);
      }
    });

    submitAddressBtn.addEventListener("click", () => {
      let contactPhone = document.querySelector("#cphone");
      let city = document.querySelector("#city");
      let street = document.querySelector("#street");
      let house = document.querySelector("#house");
      if (
        !this.checkEmpty(contactPhone) &&
        !this.checkEmpty(city) &&
        !this.checkEmpty(street) &&
        !this.checkEmpty(house)
      ) {
        let address = {
          contactPhone: contactPhone.value,
          city: city.value,
          street: street.value,
          house: house.value,
        };
        CheckoutApp.saveAddress(address);
      }
    });
  }

  checkEmpty(caller) {
    if (caller.value.trim() == "") {
      caller.style = "border:1px solid red";
      return true;
    } else {
      caller.style = "border:1px solid black";
      return false;
    }
  }

  showCheckout() {
    checkoutOverlay.classList.add("visible-bg");
    checkoutDOM.classList.add("show-checkout");
  }

  hideCheckout() {
    checkoutOverlay.classList.remove("visible-bg");
    checkoutDOM.classList.remove("show-checkout");
  }
  proceedStatement() {
    proceedBtn.classList.remove("proceed-statement");
    proceedBtn.classList.add("place-order");
    proceedBtn.value = "place order";
    paymentHeader.classList.remove("active-now");
    statementHeader.classList.add("active");
    statementHeader.classList.add("active-now");
    statementDOM.style.display = "block";
    paymentDOM.style.display = "none";
    if (deliveryOption == "pick-up") {
      showDeliveryDetails.innerHTML =
        "<p class='address-p'>You selected delivery method as <b>pick-up-from store</b>.Your items will shopped and you will come to pick-up from the store.</p>";
      document.querySelector(".address-details").style.display = "none";
    } else if (deliveryOption == "deliver-home") {
      showDeliveryDetails.innerHTML =
        "<p class='address-p'>You selected delivery method as <b>deliver home or Office</b>.Your items will shopped and will be delivered by our <b>delivery agent</b> to the address you have given.</p>";
      CheckoutApp.getAddress();
      document.querySelector(".address-details").style.display = "block";
    }

    if (paymentMethod == "mpesa-on-delivery") {
      showPaymentDetails.innerHTML =
        "<p class='address-p'>You selected payment method as <b>Mpesa On Delivey</b>.You will be provided a Mpesa Till Number to pay for the items before goods are handed to you. Please have your Mpesa balance ready.</p>";
    } else if (paymentMethod == "cash-on-delivery") {
      showPaymentDetails.innerHTML =
        "<p class='address-p'>You selected payment method as <b>Cash On Delivey</b>.You are required to hand over the cash to our <b>delivery agent</b> before goods are handed to you. Please have the exact amount to save on time and to easy your payment</p>.";
    }
  }
  proceedPayment() {
    proceedBtn.classList.remove("proceed-payment");
    proceedBtn.classList.add("proceed-statement");
    proceedBtn.value = "proceed to statement";
    paymentHeader.classList.add("active");
    paymentHeader.classList.add("active-now");
    deliveryHeader.classList.remove("active-now");
    statementHeader.classList.remove("active-now");
    paymentDOM.style.display = "block";
    deliveryDOM.style.display = "none";
  }
}

class CheckoutApp {
  static getShoppingFee() {
    let shoppingFee;
    if (cartTotalItemsPrice < 500) {
      shoppingFee = 10;
    } else if (cartTotalItemsPrice < 1000) {
      shoppingFee = 20;
    } else if (cartTotalItemsPrice < 2000) {
      shoppingFee = 30;
    } else if (cartTotalItemsPrice < 3000) {
      shoppingFee = 40;
    } else if (cartTotalItemsPrice < 4000) {
      shoppingFee = 50;
    } else if (cartTotalItemsPrice < 5000) {
      shoppingFee = 60;
    } else if (cartTotalItemsPrice < 6000) {
      shoppingFee = 70;
    } else if (cartTotalItemsPrice < 7000) {
      shoppingFee = 60;
    } else if (cartTotalItemsPrice < 8000) {
      shoppingFee = 70;
    } else if (cartTotalItemsPrice < 9000) {
      shoppingFee = 80;
    } else if (cartTotalItemsPrice < 10000) {
      shoppingFee = 90;
    } else if (cartTotalItemsPrice < 12000) {
      shoppingFee = 110;
    } else if (cartTotalItemsPrice < 14000) {
      shoppingFee = 130;
    } else if (cartTotalItemsPrice < 16000) {
      shoppingFee = 150;
    } else if (cartTotalItemsPrice < 18000) {
      shoppingFee = 170;
    } else if (cartTotalItemsPrice < 20000) {
      shoppingFee = 200;
    } else {
      shoppingFee = 250;
    }
    return shoppingFee;
  }

  static getShippingFee() {
    let shippingFee;
    if (deliveryOption == "pick-up") {
      shippingFee = 0;
    } else if (deliveryOption == "deliver-home") {
      if (cartTotalItemsPrice < 500) {
        shippingFee = 10;
      } else if (cartTotalItemsPrice < 1000) {
        shippingFee = 20;
      } else if (cartTotalItemsPrice < 2000) {
        shippingFee = 30;
      } else if (cartTotalItemsPrice < 3000) {
        shippingFee = 40;
      } else if (cartTotalItemsPrice < 4000) {
        shippingFee = 50;
      } else if (cartTotalItemsPrice < 5000) {
        shippingFee = 60;
      } else if (cartTotalItemsPrice < 6000) {
        shippingFee = 70;
      } else if (cartTotalItemsPrice < 7000) {
        shippingFee = 60;
      } else if (cartTotalItemsPrice < 8000) {
        shippingFee = 70;
      } else if (cartTotalItemsPrice < 9000) {
        shippingFee = 80;
      } else if (cartTotalItemsPrice < 10000) {
        shippingFee = 90;
      } else if (cartTotalItemsPrice < 12000) {
        shippingFee = 110;
      } else if (cartTotalItemsPrice < 14000) {
        shippingFee = 130;
      } else if (cartTotalItemsPrice < 16000) {
        shippingFee = 150;
      } else if (cartTotalItemsPrice < 18000) {
        shippingFee = 170;
      } else if (cartTotalItemsPrice < 20000) {
        shippingFee = 200;
      } else {
        shippingFee = 250;
      }
    } else if (deliveryOption == "") {
      shippingFee = 0;
    }
    return shippingFee;
  }

  static getAddresses() {
    $.ajax({
      url: "../php/fetch_checkout.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "get_addresses",
        customerID: customerID,
      },
      success: data => {
        savedAddressesDOM.innerHTML = data;
        // customerAddresses = document.forms["checkout-form"].elements["address"];
        // console.log(customerAddresses);
        // if (customerAddresses != undefined) {
        //   for (let i = 0; i < customerAddresses.length; i++) {
        //     customerAddresses[i].onclick = e => {
        //       address = e.target.value;
        //     };
        //   }
        // }
      },
      error: data => {
        console.log(data);
      },
    });
  }

  static getAddress() {
    $.ajax({
      url: "../php/fetch_checkout.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "get_address",
        addressID: address,
      },
      success: data => {
        showAddressDetails.innerHTML = data;
      },
      error: data => {
        console.log(data);
      },
    });
  }

  static checkoutSetup() {
    checkoutTotal.innerText = parseFloat(
      cartTotalItemsPrice.toFixed(2)
    ).toLocaleString();
    grandTotal =
      cartTotalItemsPrice +
      CheckoutApp.getShoppingFee() +
      CheckoutApp.getShippingFee();
    shoppingFee.innerText = parseFloat(
      CheckoutApp.getShoppingFee().toFixed(2)
    ).toLocaleString();
    shippingFee.innerText = parseFloat(
      CheckoutApp.getShippingFee().toFixed(2)
    ).toLocaleString();
    grandTotalDOM.innerText = parseFloat(
      grandTotal.toFixed(2)
    ).toLocaleString();
  }
  static saveAddress(address) {
    $.ajax({
      url: "../php/fetch_checkout.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "save_address",
        customerID: customerID,
        contactPhone: address.contactPhone,
        city: address.city,
        street: address.street,
        house: address.house,
      },
      success: data => {
        if (data == "address_added") {
          this.getAddresses();
          homeAddressTable.style.display = "none";
          let icon = document.getElementById("fa-minus");
          icon.classList.remove("fa-minus");
          icon.classList.add("fa-plus");
        }
      },
      error: data => {
        console.log(data);
      },
    });
  }

  static resetCheckout() {
    proceedBtn.classList.remove("place-order");
    proceedBtn.classList.add("proceed-payment");
    proceedBtn.value = "proceed to payment";
    paymentHeader.classList.remove("active");
    paymentHeader.classList.remove("active-now");
    paymentHeader.classList.add("disabled");
    deliveryHeader.classList.add("active");
    deliveryHeader.classList.add("active-now");
    statementHeader.classList.remove("active-now");
    statementHeader.classList.remove("active");
    statementHeader.classList.add("disabled");
    paymentDOM.style.display = "none";
    deliveryDOM.style.display = "block";
    statementDOM.style.display = "none";
    grandTotal = 0;
  }

  static deleteAddress(addressID) {
      if(confirm('This address will be deleted permanently!')){
    $.ajax({
      url: "../php/fetch_checkout.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "delete_address",
        addressID: addressID,
      },
      success: data => {
        if (data == "address_deleted") {
            alert("Address deleted successfully.");
            address = "";
          this.getAddresses();
        }
      },
      error: data => {
        console.log(data);
      },
    });
      }
  }

  static placeOrder() {
    let orderDetails = [
      {
        deliveryMethod: deliveryOption,
        paymentMethod: paymentMethod,
        cartTotal: cartTotalItemsPrice,
        shoppingFee: this.getShoppingFee(),
        shippingFee: this.getShippingFee(),
      },
    ];
    $.ajax({
      url: "../php/fetch_checkout.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "place_order",
        orderItems: JSON.stringify(cart),
        customerID: customerID,
        address: address,
        orderTotal: grandTotal,
        orderDetails: JSON.stringify(orderDetails),
      },
      success: data => {
        this.resetCheckout();
        //delete all cartItems
        Cart.clearCart();
        //hide checkout overlay
        checkoutOverlay.classList.remove("visible-bg");
        checkoutDOM.classList.remove("show-checkout");
        //hide cart Overlay
        Cart.setupApp(cart);
        cartOverlay.classList.remove("visible-bg");
        cartDOM.classList.remove("show-cart");
        Orders.getOrders();
      },
      error: data => {
        console.log(data);
      },
    });
  }
  static setAddress(id) {
    address = id;
  }
}

window.addEventListener("DOMContentLoaded", () => {
  const checkoutUi = new CheckoutUI();
  checkoutUi.eventListeners();
});
