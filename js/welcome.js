let modal = document.querySelector(".modal");
let bannerBtn = document.querySelector(".ban-btn");
let closeBtn = document.querySelector(".close-btn");
let dropbtn = document.querySelector(".dropbtn");
let dropdownContent = document.querySelector("#dropdown-content");
let modalBody = document.querySelector(".modal-body");
let table = document.querySelector(".list-supermarkets");
let modalForm = document.querySelector("#modal-form");

bannerBtn.addEventListener("click", () => {
  modal.style.display = "block";
});

closeBtn.addEventListener("click", () => {
  modal.style.display = "none";
});

window.addEventListener("click", e => {
  if (e.target === modal) {
    modal.style.display = "none";
  }
});
dropbtn.addEventListener("click", () => {
  dropdownContent.classList.toggle("show-content");
});

window.onclick = e => {
  if (!e.target.matches(".dropbtn")) {
    dropdownContent.classList.remove("show-content");
  }
};

class GetStores {
  sendModalValues() {
    modalForm.addEventListener("submit", e => {
      e.preventDefault();
      let location = document.querySelector("#location").value;
      let store = document.querySelector("#supermarket").value;
      if (location == "0" && store == "0") {
        document.querySelector("#location").style = "border:1px solid red";
        document.querySelector("#supermarket").style = "border:1px solid red";
      } else {
        window.location.href =
          "./home/?location=" + location + "&store=" + store + "";
      }
    });
  }

  sendStoresTableValues() {
    table.addEventListener("click", e => {
      if (e.target.classList.contains("storeID")) {
        let storeID = e.target.dataset.id;
        window.location.href = "./home/?store=" + storeID + "";
      }
    });
  }

  getForTable(start, limit) {
    $.ajax({
      url: "./php/fetch_stores.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "table",
        start: start,
        limit: limit,
      },
      success: data => {
        if (data != "no_more") {
          $("#list-supermarkets-tbody").append(data);
          start += limit;
          this.getForTable(start, limit);
        } else {
          $(".list-supermarkets").DataTable();
        }
      },
      error: data => {
        console.log(data);
      },
    });
  }

  getForModal() {
    $.ajax({
      url: "./php/fetch_stores.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "modal",
      },
      success: data => {
        modalBody.innerHTML = data;
      },
      error: data => {
        modalBody.innerHTML = data;
      },
    });
  }
  logout() {
    if (document.getElementById("logout-btn") != null) {
      let logoutBtn = document.getElementById("logout-btn");
      logoutBtn.addEventListener("click", () => {
        $.ajax({
          url: "./php/logout.php",
          method: "POST",
          dataType: "text",
          data: {
            key: "logout",
          },
          success: data => {
            if (data == "logged_out") {
              console.log(data);

              window.location.href = "./login/";
            }
          },
          error: data => {
            console.log(data);
          },
        });
      });
    }
  }
}

window.addEventListener("DOMContentLoaded", () => {
  const getStores = new GetStores();
  getStores.getForTable(1, 10);
  getStores.getForModal();
  getStores.sendModalValues();
  getStores.sendStoresTableValues();
  getStores.logout();
});
