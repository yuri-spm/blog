$(document).ready(function () {
    $('.entity').DataTable({
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50, 75, 100],
        language: {
            url: '//cdn.datatables.net/plug-ins/2.0.8/i18n/pt-BR.json',
        }
    });
});