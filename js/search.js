let showSearchButton = document.querySelector("#show_search");
let searchInputs = document.querySelector(".search_inputs");
let searchAndCart = document.querySelector(".search_cart");
let cartItems = document.querySelector(".cart-items");

showSearchButton.addEventListener("click", () => {
  showSearchButton.style.display = "none";
  searchInputs.style.display = "block";
});

window.addEventListener("click", e => {
  if (e.target === searchAndCart) {
    searchInputs.style.display = "none";
    showSearchButton.style.display = "block";
  }
});

cartItems.addEventListener("click", () => {
  searchInputs.style.display = "none";
  showSearchButton.style.display = "block";
});
