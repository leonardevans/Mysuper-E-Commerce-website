class UI {
  static showProducts() {
    $(".list-products").css({ display: "block" });
    $(".add-product").css({ display: "none" });
    $(".view-product").css({ display: "none" });
    $(".edit-product").css({ display: "none" });
  }
  static editProduct() {
    $(".list-products").css({ display: "none" });
    $(".add-product").css({ display: "none" });
    $(".view-product").css({ display: "none" });
    $(".edit-product").css({ display: "block" });
  }
  static addProduct() {
    $(".list-products").css({ display: "none" });
    $(".add-product").css({ display: "block" });
    $(".view-product").css({ display: "none" });
    $(".edit-product").css({ display: "none" });
  }
  static viewProduct() {
    $(".list-products").css({ display: "none" });
    $(".add-product").css({ display: "none" });
    $(".view-product").css({ display: "block" });
    $(".edit-product").css({ display: "none" });
  }
}

class Events {
  eventsListener() {
    $(".add-btn").click(() => {
      $(".edit-product-images").html("");
      $("#add-product-form")[0].reset();
      $("#update-product-btn").css({ display: "none" });
      $("#add-product-btn").css({ display: "block" });
      $(".add-result").html("");
      $(".images-error").html("");
      CKEDITOR.instances["add-product-description"].setData("");
      App.getStoresCategories("");
      UI.addProduct();
    });
    $(".edit-btn").click(() => {
      UI.editProduct();
    });
    $(".cancel-btn").click(() => {
      $(".edit-product-images").html("");

      CKEDITOR.instances["add-product-description"].setData("");
      $(".images-error").html("");

      $("#update-product-btn").css({
        display: "none",
      });
      $("#add-product-btn").css({
        display: "block",
      });
      $(".add-result").html("");
      $(".products-tbody").empty();
      $(".reviews-tbody").empty();
      App.fetchProducts(0, 10);
      UI.showProducts();
    });
    $(".view-btn").click(() => {
      UI.viewProduct();
    });
    $("#add-product-form").submit(e => {
      e.preventDefault();

      App.addProductToDb("");
    });
  }
}

