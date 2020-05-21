class MainApp {
  static fetchTotals() {
    $.ajax({
      url: "../php/home.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "fetch_totals",
      },
      success: response => {
        let data = JSON.parse(response);
        $(".total-products").text(data.products);
        $(".total-stores").text(data.stores);
        $(".total-categories").text(data.categories);
        $(".total-locations").text(data.locations);
        $(".total-orders").text(data.orders);
        $(".total-customers").text(data.customers);
      },
      error: response => {
        console.log(response);
      },
    });
  }

  static logout() {
    $.ajax({
      url: "../php/logout.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "logout",
      },
      success: data => {
        if (data == "logged_out") {
          window.location.href = "../";
        }
      },
      error: data => {
        console.log(data);
      },
    });
  }
}

document.addEventListener("DOMContentLoaded", () => {
  MainApp.fetchTotals();
});
