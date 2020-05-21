function validateEmail(email) {
  let re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

function checkEmpty(caller) {
  if (caller.value.trim() == "") {
    caller.style = "border:1px solid red";
    return true;
  } else {
    caller.style = "border:none";
    return false;
  }
}

function sendMail() {
  let email = document.getElementById("email");

  if (!checkEmpty(email)) {
    if (validateEmail(email.value.trim())) {
      $(".recover-error").html("");
      $.ajax({
        url: "../php/recover_password.php",
        dataType: "text",
        method: "POST",
        data: {
          email: email.value.trim(),
          key: "recover_password",
        },
        beforeSend: () => {
          $(".recover-error").html('<p style="color:green">Sending email...</p>');
        },
        success: response => {
          if (response == "sent") {
            $(".recover-error").html(
              '<p style="color:green">password sent to your email</p>'
            );
          } else if (response == "failed") {
            $(".recover-error").html(
              '<p style="color:red">Failed to send email</p>'
            );
          } else if (response == "not_found") {
            $(".recover-error").html(
              '<p style="color:red">Email not registered</p>'
            );
          } else {
            $(".recover-error").html(
              '<p style="color:red">Failed to send email</p>'
            );
            console.log(response);
          }
        },
        error: response => {
          console.log(response);
        },
      });
    } else {
      $(".recover-error").html('<p style="color:red">Enter a valid email</p>');
    }
  }
}

$("#recover-form").submit(e => {
  e.preventDefault();
  sendMail();
});
