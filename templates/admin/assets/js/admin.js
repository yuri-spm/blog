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

window.onload = function(){
    const sidebar = document.querySelector(".sidebar");
    const closeBtn = document.querySelector("#btn");
    const searchBtn = document.querySelector(".bx-search")

    closeBtn.addEventListener("click",function(){
        sidebar.classList.toggle("open")
        menuBtnChange()
    })

    searchBtn.addEventListener("click",function(){
        sidebar.classList.toggle("open")
        menuBtnChange()
    })

    function menuBtnChange(){
        if(sidebar.classList.contains("open")){
            closeBtn.classList.replace("bx-menu","bx-menu-alt-right")
        }else{
            closeBtn.classList.replace("bx-menu-alt-right","bx-menu")
        }
    }
}