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

document.addEventListener('DOMContentLoaded', function () {
    const textareas = document.querySelectorAll('.autoResizeTextarea');

    function autoResize(event) {
        const textarea = event.target;
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }

    textareas.forEach(textarea => {
        textarea.addEventListener('input', autoResize);
        // Ajusta o tamanho inicial ao carregar a página se houver conteúdo pré-carregado
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    });
});