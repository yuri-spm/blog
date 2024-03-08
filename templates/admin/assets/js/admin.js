$(document).ready(function () {
    $("#search").keyup(function () {
        var search = $(this).val();
        if (search.length > 0) {
            $.ajax({
                url: $('form').attr('data-url-search'),
                method: 'POST',
                data: {
                    search: search
                },
                success: function (resultado) {
                    if (resultado) {
                        $('#request').html("<div class='alert alert-dark'>"+resultado+"</ul></div></div>");
                    } else {
                        $('#request').html('<div class="alert alert-warning">Nenhum resultado encontrado!</div>');
                    }
                }
            });
            $('#request').show();
        } else {
            $('#request').hide();
        }
    });
});