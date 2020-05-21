class UI {
  static showStores() {
    $(".list-stores").css({ display: "block" });
    $(".add-store").css({ display: "none" });
    $(".edit-store").css({ display: "none" });
  }

  static addStore() {
    $(".list-stores").css({ display: "none" });
    $(".add-store").css({ display: "block" });
    $(".edit-store").css({ display: "none" });
  }
}

class Events {
  eventsListener() {
    $("#add-btn").click(() => {
      $("#store-name").val("");
      $(".add-result").html("");
      $("#add-store").css({ display: "block" });
      $("#update-store").css({ display: "none" });
      App.getLocations("");
      UI.addStore();
    });

    $(".cancel-btn").click(() => {
      $(".add-result").html("");
      $("#add-store").css({ display: "none" });
      $("#update-store").css({ display: "block" });
      $("#store-name").val("");
      UI.showStores();
    });
    $("#addForm").submit(e => {
      e.preventDefault();
    });
    $("#add-store").click(() => {
      App.addEditStore("", "add_store");
    });
  }
}

class App {
  static fetchStores(start, limit) {
    $.ajax({
      url: "../php/stores.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "fetch_stores",
        start: start,
        limit: limit,
      },
      success: response => {
        if (response != "no_more") {
          $(".stores-tbody").append(response);
          start += limit;
          this.fetchStores(start, limit);
        } else {
          $(".stores-table").DataTable();
        }
      },
      error: response => {
        console.log(response);
      },
    });
  }

  static deleteStore(id) {
    if (confirm("The selected store will be deleted permanently!")) {
      $.ajax({
        url: "../php/stores.php",
        method: "POST",
        dataType: "text",
        data: {
          key: "delete_store",
          id: id,
        },
        success: response => {
          if (response == "deleted") {
            alert("Store Deleted Successfully");
            $("#store_" + id)
              .parent()
              .remove();
          } else if (response == "foreign_key") {
            alert(
              "Cannot delete this Store. Certain product/s are/is in this Store!"
            );
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

  static getLocations(id) {
    $.ajax({
      url: "../php/stores.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "get_locations",
        storeID: id,
      },
      success: response => {
        $("#store-location").html("");

        $("#store-location").append(response);
      },
      error: response => {
        console.log(response);
      },
    });
  }

  static addEditStore(id, key) {
    let storeInput = document.querySelector("#store-name");
    let selectLocation = document.querySelector("#store-location");
    if (!this.checkEmpty(storeInput) && !this.checkEmpty(selectLocation)) {
      let store = document.querySelector("#store-name").value;
      let location = document.querySelector("#store-location").value;
      if (store.length >= 3) {
        $(".add-result").html("");
        $.ajax({
          url: "../php/stores.php",
          method: "POST",
          dataType: "text",
          data: {
            key: key,
            store: store,
            id: id,
            location: location,
          },
          success: response => {
            if (response == "added") {
              $(".add-result").html(
                '<p class="text-success">Store successfully added.</>'
              );
              storeInput.value = "";
              $(".stores-tbody").html("");
              App.fetchStores(0, 10);
              return;
            } else if (response == "exist") {
              $(".add-result").html(
                '<p class="text-danger">Store name exists!</>'
              );
              return;
            } else if (response == "updated") {
              $(".stores-tbody").html("");
              $("#add-store").css({ display: "block" });
              $("#update-store").css({ display: "none" });
              storeInput.value = "";
              $(".add-result").html(
                '<p class="text-success">Store updated successfully</>'
              );
              App.fetchStores(0, 10);
              return;
            } else {
              console.log(response);
            }
          },
          error: response => {
            console.log(response);
          },
        });
      } else {
        $(".add-result").html(
          '<p class="text-danger">Store name too short!</>'
        );
      }
    }
  }

  static editStore(id, key) {
    $(".add-result").html("");
    $("#add-store").css({
      display: "none",
    });
    $("#update-store").css({
      display: "block",
    });
    UI.addStore();
    $.ajax({
      url: "../php/stores.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "get_data",
        id: id,
      },
      success: data => {
        let response = JSON.parse(data);
        $("#store-location").html("");
        $("#store-location").append(response.locations);
        $("#store-name").val(response.store_name);
        UI.addStore();
        $("#update-store").click(e => {
          e.preventDefault();
          App.addEditStore(id, key);
        });
      },
    });
  }

  static checkEmpty(caller) {
    $(".add-result").html("");

    if (caller.value.trim() == "") {
      caller.style = "border:1px solid red";
      return true;
    } else {
      caller.style = "border:1px solid black";
      return false;
    }
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const events = new Events();
  App.fetchStores(0, 10);
  events.eventsListener();
});