class App {
  static fetchProducts(start, limit) {
    $.ajax({
      url: "../php/products.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "fetch_products",
        start: start,
        limit: limit,
      },
      success: response => {
        if (response != "no_more") {
          $(".products-tbody").append(response);
          start += limit;
          this.fetchProducts(start, limit);
        } else {
          $(".products-table").DataTable();
        }
      },
      error: response => {
        console.log(response);
      },
    });
  }

  static deleteProduct(id) {
    if (confirm("The selected product will be deleted permanently!")) {
      $.ajax({
        url: "../php/products.php",
        method: "POST",
        dataType: "text",
        data: {
          key: "delete_product",
          id: id,
        },
        success: response => {
          if (response == "deleted") {
            alert("Product Deleted Successfully");
            $("#product_" + id)
              .parent()
              .remove();
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

  static viewProductDetails(id) {
    $.ajax({
      url: "../php/products.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "view_product",
        id: id,
      },
      success: response => {
        if (response == "not_found") {
          App.fetchProducts(0, 10);
        } else {
          let data = JSON.parse(response);
          $(".product-details").html(data.productDetails);
          $(".show-product-images").html(data.images);
          $(".product-description").html(data.description);
          $(".likes").text(data.likes);
          $(".dislikes").text(data.dislikes);
          $(".views").text(data.views);
          $(".edit-product-btn").attr(
            "onclick",
            'App.getProductDetails("' + id + '")'
          );
          $(".reviews-tbody").empty();

          App.fetchReviews(id, 0, 10);
          UI.viewProduct();
        }
      },
      error: response => {
        console.log(response);
      },
    });
  }

  static fetchReviews(id, start, limit) {
    $.ajax({
      url: "../php/products.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "get_reviews",
        id: id,
        start: start,
        limit: limit,
      },
      success: response => {
        if (response == "no_more") {
          $(".reviews-table").DataTable();
        } else {
          $(".reviews-tbody").append(response);
          start += limit;
          App.fetchReviews(id, start, limit);
        }
      },
      error: response => {
        console.log(response);
      },
    });
  }
  static getStoresCategories(id) {
    $.ajax({
      url: "../php/products.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "get_stores_categories",
        id: id,
      },
      success: response => {
        let data = JSON.parse(response);
        $("#add-product-store").html(data.stores);
        $("#add-product-category").html(data.categories);
      },
      error: response => {
        console.log(response);
      },
    });
  }

  static addProductToDb(id) {
    let nameInput = document.querySelector("#add-product-name");
    let priceInput = document.querySelector("#add-product-price");
    let stockInput = document.querySelector("#add-product-stock");
    let description = CKEDITOR.instances["add-product-description"].getData();
    if (
      !this.checkEmpty(nameInput) &&
      !this.checkEmpty(priceInput) &&
      !this.checkEmpty(stockInput)
    ) {
      let name = nameInput.value;
      let price = priceInput.value;
      let stock = stockInput.value;
      if (name.length >= 3) {
        $(".add-result").html("");
        if (isNaN(price) || price == 0) {
          $(".add-result").html(
            '<p class="text-danger">Price should be a number and not zero</p>'
          );
          return;
        } else {
          $(".add-result").html("");
          if (isNaN(stock) || stock == 0) {
            $(".add-result").html(
              '<p class="text-danger">Stock should be a number and not zero!</p>'
            );
            return;
          } else {
            $(".add-result").html("");
            if (description == "") {
              $(".add-result").html(
                '<p class="text-danger">Enter product description</p>'
              );
              return;
            } else {
              $(".add-result").html("");
              if (description.length < 10) {
                $(".add-result").html(
                  '<p class="text-danger">Description too short</p>'
                );
                return;
              } else {
                $(".add-result").html("");
                if (id != "") {
                  $(".add-result").html("");
                  let form_data = new FormData($("#add-product-form")[0]);
                  form_data.append("productID", id);
                  form_data.append("product_description", description);
                  $.ajax({
                    url: "../php/update_product.php",
                    method: "POST",
                    dataType: "text",
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: () => {
                      $(".add-result").html(
                        '<p class="text-success">Updating product details</p>'
                      );
                    },
                    success: response => {
                      if (response == "updated") {
                        $(".add-result").html(
                          '<p class="text-success">Product updated successfully</p>'
                        );
                        $("#add-product-form")[0].reset();
                        CKEDITOR.instances["add-product-description"].setData(
                          ""
                        );
                        $(".edit-product-images").html("");
                        $("#update-product-btn").css({ display: "none" });
                        $("#add-product-btn").css({ display: "block" });
                        App.viewProductDetails(id);
                      } else if (response == "exist") {
                        $(".add-result").html(
                          '<p class="text-danger">Product name exist</p>'
                        );
                      } else {
                        console.log(response);
                      }
                    },
                    error: response => {
                      console.log(response);
                    },
                  });
                } else {
                  if (
                    $("#add-product-image-1").val() == "" &&
                    $("#add-product-image-2").val() == "" &&
                    $("#add-product-image-3").val() == ""
                  ) {
                    $(".add-result").html(
                      '<p class="text-danger">Please select an image</p>'
                    );
                    return;
                  } else {
                    $(".add-result").html("");
                    let form_data = new FormData($("#add-product-form")[0]);
                    form_data.append("product_description", description);
                    $.ajax({
                      url: "../php/products.php",
                      method: "POST",
                      dataType: "text",
                      data: form_data,
                      contentType: false,
                      cache: false,
                      processData: false,
                      beforeSend: () => {
                        $(".add-result").html(
                          '<p class="text-success">Uploading product details</p>'
                        );
                      },
                      success: response => {
                        if (JSON.parse(response)) {
                          let data = JSON.parse(response);
                          $(".add-result").html(
                            '<p class="text-success">Product added successfully</p>'
                          );
                          $("#add-product-form")[0].reset();
                          CKEDITOR.instances["add-product-description"].setData(
                            ""
                          );
                          App.viewProductDetails(data.productID);
                        } else if (response == "exist") {
                          $(".add-result").html(
                            '<p class="text-danger">Product name exist</p>'
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
              }
            }
          }
        }
      } else {
        $(".add-result").html(
          '<p class="text-danger">Product name too short!</p>'
        );
        return;
      }
    }
  }

  static getProductDetails(id) {
    $.ajax({
      url: "../php/products.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "fetch_details",
        id: id,
      },
      success: response => {
        let data = JSON.parse(response);
        $("#add-product-name").val(data.details.name);
        $("#add-product-price").val(data.details.price);
        $("#add-product-stock").val(data.details.stock);
        CKEDITOR.instances["add-product-description"].setData(
          data.details.description
        );
        $("#add-product-category").html(data.categories);
        $("#add-product-store").html(data.stores);
        $(".edit-product-images").html(data.images);
        $("#update-product-btn").css({ display: "block" });
        $("#add-product-btn").css({ display: "none" });
        $(".add-result").html("");
        $(".images-error").html("");
        UI.addProduct();
        $("#add-product-form").submit(e => {
          $(".images-error").html("");

          e.preventDefault();
          App.addProductToDb(id);
        });
      },
      error: response => {
        console.log(response);
      },
    });
  }

  static deleteImage(productID, imageID) {
    if (confirm("This image will be deleted permanently!")) {
      $.ajax({
        url: "../php/products.php",
        method: "POST",
        dataType: "text",
        data: {
          key: "delete_image",
          imageID: imageID,
          productID: productID,
        },
        success: response => {
          if (response == "deleted") {
            $(".images-error").html(
              '<p class="text-success">Image deleted successfully!</p>'
            );
            $("#image_" + imageID).remove();
          } else if (response == "reached_min") {
            $(".images-error").html(
              '<p class="text-danger">Cannot delete more images for this product. Product should have atleast one image</p>'
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

  static validateImage(input, file) {
    var ext = file.split(".");
    ext = ext[ext.length - 1].toLowerCase();
    var arrayExtensions = ["jpg", "jpeg", "png", "bmp", "gif"];

    if (arrayExtensions.lastIndexOf(ext) == -1) {
      alert("Wrong extension type.");
      input.value = "";
    }
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const events = new Events();
  let setProductId = $("#get-product-id").val();
  if (setProductId != "" && setProductId != undefined) {
    App.viewProductDetails(setProductId);
  } else {
    App.fetchProducts(0, 10);
  }
  events.eventsListener();
});
