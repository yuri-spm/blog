$(document).ready(function () {
  $("form").submit(function (event) {
    event.preventDefault();

    var loading = $('.ajaxLoading');
    button = $(':input[type="submit"]');

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: new FormData(this),
        dataType: "json",
        contentType: false,
        processData: false,
        beforeSend: function(){
            loading.show().fadeIn(200);
            button.prop('disable', false).addClass('disabled');
        },
        success: function (data) {
            if(data.erro){
               jBoxAlert(data.erro, 'yellow');
            }
            if(data.redirect){
                window.location.href = data.redirect;
            }
        },
        complete: function(){
           loading.hide().fadeOut(200);
           button.removeClass('disabled');
        },
        error:function(jqXHR, textSatus, errorThrown){
            console.log(jqXHR, textSatus, errorThrown);
        }
    });
  });
});

function jBoxAlert(mensagem, cor) {
    new jBox('Notice', {
        content: mensagem,
        color: cor,
        animation: 'pulse',
        showCountdown: true
    });
}