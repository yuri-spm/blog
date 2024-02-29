$(document).ready(function(){
    $("#search").keyup(function(){
        var search = $(this).val();
        if(search !== ""){
            $.ajax({
                url: $('form').attr('data-url-search'),
                method: 'POST',
                data: {
                    search: search
                },
                sucess: function(data){
                    $('#request').html(data);
                }
            });
        }else{
            $('#request').css('display', 'none')
        }
    });
});
