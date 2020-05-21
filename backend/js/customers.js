function fetchCustomers(start, limit) {
  $.ajax({
    url: "../php/customers.php",
    method: "POST",
    dataType: "text",
    data: {
      key: "fetch_customers",
      start: start,
      limit: limit,
    },
    success: response => {
      if (response != "no_more") {
        $(".customers-tbody").append(response);
        start += limit;
        fetchCustomers(start, limit);
      } else {
        $(".customers-table").DataTable();
      }
    },
    error: response => {
      console.log(response);
    },
  });
}

document.addEventListener("DOMContentLoaded", () => {
  fetchCustomers(0, 10);
});
