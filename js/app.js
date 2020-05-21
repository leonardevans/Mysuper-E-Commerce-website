const cartBtn = document.querySelector(".cart-btn");
const totalCartItems = document.querySelector(".total-items");
const cartOverlay = document.querySelector(".cart-overlay");
const closeCartBtn = document.querySelector(".close-cart");
const clearCartBtn = document.querySelector(".clear-cart-btn");
const cartDOM = document.querySelector(".cart");
const cartTotal = document.querySelector(".cart-total");
const cartContent = document.querySelector(".cart-content");
const checkoutBtn = document.querySelector(".checkout-btn");
let cartTotalItemsPrice;

let cart = [];
if (localStorage.getItem("My_super_cart")) {
  cart = JSON.parse(localStorage.getItem("My_super_cart"));
}

class UI {
  EventsListeners() {
    cartBtn.addEventListener("click", () => {
        if(cart.length ==0){
            alert("Your cart is empty! Add items to cart to view your cart");
        }else{
      this.showCart();
      }
    });
    window.addEventListener("click", e => {
      if (e.target.matches(".cart-overlay")) {
        this.hideCart();
      }
    });
    closeCartBtn.addEventListener("click", () => {
      this.hideCart();
    });
    this.addToCart();
  }

  showCart() {
    cartOverlay.classList.add("visible-bg");
    cartDOM.classList.add("show-cart");
  }

  hideCart() {
    cartOverlay.classList.remove("visible-bg");
    cartDOM.classList.remove("show-cart");
  }

  addToCart() {
    window.addEventListener("click", e => {
      if (e.target.matches(".add-btn") || e.target.matches(".add-to-btn")) {
        let productID = e.target.dataset.id;
        let addButton = e.target;
        addButton.innerText = "In Cart";
        addButton.disabled = true;
        if (cart.find(item => item.id == productID) == undefined) {
          Cart.addItemToCart(productID, 1);
        }
        this.showCart();
      }
    });
  }
}

