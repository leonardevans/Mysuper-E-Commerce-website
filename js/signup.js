const signUpForm = document.querySelector(".sign-up-form");
const username = document.querySelector("#username");
const email = document.querySelector("#email");
const password = document.querySelector("#password");
const confirmPassword = document.querySelector("#confirm-password");
const errors = document.querySelector(".form-error");

class SignUp {
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

  validatePassword(password, confirmPassword) {
    if (password.length >= 8) {
      errors.style.display = "none";
      let upperCases = 0;
      let numbers = 0;
      for (let i = 0; i < password.length; i++) {
        if (password.charAt(i).toUpperCase() === password.charAt(i)) {
          upperCases += 1;
        }
        if (typeof (password.charAt(i) == "number")) {
          numbers += 1;
        }
      }
      if (upperCases >= 1 && numbers >= 1) {
        errors.style.display = "none";
        if (password == confirmPassword) {
          errors.style.display = "none";
          return true;
        } else {
          errors.innerHTML = "passwords does not match";
          errors.style.display = "block";
          return false;
        }
      } else {
        errors.innerHTML =
          "Password should have atleast 1 number and 1 capital letter";
        errors.style.display = "block";
        return false;
      }
    } else {
      errors.innerHTML = "Password too short";
      errors.style.display = "block";
      return false;
    }
  }

  submitForm() {
    signUpForm.addEventListener("submit", e => {
      e.preventDefault();
      if (
        !this.checkEmpty(username) &&
        !this.checkEmpty(email) &&
        !this.checkEmpty(password) &&
        !this.checkEmpty(confirmPassword)
      ) {
        if (username.value.trim().length >= 8) {
          errors.style.display = "none";
          if (this.validateEmail(email.value.trim())) {
            errors.style.display = "none";
            if (
              this.validatePassword(
                password.value.trim(),
                confirmPassword.value.trim()
              )
            ) {
              $.ajax({
                url: "../php/login_signup.php",
                method: "POST",
                dataType: "text",
                data: {
                  key: "signup",
                  username: username.value.trim(),
                  email: email.value.trim(),
                  password: password.value.trim(),
                },
                beforeSend: () => {
                errors.innerHTML= '<p style="color:green">Signing up...</p>';
                },
                success: data => {
                  if (data == "username_exist") {
                    errors.innerHTML = "Username is taken";
                    errors.style.display = "block";
                  } else if (data == "email_exist") {
                    errors.innerHTML = "Email is taken";
                    errors.style.display = "block";
                  } else if (data == "created") {
                    window.location.href = "../login/";
                  } else {
                    errors.innerHTML = data;
                    errors.style.display = "block";
                  }
                },
              });
            }
          } else {
            errors.innerHTML = "Invalid Email address";
            errors.style.display = "block";
          }
        } else {
          errors.innerHTML = "Username too short";
          errors.style.display = "block";
        }
      }
    });
  }
}

window.addEventListener("DOMContentLoaded", () => {
  const signup = new SignUp();
  signup.submitForm();
});
