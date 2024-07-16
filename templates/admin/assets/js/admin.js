$(document).ready(function () {
    var url = 'http://localhost/blog/admin'
    $('#tabela').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.3/i18n/pt-BR.json'
        },
        order: [[0, 'desc']],
        processing: true,
        serverSide: true,
        ajax: 'http://localhost/blog/admin/posts/datatable',
        columns:[
            null,
            null,
            null,
            {
                data: null,
                render: function(data,type,row){
                    var html = '';
                    html += ' <a href=" '+url+'/posts/edit/' +row[0]+  ' " tooltip="tooltip" title="Editar"> <i class="fa-solid fa-chart-simple"></i></a>';
                    return $html;
                }    
            }
        ]
    });
});