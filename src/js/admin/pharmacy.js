var tabel_pharmacy;

$(document).ready(function () {

    // --- Init Datepicker ---
    $('.datepicker').datetimepicker({
        datepicker: true,
        timepicker: false,
        format: 'd/m/Y',
        closeOnDateSelect: true,
        scrollMonth: false,
        scrollInput: false,
    });

    // --- DataTable ---
    tabel_pharmacy = $('#tabel_pharmacy').DataTable({
        processing: true,
        serverSide: false,
        scrollY: "500px",
        deferRender: true,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 2
        },
        ajax: {
            url: site_url + 'pharmacy/table',
            type: 'GET',
            data: function (d) {
                d.date = $('#filter_date').val();
            }
        },
        columns: [
            { data: '0', width: '40px' },
            { data: '1' },
            { data: '2' },
            { data: '3', className: 'text-center' },
            { data: '4', className: 'text-center' },
            { data: '5', className: 'text-center' },
            {
                data: null,
                width: '80px',
                orderable: false,
                className: 'text-center',
                render: function (data, type, row) {
                    return '<button class="ds-act-btn ds-act-view detail-row-btn" data-mrd="' + row.id_medical_record + '" title="Lihat Detail Obat & Racikan">' +
                               '<i class="fa fa-eye"></i>' +
                           '</button>';
                }
            }
        ],
        language: {
            processing: 'Memuat data...',
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ baris',
            info: 'Menampilkan _START_ – _END_ dari _TOTAL_ data',
            infoEmpty: 'Belum ada data',
            zeroRecords: 'Belum ada data',
            emptyTable: 'Silakan pilih tanggal terlebih dahulu',
            paginate: { previous: '&laquo;', next: '&raquo;' },
        },
        order: [[0, 'asc']],
        responsive: true,
        autoWidth: false,
    });

    // --- Helper: show modal ---
    function showDsModal(title, contentHtml, size) {
        $('#MyModalTitle').html(title);
        $('#MyModalContent').html(contentHtml);
        $('#MyModalFooter').html('');
        $('.modal-dialog').addClass('ds-modal');
        if (size) {
            $('.modal-dialog').addClass(size);
        }
        $('#MyModal').modal('show');
    }

    // --- Tombol Tampilkan ---
    $('#search_btn').on('click', function () {
        tabel_pharmacy.ajax.reload();
    });

    // --- Enter pada filter date ---
    $('#filter_date').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            tabel_pharmacy.ajax.reload();
        }
    });

    // --- Detail Obat & Racikan ---
    $('#tabel_pharmacy tbody').on('click', '.detail-row-btn', function () {
        var mrd_id = $(this).data('mrd');
        $.get(site_url + 'pharmacy/detail', { mrd_id: mrd_id }, function (html) {
            showDsModal(
                '<i class="fa fa-pills"></i> Detail Resep & Racikan',
                html,
                'modal-md'
            );
        });
    });

});
