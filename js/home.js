const productsContainer = document.querySelector(".products");
const categoryList = document.querySelector(".category-list");
const catDropContent = document.querySelector(".cat-dropdown-content");
const categoryContainer = document.querySelector(".categories");
const searchForm = document.getElementById("search_form");
const searchInput = document.getElementById("search");
const resultTitle = document.querySelector(".product-category");
const locationName = document.querySelector("#locationName").value;
const storeName = document.querySelector("#storeName").value;

let modal = document.querySelector(".modal");
let modalBody = document.querySelector(".modal-body");
let closeBtn = document.querySelector(".close-btn");
let cancelBtn = document.querySelector(".cancel-btn");
let signInFirstBtn = document.querySelector(".sign-in-btn");

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
  getProducts() {
    let key;
    if (locationName != "" || storeName != "") {
      key = "specific";
    } else {
      key = "loaded";
    }
    $.ajax({
      url: "../php/fetch_items.php",
      method: "POST",
      dataType: "text",
      data: {
        key: key,
        locationName: locationName,
        storeName: storeName,
      },

      success: data => {
        productsContainer.innerHTML = data;
      },
    });
  }
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
            productsContainer.innerHTML = data;
            resultTitle.innerHTML = "Results for: " + searchInput.value.trim();
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
            productsContainer.innerHTML = data;
            resultTitle.innerHTML = "Results for: " + searchInput.value.trim();
          },
        });
      } else {
        this.getProducts();
        resultTitle.innerHTML = "All products";
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
            productsContainer.innerHTML = data;
            resultTitle.innerHTML = "Results for: " + e.target.innerText;
          },
        });
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
}

document.addEventListener("DOMContentLoaded", () => {
  searchInput.value = "";
  const products = new Products();
  products.getProducts();
  products.searchProducts();
  products.getCategories();
  products.logout();
});
