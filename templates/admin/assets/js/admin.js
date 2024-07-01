document.addEventListener("DOMContentLoaded", function () {
  const textareas = document.querySelectorAll(".autoResizeTextarea");

  function autoResize(event) {
    const textarea = event.target;
    textarea.style.height = "auto";
    textarea.style.height = textarea.scrollHeight + "px";
  }

  textareas.forEach((textarea) => {
    textarea.addEventListener("input", autoResize);
    textarea.style.height = "auto";
    textarea.style.height = textarea.scrollHeight + "px";
  });
});

const flashMessageTimeout = 5000;
const flashMessage = document.querySelector(".alert-dismissible");

if (flashMessage) {
  setTimeout(() => {
    flashMessage.classList.remove("show");
    flashMessage.classList.add("fade");

    setTimeout(() => {
      flashMessage.remove();
    }, 150);
  }, flashMessageTimeout);
}

$(document).ready(function () {
  $("#summernote").summernote();
});