class Cart {
  static addItemToCart(productID, items) {
    $.ajax({
      url: "../php/fetch_cart.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "get_product",
        productID: productID,
        items: items,
      },
      success: data => {
        if(data == 'no_such_product'){
            window.location.href = "../home/";
        }
        else if(JSON.parse(data)){
            let response = JSON.parse(data);
        const div = document.createElement("div");
        div.classList.add("cart-item");
        div.innerHTML = response.html;
        cartContent.appendChild(div);
        cart[cart.length] = response.details;
        Storage.storeCartInLocaLStorage(cart);

        this.setCartValues(cart);
        let upButtons = [...document.querySelectorAll(".fa-chevron-up")];
        cart.map(item => {
          let temporaryTotalItems = Number(item.items);
          upButtons.map(button => {
            if (button.dataset.id == item.id) {
              button.nextElementSibling.innerText = temporaryTotalItems;
            }
          });
        });
        this.saveCustomerCart(cart);
        }
      },
      error: data => {
        console.log(data);
      },
    });
  }
  static setCartValues(cart) {
    let temporaryTotal = 0;
    let temporaryTotalItems = 0;
    let subTotals = [...document.querySelectorAll(".cart-sub-total")];

    cart.map(item => {
      temporaryTotal += item.price * Number(item.items);
      temporaryTotalItems += Number(item.items);
      let subTotal = subTotals.find(element => element.dataset.id == item.id);
      if (subTotal != undefined) {
        subTotal.innerText = Number(item.subtotal).toLocaleString();
      }
    });

    cartTotal.innerText = parseFloat(
      temporaryTotal.toFixed(2)
    ).toLocaleString();
    cartTotalItemsPrice = temporaryTotal;
    totalCartItems.innerText = temporaryTotalItems;
  }
  static cartLogic() {
    clearCartBtn.addEventListener("click", () => {
         if(confirm('All items will be removed from your cart!')){
      this.clearCart();
         }
    });
    cartContent.addEventListener("click", e => {
      if (e.target.classList.contains("fa-window-close")) {
          if(confirm('This item will be removed yout cart!')){
        let removeItem = e.target;
        let id = removeItem.dataset.id;

        cartContent.removeChild(
          removeItem.parentElement.parentElement.parentElement
        );
        this.removeItem(id);
          }
      } else if (e.target.classList.contains("fa-chevron-up")) {
        let addAmount = e.target;
        let id = addAmount.dataset.id;
        let stock = addAmount.dataset.stock;
         let tempItem = cart.find(item => item.id == id);
      if(Number(tempItem.items) >= stock){
          alert("Stock not enough");
          
      }else{
        
        tempItem.items = Number(tempItem.items) + 1;
        tempItem.subtotal = Number(tempItem.items) * tempItem.price;
        Storage.storeCartInLocaLStorage(cart);
        this.setCartValues(cart);
        this.saveCustomerCart(cart);
        addAmount.nextElementSibling.innerText = tempItem.items;
        let subTotals = [...document.querySelectorAll(".cart-sub-total")];
        let subTotal = subTotals.find(element => element.dataset.id == id);
        subTotal.innerText = tempItem.subtotal.toLocaleString();
      }
        
    
       
        
      } else if (e.target.classList.contains("fa-chevron-down")) {
        let lowerAmount = e.target;
        let id = lowerAmount.dataset.id;
        let tempItem = cart.find(item => item.id == id);
        if (Number(tempItem.items) == 1) {
            if(confirm('This item will be removed from your cart')){
          cartContent.removeChild(
            lowerAmount.parentElement.parentElement.parentElement
          );
          this.removeItem(id);
            }
        }else{
            tempItem.items = Number(tempItem.items) - 1;
        tempItem.subtotal = Number(tempItem.items) * tempItem.price;
        Storage.storeCartInLocaLStorage(cart);
        this.setCartValues(cart);
        this.saveCustomerCart(cart);
        lowerAmount.previousElementSibling.innerText = tempItem.items;
        let subTotals = [...document.querySelectorAll(".cart-sub-total")];
        let subTotal = subTotals.find(element => element.dataset.id == id);
        subTotal.innerText = tempItem.subtotal.toLocaleString();
        }
      }
    });
  }
  static clearCart() {
     
    let cartItems = cart.map(item => item.id);
    cartItems.forEach(id => this.removeItem(id));
    while (cartContent.children.length > 0) {
      cartContent.removeChild(cartContent.children[0]);
    }
    let uiObj = new UI();
    uiObj.hideCart();
      
  }


  static removeItem(id) {
    cart = cart.filter(item => item.id != id);
    this.setCartValues(cart);
    Storage.storeCartInLocaLStorage(cart);
    this.saveCustomerCart(cart);
    this.checkEmptyCart(cart);

    if (document.querySelector(".add-to-btn") != null) {
      let addTocartBtn = document.querySelector(".add-to-btn");
      addTocartBtn.disabled = false;
      addTocartBtn.innerHTML = `<i class="fas fa-shopping-cart"></i>Add to cart`;
    }
    let button = this.getSingleButton(id);
    if (button != undefined) {
      button.disabled = false;
      button.innerHTML = `<i class="fas fa-shopping-cart"></i>Add to cart`;
    }
    if (cart.length == 0) {
      let uiObj = new UI();

      uiObj.hideCart();
    }
  }
  static getSingleButton(id) {
    let addToCartButtons = [...document.querySelectorAll(".add-btn")];
    return addToCartButtons.find(button => button.dataset.id == id);
  }
  static setupApp(customerCart) {
    let productIDs = [];
    if (customerCart.length > 0) {
      customerCart.map(item => {
        productIDs.push({ id: item.id, items: item.items });
        this.removeItem(item.id);
      });
    }
    cart.map(item => {
      productIDs.push({ id: item.id, items: item.items });
      this.removeItem(item.id);
    });

    productIDs.map(item => {
      this.addItemToCart(item.id, item.items);
    });
  }
  static saveCustomerCart(cart) {
    let customerID = document.querySelector("#customerID").value;

    if (customerID != "") {
      $.ajax({
        url: "../php/fetch_cart.php",
        method: "POST",
        dataType: "text",
        data: {
          key: "save_cart",
          customerID: customerID,
          cart: JSON.stringify(cart),
        },
        success: data => {
          // console.log(data);
        },
        error: data => {
          console.log(data);
        },
      });
    }
  }
  static getCustomerCart() {
    let customerID = document.querySelector("#customerID").value;
    if (customerID != "") {
      $.ajax({
        url: "../php/fetch_cart.php",
        method: "POST",
        dataType: "text",
        data: {
          key: "get_cart",
          customerID: customerID,
        },
        success: data => {
          let customerCart = JSON.parse(data);
          this.setupApp(customerCart);
        },
        error: data => {
          console.log(data);
        },
      });
    } else {
      this.setupApp(cart);
    }
  }
  static checkEmptyCart(cart) {
    // if (cart.length == 0) {
    //   clearCartBtn.disabled = true;
    //   checkoutBtn.disabled = true;
    // } else {
    //   clearCartBtn.disabled = false;
    //   checkoutBtn.disabled = false;
    // }
  }

  static viewProduct(id) {
    window.location.href = "../product/?pid=" + id;
  }
}

class Storage {
  static storeCartInLocaLStorage(cart) {
    localStorage.setItem("My_super_cart", JSON.stringify(cart));
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const ui = new UI();

  Cart.getCustomerCart();
  ui.EventsListeners();
  Cart.cartLogic();
});
