$(document).ready(function () {
    $('#tabela').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.3/i18n/pt-BR.json'
        },
        processing: true,
        serverSide: true,
        ajax: 'http://localhost/blog/admin/posts/datatable'
    });
});