const loginForm = document.querySelector(".login-form");
const email = document.querySelector("#email");
const password = document.querySelector("#password");
const errors = document.querySelector(".form-error");

class Login {
  checkEmpty(caller) {
    if (caller.value.trim() == "") {
      caller.style = "border:1px solid red";
      return true;
    } else {
      caller.style = "border:none";
      return false;
    }
  }

  validateEmail(email) {
    let re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }

  submitForm() {
    loginForm.addEventListener("submit", e => {
      e.preventDefault();
      if (!this.checkEmpty(email) && !this.checkEmpty(password)) {
        if (this.validateEmail(email.value.trim())) {
          errors.style.display = "none";
          $.ajax({
            url: "../php/login_signup.php",
            method: "POST",
            dataType: "text",
            data: {
              key: "login",
              email: email.value.trim(),
              password: password.value.trim(),
            },
            beforeSend: () => {
                errors.innerHTML= '<p style="color:green">Signing in...</p>';
                },
            success: data => {
              if (data == "no_such_user") {
                errors.innerHTML = "wrong email or password";
                errors.style.display = "block";
              } else if (data == "logged_in") {
                window.location.href = "../";
              } else {
                console.log(data);
              }
            },
          });
        } else {
          errors.innerHTML = "Invalid Email address";
          errors.style.display = "block";
        }
      }
    });
  }
}

window.addEventListener("DOMContentLoaded", () => {
  const login = new Login();
  login.submitForm();
});
