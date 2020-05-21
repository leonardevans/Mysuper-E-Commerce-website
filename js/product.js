const container = document.querySelector(".container");
const categoryList = document.querySelector(".category-list");
const catDropContent = document.querySelector(".cat-dropdown-content");
let modal = document.querySelector(".modal");
let modalBody = document.querySelector(".modal-body");
let closeBtn = document.querySelector(".close-btn");
let cancelBtn = document.querySelector(".cancel-btn");
let signInFirstBtn = document.querySelector(".sign-in-btn");
const categoryContainer = document.querySelector(".categories");
const searchForm = document.getElementById("search_form");
const searchInput = document.getElementById("search");
const productDetails = document.querySelector(".product-details");
const productDescription = document.querySelector(".p-description");
const productReviews = document.querySelector(".reviews");

let myImage;
let imageArray;
let prevIndex;
let imageIndex;

closeBtn.addEventListener("click", () => {
  modal.style.display = "none";
});
cancelBtn.addEventListener("click", () => {
  modal.style.display = "none";
});

window.addEventListener("click", e => {
  if (e.target === modal) {
    modal.style.display = "none";
  }
});

class Products {
  searchProducts() {
    searchForm.addEventListener("submit", e => {
      e.preventDefault();
      if (searchInput.value.trim() != "") {
        $.ajax({
          url: "../php/fetch_items.php",
          method: "POST",
          dataType: "text",
          data: {
            key: "search",
            search_value: searchInput.value.trim(),
          },
          success: data => {
            container.innerHTML =
              '<div class="section-title"><h3 class ="product-category">Results for: ' +
              searchInput.value +
              '<h3></div><div class="products">' +
              data +
              "</div>";
          },
        });
      }
    });
    searchInput.addEventListener("input", () => {
      if (searchInput.value.trim() != "") {
        $.ajax({
          url: "../php/fetch_items.php",
          method: "POST",
          dataType: "text",
          data: {
            key: "search",
            search_value: searchInput.value.trim(),
          },
          success: data => {
            container.innerHTML =
              '<div class="section-title"><h3 class ="product-category">Results for: ' +
              searchInput.value +
              '<h3></div><div class="products">' +
              data +
              "</div>";
          },
        });
      }
    });
  }
  getCategories() {
    $.ajax({
      url: "../php/fetch_items.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "categories",
      },
      success: data => {
        categoryList.innerHTML = data;
        catDropContent.innerHTML = data;
      },
    });
    this.getCategoryButtons();
  }
  getCategoryButtons() {
    categoryContainer.addEventListener("click", e => {
      if (e.target.matches(".category")) {
        let categoryID = e.target.dataset.id;
        $.ajax({
          url: "../php/fetch_items.php",
          method: "POST",
          dataType: "text",
          data: {
            key: "category",
            categoryID: categoryID,
          },
          success: data => {
            container.innerHTML =
              '<div class="section-title"><h3 class ="product-category">Results for: ' +
              e.target.innerText +
              '<h3></div><div class="products">' +
              data +
              "</div>";
          },
        });
      }
    });
  }
  getProductDetails() {
    if (document.getElementById("productID") != null) {
      let productID = document.getElementById("productID").value;
      let customerID = document.querySelector("#customerID").value;
      if (customerID == "") {
        customerID = 1;
      }
      $.ajax({
        url: "../php/fetch_product_details.php",
        method: "POST",
        dataType: "text",
        data: {
          key: "product_details",
          productID: productID,
          customerID: customerID,
        },
        success: data => {
            if (data == "No_such_product") {
            window.location.href = "../home/";
          }
          else if (JSON.parse(data)) {
            let response = JSON.parse(data);
            productDetails.innerHTML = response.productDetails;
            productDescription.innerHTML = response.description;
            productReviews.innerHTML = response.reviews;
            myImage = document.getElementById("product-images");
            imageIndex = document.querySelector(".index");
            imageArray = JSON.parse(
              document.getElementById("imagesArray").innerText
            );
            prevIndex = imageArray.length - 1;
          }else{
              console.log(data);
          }
        },
      });
    } else {
      window.location = "../home/";
    }
  }

  getRelatedProducts() {
    let relatedProductsContainer = document.querySelector(
      ".related-products-container"
    );
    let productID = document.getElementById("productID").value;
    $.ajax({
      url: "../php/fetch_product_details.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "related",
        productID: productID,
      },
      success: data => {
        relatedProductsContainer.innerHTML = data;
      },
    });
  }
  makeAReview() {
    let reviewForm = document.querySelector("#reviewForm");

    reviewForm.addEventListener("submit", e => {
      e.preventDefault();
      let productID = document.getElementById("productID").value;
      let customerID = document.querySelector("#customerID").value;

      let reviewInputElement = document.querySelector("#reviewText");
      let reviewText = reviewInputElement.value.trim();

      if (reviewText == "") {
        reviewInputElement.style = "border:1px solid red";
      } else {
        reviewInputElement.style = "border:none";
        if (customerID != "") {
          reviewInputElement.value = "";
          $.ajax({
            url: "../php/fetch_product_details.php",
            method: "POST",
            dataType: "text",
            data: {
              key: "makeAReview",
              productID: productID,
              customerID: customerID,
              review: reviewText,
            },
            success: data => {
              productReviews.innerHTML = data;
            },
            error: data => {
              console.log(data);
            },
          });
        } else {
          signInFirstBtn.href = "../login/";
          signInFirstBtn.innerHTML = "Sign in";
          modalBody.innerHTML = "<h4>Sign in to make a review</h4>";
          modal.style.display = "block";
        }
      }
    });
  }
  logout() {
    if (document.getElementById("logout-btn") != null) {
      let logoutBtn = document.getElementById("logout-btn");
      logoutBtn.addEventListener("click", () => {
        $.ajax({
          url: "../php/logout.php",
          method: "POST",
          dataType: "text",
          data: {
            key: "logout",
          },
          success: data => {
            if (data == "logged_out") {
              window.location.href = "../login/";
            }
          },
          error: data => {
            alert(data);
          },
        });
      });
    }
  }
  setRecentlyViewed() {
    let productID = document.getElementById("productID").value;
    let recentProducts = [];

    if (localStorage.getItem("recentProducts")) {
      recentProducts = JSON.parse(localStorage.getItem("recentProducts"));
    } else {
      localStorage.setItem("recentProducts", JSON.stringify(recentProducts));
      recentProducts = JSON.parse(localStorage.getItem("recentProducts"));
    }
    if (!recentProducts.includes(productID)) {
      if (recentProducts.length >= 10) {
        recentProducts.shift();
        recentProducts[recentProducts.length] = productID;
        localStorage.setItem("recentProducts", JSON.stringify(recentProducts));
      } else {
        recentProducts[recentProducts.length] = productID;
        localStorage.setItem("recentProducts", JSON.stringify(recentProducts));
      }
    }
  }
  getRecentlyViewd() {
    let productID = document.getElementById("productID").value;
    let customerID = document.querySelector("#customerID").value;
    if (customerID == "") {
      customerID = 1;
    }
    if (localStorage.getItem("recentProducts")) {
      let recentProducts = JSON.parse(localStorage.getItem("recentProducts"));
      let jsonData = {
        customerID: customerID,
        productID: productID,
        recentProducts: recentProducts,
      };

      $.ajax({
        url: "../php/fetch_product_details.php",
        method: "POST",
        data: {
          key: "recentlyViewed",
          jsonData: jsonData,
        },
        success: data => {
          let recentProductscontainer = document.querySelector(
            ".recent-products-container"
          );
          recentProductscontainer.innerHTML = data;
        },
        error: data => {
          console.log(data);
        },
      });
    }
  }
}
document.addEventListener("DOMContentLoaded", () => {
  searchInput.value = "";
  const products = new Products();

  products.searchProducts();
  products.getCategories();
  products.getProductDetails();
  products.getRelatedProducts();
  products.makeAReview();
  products.logout();
  products.setRecentlyViewed();
  products.getRecentlyViewd();
});

let nextIndex = 1;
function changeIndex(action) {
  if (action == "next") {
    if (nextIndex > imageArray.length - 1) {
      nextIndex = 0;
    }
    myImage.setAttribute("src", imageArray[nextIndex]);
    imageIndex.innerText = nextIndex + 1;
    prevIndex = nextIndex - 1;
    nextIndex++;
  } else if (action == "prev") {
    if (prevIndex < 0) {
      prevIndex = imageArray.length - 1;
    }
    myImage.setAttribute("src", imageArray[prevIndex]);
    imageIndex.innerText = prevIndex + 1;
    nextIndex = prevIndex + 1;
    prevIndex--;
  }
}
