const ordersOverlay = document.querySelector(".orders-overlay");
const ordersDiv = document.querySelector(".orders");
const ordersBody = document.querySelector(".orders-body");
let showOrdersBtn = document.querySelector(".my-orders-btn");
const closeOrdersBtn = document.querySelector(".close-orders");
if (showOrdersBtn != undefined) {
  showOrdersBtn.addEventListener("click", () => {
    Orders.getOrders();
  });
}
closeOrdersBtn.addEventListener("click", () => {
  ordersOverlay.style.display = "none";
});

window.addEventListener("click", e => {
  if (e.target === ordersOverlay) {
    ordersOverlay.style.display = "none";
  }
});

class Orders {
  static getOrders() {
    let customerID = document.querySelector("#customerID").value;

    $.ajax({
      url: "../php/fetch_orders.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "get_orders",
        customerID: customerID,
      },
      success: data => {
        ordersBody.innerHTML = data;
        ordersOverlay.style.display = "block";
      },
      error: data => {
        console.log(data);
      },
    });
  }

  static showMoreDetails() {
    ordersBody.addEventListener("click", e => {
      if (e.target.matches(".view-more")) {
        let button = e.target;
        if (button.classList.contains("open-details")) {
          button.classList.remove("open-details");
          button.classList.add("close-details");
          button.innerText = "Close Details";

          button.parentElement.parentElement.parentElement.parentElement.nextElementSibling.style.display =
            "block";
        } else if (button.classList.contains("close-details")) {
          button.classList.add("open-details");
          button.classList.remove("close-details");
          button.innerText = "View Details";
          button.parentElement.parentElement.parentElement.parentElement.nextElementSibling.style.display =
            "none";
        }
      }
    });
  }
}

Orders.showMoreDetails();
