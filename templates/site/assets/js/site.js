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
                success: function (result) {
                    if (result) {
                        $('#request').html("<div class='card'><div class='card-body'><ul class='list-group list-group-flush'>"+result+"</ul></div></div>");
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
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    });
});