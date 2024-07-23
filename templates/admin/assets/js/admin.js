$(document).ready(function () {
    var url = $('table').attr('url');
    $.extend($.fn.dataTable.defaults, {
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.3/i18n/pt-BR.json',
        },
    });

    $('#entityPost').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        order: [[0, 'desc']],
        processing: true,
        serverSide: true,
        ajax: {
            url: url + 'admin/posts/datatable',
            type: 'POST',
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
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
        ],
         columnDefs: [
            {
                className: 'dt-body-left',
                targets: [2]
            },
            {
                className: 'dt-center',
                targets: [0, 1, 3, 4, 5, 6]
            },
            {
                orderable: false,
                targets: [1, -1]
            }

        ]
    });

    $('#entityCategories').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        
        paging: false,
        columnDefs: [
            {
                targets: [-1, -2],
                orderable: false
            }
        ],
        order: [[1, 'asc']]
    });

    $('#entityUsers').DataTable({
        paging: false,
        columnDefs: [
            {
                targets: [-1, -2],
                orderable: false
            }
        ],
        order: [[1, 'asc']]
    });

});
