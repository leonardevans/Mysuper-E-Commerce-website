let dropbtn = document.querySelector(".dropbtn");
let dropdownContent = document.querySelector("#dropdown-content");
let catdropbtn = document.querySelector(".category-dropbtn");
let catdropdownContent = document.querySelector(".cat-dropdown-content");

dropbtn.addEventListener("click", () => {
  dropdownContent.classList.toggle("show-content");
});

catdropbtn.addEventListener("click", () => {
  catdropdownContent.classList.toggle("show-content");
});

window.onclick = e => {
  if (!e.target.matches(".dropbtn")) {
    dropdownContent.classList.remove("show-content");
  }
  if (!e.target.matches(".category-dropbtn")) {
    catdropdownContent.classList.remove("show-content");
  }
};
