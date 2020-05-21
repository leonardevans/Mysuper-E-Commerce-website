let customerID = document.querySelector("#customerID").value;
let productID = document.getElementById("productID").value;
let likeBtn;
let disLikeBtn;
let totalLikes;
let totalDislikes;

class LikeDislikes {
  likes() {
    let customerID = document.querySelector("#customerID").value;
    if (customerID == "") {
      signInFirstBtn.href = "../login/";
      signInFirstBtn.innerHTML = "Sign in";
      modalBody.innerHTML = "<h4>Sign in to make your view count</h4>";
      modal.style.display = "block";
    } else {
      $.ajax({
        url: "../php/fetch_like_dislikes.php",
        method: "POST",
        dataType: "json",
        data: {
          key: "like",
          customerID: customerID,
          productID: productID,
        },
        success: data => {
          totalLikes.innerText = data.likes;
          totalDisLikes.innerText = data.dislikes;

          if (data.action == "like") {
            likeBtn.classList.add("actioned");
            disLikeBtn.classList.remove("actioned");
          } else if (data.action == "unlike") {
            likeBtn.classList.remove("actioned");
          } else if (data.action == "dislike") {
            disLikeBtn.classList.add("actioned");
            likeBtn.classList.remove("actioned");
          } else if (data.action == "undislike") {
            disLikeBtn.classList.remove("actioned");
          }
        },

        error: data => {
          console.log(data);
        },
      });
    }
  }

  disLikes() {
    let customerID = document.querySelector("#customerID").value;

    if (customerID == "") {
      signInFirstBtn.href = "../login/";
      signInFirstBtn.innerHTML = "Sign in";
      modalBody.innerHTML = "<h4>Sign in to make your view count</h4>";
      modal.style.display = "block";
    } else {
      $.ajax({
        url: "../php/fetch_like_dislikes.php",
        method: "POST",
        dataType: "json",
        data: {
          key: "dislike",
          customerID: customerID,
          productID: productID,
        },
        success: data => {
          totalLikes.innerText = data.likes;
          totalDisLikes.innerText = data.dislikes;

          if (data.action == "like") {
            likeBtn.classList.add("actioned");
            disLikeBtn.classList.remove("actioned");
          } else if (data.action == "unlike") {
            likeBtn.classList.remove("actioned");
          } else if (data.action == "dislike") {
            disLikeBtn.classList.add("actioned");
            likeBtn.classList.remove("actioned");
          } else if (data.action == "undislike") {
            disLikeBtn.classList.remove("actioned");
          }
        },
        error: data => {
          console.log(typeof data);
        },
      });
    }
  }
}

window.addEventListener("DOMContentLoaded", () => {
  const likedislikes = new LikeDislikes();

  window.addEventListener("click", e => {
    disLikeBtn = document.querySelector(".fa-heart-broken");
    likeBtn = document.querySelector(".fa-heart");
    totalLikes = document.querySelector(".likes");

    totalDisLikes = document.querySelector(".dislikes");

    if (e.target.matches(".fa-heart")) {
      likedislikes.likes();
    } else if (e.target.matches(".fa-heart-broken")) {
      likedislikes.disLikes();
    }
  });
});
