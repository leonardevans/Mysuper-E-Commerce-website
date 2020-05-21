class UI {
  static showCategories() {
    $(".list-categories").css({ display: "block" });
    $(".add-category").css({ display: "none" });
    $(".edit-category").css({ display: "none" });
  }

  static showAddCategory() {
    $(".list-categories").css({ display: "none" });
    $(".add-category").css({ display: "block" });
    $(".edit-category").css({ display: "none" });
  }
}

class Events {
  eventsListener() {
    $("#add-btn").click(() => {
      $(".add-result").html("");
      $("#add-category").css({ display: "block" });
      $("#update-category").css({ display: "none" });
      $("#category-name").val("");
      UI.showAddCategory();
    });

    $(".cancel-btn").click(() => {
      $(".add-result").html("");
      $("#add-category").css({ display: "block" });
      $("#update-category").css({ display: "none" });
      UI.showCategories();
    });
    $("#add-category").click(() => {
      App.addEditCategory("", "add_category");
    });
    $("#addForm").submit(e => {
      e.preventDefault();
    });
  }
}

class App {
  static fetchCategories(start, limit) {
    $.ajax({
      url: "../php/categories.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "fetch_categories",
        start: start,
        limit: limit,
      },
      success: response => {
        if (response != "no_more") {
          $(".categories-tbody").append(response);
          start += limit;
          this.fetchCategories(start, limit);
        } else {
          $(".categories-table").DataTable();
        }
      },
      error: response => {
        console.log(response);
      },
    });
  }

  static addEditCategory(id, key) {
    let catInput = document.querySelector("#category-name");
    if (!this.checkEmpty(catInput)) {
      let cat = catInput.value.trim();
      if (cat.length >= 3) {
        $(".add-result").html("");
        $.ajax({
          url: "../php/categories.php",
          method: "POST",
          dataType: "text",
          data: {
            key: key,
            category: cat,
            id: id,
          },
          success: response => {
            if (response == "added") {
              $(".add-result").html(
                '<p class="text-success">Category Added!</>'
              );
              catInput.value = "";
              $(".categories-tbody").html("");
              App.fetchCategories(0, 10);
              return;
            } else if (response == "updated") {
              $(".categories-tbody").html("");
              $("#add-category").css({ display: "block" });
              $("#update-category").css({ display: "none" });
              catInput.value = "";
              $(".add-result").html(
                '<p class="text-success">Category updated successfully</>'
              );
              App.fetchCategories(0, 10);
              return;
            } else if (response == "exist") {
              $(".add-result").html(
                '<p class="text-danger">Category name exists!</>'
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
          '<p class="text-danger">Category name too short!</>'
        );
      }
    }
  }

  static editCategory(id, key) {
    let catInput = document.querySelector("#category-name");
    $(".add-result").html("");
    $("#add-category").css({
      display: "none",
    });
    $("#update-category").css({
      display: "block",
    });
    UI.showAddCategory();
    $.ajax({
      url: "../php/categories.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "get_data",
        id: id,
      },
      success: data => {
        catInput.value = data;
        $("#update-category").click(e => {
          e.preventDefault();
          App.addEditCategory(id, key);
        });
      },
    });
  }

  static deleteCategory(id) {
    if (confirm("The selected category will be deleted permanently!")) {
      $.ajax({
        url: "../php/categories.php",
        method: "POST",
        dataType: "text",
        data: {
          key: "delete_category",
          categoryID: id,
        },
        success: response => {
          if (response == "category_deleted") {
            alert("Category Deleted Successfully");
            $("#category_" + id)
              .parent()
              .remove();
          } else if (response == "foreign_key") {
            alert(
              "Cannot delete Category. Certain product/s are/is in this category!"
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
  App.fetchCategories(0, 10);

  events.eventsListener();
});
