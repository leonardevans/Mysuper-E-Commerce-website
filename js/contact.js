const form = document.getElementById("contact-form");
const name = document.getElementById("name");
const email = document.getElementById("email");
const subject = document.getElementById("subject");
const message = document.getElementById("message");
const result = document.querySelector(".result");

form.addEventListener("submit", e => {
  e.preventDefault();
  if (
    !checkEmpty(name) &&
    !checkEmpty(email) &&
    !checkEmpty(subject) &&
    !checkEmpty(message)
  ) {
      if(validateEmail(email.value.trim())){
    $.ajax({
      url: "../php/contact.php",
      method: "POST",
      dataType: "text",
      data: {
        key: "send mail",
        name: name.value,
        email: email.value.trim(),
        subject: subject.value,
        message: message.value,
      },
      beforeSend: () => {
          result.innerHTML = "<span style='color:green;'>Sending message...</span>";
        },
      success: response => {
        if (response == "success") {
          result.innerHTML =
            "<span style='color:green;'>Message sent successfully. Thank You for being with us. </span>";
          name.value = "";
          subject.value = "";
          email.value = "";
          message.value = "";
        } else {
          result.innerHTML =
            "<span style='color:red;'>Failed to send message!</span>";
        }
      },
    });
  }else{
      result.innerHTML =
            "<span style='color:red;'>Please enter a valid email address!</span>";
  }
  }
});

function checkEmpty(caller) {
  if (caller.value.trim() == "") {
    caller.style = "border:1px solid red";
    return true;
  } else {
    caller.style = "border:1px solid black";
    return false;
  }
}

function validateEmail(email) {
  let re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}
