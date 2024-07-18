$(document).ready(function () {
    $.extend($.fn.dataTable.defaults, {
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.3/i18n/pt-BR.json'
        }
    });
    var url = 'http://localhost/blog/';

    $('#entityPost').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.3/i18n/pt-BR.json'
        },
        order: [[0, 'desc']],
        processing: true,
        serverSide: true,
        ajax: {
            url: url + 'admin/posts/datatable',
        },
        columns: [
            null,
            {
                data: null,
                render: function (data, type, row) {
                    if (row[1]) {
                        return '<a data-fancybox data-caption="cover" class="overflow zoom" href="' + url + 'uploads/imagens/' + row[1] + '"><img src="' + url + 'uploads/imagens/thumbs/' + row[1] + '" class="cover-img" /></a>';
                    } else {
                        return '<i class="fa-regular fa-images fs-1 text-secondary"></i>';
                    }
                }
            },
            null,
            null,
            null,
            {
                data: null,
                render: function (data, type, row) {
                    if (row[5] === 1) {
                        return '<i class="fa-solid fa-circle text-success" tooltip="tooltip" title="Ativo"></i>';
                    } else {
                        return '<i class="fa-solid fa-circle text-danger" tooltip="tooltip" title="Inativo"></i>';
                    }
                }
            },
            {
                data: null,
                render: function (data, type, row) {
                    var html = '';
                    html += ' <a href="' + url + 'admin/posts/edit/' + row[0] + '"><i class="fa-solid fa-pen m-1"></i></a> ';
                    html += '<a href="' + url + 'admin/posts/delete/' + row[0] + '"><i class="fa-solid fa-trash m-1"></i></a>';
                    return html;
                }
            }
        ]
    });
});
