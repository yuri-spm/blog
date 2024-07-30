$(document).ready(function () {
  $("form").submit(function (event) {
    var validate = false;
    var name = $("#nome");

    if ($('#nome').val().trim() === "") {
        $('#nome').addClass('is-invalid');
        $('#nome').siblings('.invalid-feedback').html('Preencha o campo nome');
        validate = true;
    }else{
        $('#nome').removeClass('is-invalid').addClass('is-valid');
        $('#nome').siblings('.invalid-feedback').html('').removeClass('.invalid-feedback').addClass('.valid-feedback');
      }

    if ($('#email').val().trim() === "") {
        $('#email').addClass('is-invalid');
        $('#email').siblings('.invalid-feedback').html('Preencha o campo email');
        validate = true;
    }else{
        $('#email').removeClass('is-invalid').addClass('is-valid');
        $('#email').siblings('.invalid-feedback').html('').removeClass('.invalid-feedback').addClass('.valid-feedback');
      }
    if (validate) {
      event.preventDefault();
    }
  });
});
