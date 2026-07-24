var tabel_skbs;

$(document).ready(function () {

    // --- DataTable ---
    tabel_skbs = $('#tabel_skbs').DataTable({
        processing: true,
        serverSide: false,
        scrollY: "500px",
        deferRender: true,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 2,
            rightColumns: 1
        },
        ajax: {
            url: site_url + 'skbs/table',
            type: 'GET',
        },
        columns: [
            { data: '0', width: '40px' },
            { data: '1'},
            { data: '2', className: 'text-center' },
            { data: '3'},
            { data: '4'},
            { data: '5', className: 'text-center' },
            { data: '6'},
            { data: '7', className: 'text-center' },
            {
                data: null,
                width: '100px',
                orderable: false,
                className: 'text-center',
                render: function (data, type, row) {
                    return '<div class="ds-act-group">' +
                               '<button class="ds-act-btn ds-act-view view-row-btn" data-id="' + row.DT_RowId + '" title="Lihat Detail">' +
                                   '<i class="fa fa-eye"></i>' +
                               '</button>' +
                               '<button class="ds-act-btn ds-act-print print-row-btn" data-id="' + row.DT_RowId + '" title="Cetak SKBS">' +
                                   '<i class="fa fa-print"></i>' +
                               '</button>' +
                           '</div>';
                }
            },
        ],
        language: {
            processing: 'Memuat data...',
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ baris',
            info: 'Menampilkan _START_ – _END_ dari _TOTAL_ data',
            infoEmpty: 'Belum ada data',
            zeroRecords: 'Belum ada data',
            emptyTable: 'Belum ada data SKBS',
            paginate: { previous: '&laquo;', next: '&raquo;' },
        },
        order: [[0, 'asc']],
        responsive: true,
        autoWidth: false,
    });

    // --- Helper: show modal with ds-modal class ---
    function showDsModal(title, contentHtml, footerHtml, size) {
        $('#MyModalTitle').html(title);
        $('#MyModalContent').html(contentHtml);
        $('#MyModalFooter').html(footerHtml);
        $('.modal-dialog').addClass('ds-modal');
        if (size) {
            $('.modal-dialog').addClass(size);
        }
        $('#MyModal').modal('show');
    }

    // --- Lihat Detail SKBS ---
    $('#tabel_skbs tbody').on('click', '.view-row-btn', function () {
        var id = $(this).data('id');
        $.get(site_url + 'skbs/detail', { id: id }, function (html) {
            showDsModal(
                '<i class="fa fa-file-text-o"></i> Detail SKBS',
                html,
                '',
                'modal-lg'
            );
        });
    });

    // --- Cetak SKBS ---
    $('#tabel_skbs tbody').on('click', '.print-row-btn', function () {
        var id = $(this).data('id');
        var url = site_url + 'skbs/cetak/' + id;
        window.open(url, '_blank');
    });

});
