class App {
  static fetchOrders() {
    $.ajax({
      url: "../php/home.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "latest_orders",
      },
      success: response => {
        $(".latest-orders-tbody").append(response);
        $(".latest-orders-tbody").click(e => {
          let id = e.target.dataset.id;
          if (id != "") {
            window.location.href = "../orders/?oid=" + id;
          }
        });
        return;
      },
      error: response => {
        console.log(response);
      },
    });
  }
}

document.addEventListener("DOMContentLoaded", () => {
  App.fetchOrders();
});
