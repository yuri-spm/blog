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
                render: function (data, type, row) {
                    var html = '';

                    html += ' <a href=" ' + url + '/posts/editar/' + row[0] + ' " tooltip="tooltip" title="Editar"><i class="fa-solid fa-pen m-1"></i></a> ';

                    html += '<a href=" ' + url + '/posts/deletar/' + row[0] + ' "><i class="fa-solid fa-trash m-1"></i></a>';

                    return html;
                }
            }
        ]
    });
});