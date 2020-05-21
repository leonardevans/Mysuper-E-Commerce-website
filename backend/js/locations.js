class UI {
  static showLocations() {
    $(".list-locations").css({ display: "block" });
    $(".add-location").css({ display: "none" });
    $(".edit-location").css({ display: "none" });
  }
  static showAddLocation() {
    $(".list-locations").css({ display: "none" });
    $(".add-location").css({ display: "block" });
    $(".edit-location").css({ display: "none" });
  }
}

class Events {
  static eventsListener() {
    $("#add-btn").click(() => {
      $(".add-result").html("");
      $("#submit-location-btn").css({ display: "block" });
      $("#update-location-btn").css({ display: "none" });
      $("#add-location-name").val("");
      UI.showAddLocation();
    });

    $(".cancel-btn").click(() => {
      $(".add-result").html("");
      $("#submit-location-btn").css({ display: "block" });
      $("#update-location-btn").css({ display: "none" });
      UI.showLocations();
    });
    $("#submit-location-btn").click(e => {
      e.preventDefault();
      App.addEditLocation("", "add_location");
    });
    $("#addForm").submit(e => {
      e.preventDefault();
    });
  }
}

class App {
  static fetchLocations(start, limit) {
    $.ajax({
      url: "../php/locations.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "fetch_locations",
        start: start,
        limit: limit,
      },
      success: response => {
        if (response != "no_more") {
          $(".locations-tbody").append(response);
          start += limit;
          this.fetchLocations(start, limit);
        } else {
          $(".locations-table").DataTable();
        }
      },
      error: response => {
        console.log(response);
      },
    });
  }
  static addEditLocation(id, key) {
    let locationInput = document.querySelector("#add-location-name");
    if (!this.checkEmpty(locationInput)) {
      let location = locationInput.value.trim();
      if (location.length >= 3) {
        $(".add-result").html("");
        $.ajax({
          url: "../php/locations.php",
          method: "POST",
          dataType: "text",
          data: {
            key: key,
            location_name: location,
            id: id,
          },
          success: response => {
            if (response === "location_added") {
              $(".add-result").html(
                '<p class="text-success">Location Added!</>'
              );
              locationInput.value = "";
              $(".locations-tbody").html("");
              App.fetchLocations(0, 10);
              return;
            } else if (response === "location_updated") {
              $(".locations-tbody").html("");
              $("#submit-location-btn").css({ display: "block" });
              $("#update-location-btn").css({ display: "none" });
              locationInput.value = "";
              $(".add-result").html(
                '<p class="text-success">Location updated successfully</>'
              );
              App.fetchLocations(0, 10);
              return;
            } else if (response === "location_exist") {
              $(".add-result").html(
                '<p class="text-danger">Location name exists!</>'
              );
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
          '<p class="text-danger">Location name too short!</>'
        );
      }
    }
  }

  static deleteLocation(id) {
    if (confirm("The selected location will be deleted permanently!")) {
      $.ajax({
        url: "../php/locations.php",
        method: "POST",
        dataType: "text",
        data: {
          key: "delete_location",
          locationID: id,
        },
        success: response => {
          if (response == "location_deleted") {
            alert("Location Deleted Successfully");
            $("#location_" + id)
              .parent()
              .remove();
          } else if (response == "foreign_key") {
            alert(
              "Cannot delete Location. Certain store/s are/is in this Location!"
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

  static editLocation(id, key) {
    let locationInput = document.querySelector("#add-location-name");
    $(".add-result").html("");
    $("#submit-location-btn").css({
      display: "none",
    });
    $("#update-location-btn").css({
      display: "block",
    });
    UI.showAddLocation();
    $.ajax({
      url: "../php/locations.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "get_data",
        id: id,
      },
      success: data => {
        locationInput.value = data;
        $("#update-location-btn").click(e => {
          e.preventDefault();
          App.addEditLocation(id, key);
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
  // const events = new Events();
  App.fetchLocations(0, 10);
  Events.eventsListener();
});
