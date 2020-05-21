class UI {
  static showOrders() {
    $(".list-orders").css({ display: "block" });
    $(".view-order").css({ display: "none" });
  }
  static viewOrder() {
    $(".list-orders").css({ display: "none" });
    $(".view-order").css({ display: "block" });
  }
}

class Events {
  eventsListener() {
    $(".cancel-btn").click(() => {
      $(".orders-tbody").html("");
      App.fetchOrders(0, 10);
      UI.showOrders();
    });

    $("#update-order-form").submit(e => {
      e.preventDefault();
    });
  }
}

class App {
  static fetchOrders(start, limit) {
    $.ajax({
      url: "../php/orders.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "fetch_orders",
        start: start,
        limit: limit,
      },
      success: response => {
        if (response != "no_more") {
          $(".orders-tbody").append(response);
          start += limit;
          this.fetchOrders(start, limit);
        } else {
          $(".orders-table").DataTable();
        }
      },
      error: response => {
        console.log(response);
      },
    });
  }

  static viewOrderDetails(id) {
    $.ajax({
      url: "../php/orders.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "view_order",
        orderID: id,
      },
      success: response => {
        if (response == "not_found") {
          App.fetchOrders(0, 10);
        } else {
          let data = JSON.parse(response);
          $(".order-headers").html(data.orderHeaders);
          $(".order-details").html(data.orderDetails);
          $(".order-address").html(data.address);
          $(".order-items").html(data.orderItems);
          $("#order-statuses").val(data.status);
          $(".update-result").html("");
          UI.viewOrder();
          $("#update-order").click(() => {
            App.updateStatus(id);
          });
          $(".order-items").click(e => {
            if (e.target.dataset.id != undefined) {
              window.location.href = "../products/?pid=" + e.target.dataset.id;
            }
          });
        }
      },
      error: response => {
        console.log(response);
      },
    });
  }

  static updateStatus(id) {
    let prevStatus = $(".this-status").text();
    let newStatus = $("#order-statuses").val();
    let proceed = false;
    if (newStatus == prevStatus) {
      proceed = false;
      $(".update-result").html(
        '<p class="text-success">Order updated successfully.</p>'
      );
    } else {
      if (newStatus == "pending") {
        if (
          prevStatus != "received" &&
          prevStatus != "processing" &&
          prevStatus != "ready" &&
          prevStatus != "shipping" &&
          prevStatus != "complete"
        ) {
          proceed = true;
          $(".update-result").html("");
        } else {
          proceed = false;
          $(".update-result").html(
            '<p class="text-danger">Cannot rollback order</p>'
          );
        }
      } else if (newStatus == "received") {
        if (
          prevStatus != "processing" &&
          prevStatus != "ready" &&
          prevStatus != "shipping" &&
          prevStatus != "complete"
        ) {
          proceed = true;
          $(".update-result").html("");
        } else {
          proceed = false;
          $(".update-result").html(
            '<p class="text-danger">Cannot rollback order</p>'
          );
        }
      } else if (newStatus == "processing") {
        if (
          prevStatus != "ready" &&
          prevStatus != "shipping" &&
          prevStatus != "complete"
        ) {
          proceed = true;
          $(".update-result").html("");
        } else {
          proceed = false;
          $(".update-result").html(
            '<p class="text-danger">Cannot rollback order</p>'
          );
        }
      } else if (newStatus == "ready") {
        if (prevStatus != "shipping" && prevStatus != "complete") {
          proceed = true;
          $(".update-result").html("");
        } else {
          proceed = false;
          $(".update-result").html(
            '<p class="text-danger">Cannot rollback order</p>'
          );
        }
      } else if (newStatus == "shipping") {
        if (prevStatus != "complete") {
          proceed = true;
          $(".update-result").html("");
        } else {
          proceed = false;
          $(".update-result").html(
            '<p class="text-danger">Cannot rollback order</p>'
          );
        }
      } else if (newStatus == "complete") {
        proceed = true;
      }
    }
    if (proceed) {
      $.ajax({
        url: "../php/orders.php",
        method: "POST",
        dataType: "text",
        data: {
          key: "update_order",
          orderID: id,
          status: newStatus,
        },
        success: response => {
          if (response == "updated") {
            $(".update-result").html(
              '<p class="text-success">Order updated successfully.</p>'
            );
            $(".this-status").html(newStatus);
          } else {
            console.log(response);
          }
        },
        error: response => {
          console.log(response);
        },
      });
    }
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const events = new Events();
  let setOrderId = $("#get-order-id").val();
  if (setOrderId != "") {
    App.viewOrderDetails(setOrderId);
  } else {
    App.fetchOrders(0, 10);
  }
  events.eventsListener();
});
