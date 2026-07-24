var tabel_reportobat;

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

    // --- Load obat list untuk autocomplete ---
    $.get(site_url + 'reportobat/obat_list', function (data) {
        var select = $('#filter_obat');
        $.each(data, function (i, name) {
            select.append('<option value="' + $('<span>').text(name).html() + '">' + $('<span>').text(name).html() + '</option>');
        });
        select.trigger('chosen:updated');
    }, 'json');

    $(".autocomplete").chosen();

    // --- DataTable (mulai kosong) ---
    tabel_reportobat = $('#tabel_reportobat').DataTable({
        processing: true,
        serverSide: false,
        scrollY: "500px",
        deferRender: true,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 2,
            rightColumns: 0
        },
        data: [],
        columns: [
            { data: '0', width: '40px' },
            { data: '1' },
            { data: '2', className: 'text-center' },
            { data: '3', className: 'text-center' },
            { data: '4' },
            { data: '5', className: 'text-right' },
            { data: '6', className: 'text-right' },
            { data: '7' },
            { data: '8', className: 'text-center' },
            { data: '9', className: 'text-center' },
        ],
        language: {
            processing: 'Memuat data...',
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ baris',
            info: 'Menampilkan _START_ – _END_ dari _TOTAL_ data',
            infoEmpty: 'Belum ada data',
            zeroRecords: 'Tidak ada data',
            emptyTable: 'Silakan pilih rentang tanggal dan klik Tampilkan',
            paginate: { previous: '&laquo;', next: '&raquo;' },
        },
        order: [[0, 'asc']],
        responsive: true,
        autoWidth: false,
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            var total = api.column(6).data().reduce(function (a, b) {
                var val = b.replace(/[^0-9]/g, '');
                return a + (parseInt(val) || 0);
            }, 0);
            $(api.column(6).footer()).html('Rp ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
        },
    });

    // --- Fungsi load data ---
    function loadData() {
        var date_from = $('#filter_date_from').val().trim();
        var date_to   = $('#filter_date_to').val().trim();
        var obat_name = $('#filter_obat').val();

        if (date_from == '' || date_to == '') {
            notifNo('Silakan pilih rentang tanggal terlebih dahulu');
            return;
        }

        var url = site_url + 'reportobat/table?date_from=' + encodeURIComponent(date_from) +
                  '&date_to=' + encodeURIComponent(date_to);
        if (obat_name) {
            url += '&obat_name=' + encodeURIComponent(obat_name);
        }

        tabel_reportobat.ajax.url(url).load();
    }

    // --- Tombol Tampilkan ---
    $('#search_btn').on('click', function () {
        loadData();
    });

    // --- Enter pada field filter ---
    $('#filter_date_from, #filter_date_to').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            loadData();
        }
    });

    // --- Download Excel ---
    $('#download_excel_btn').on('click', function () {
        var date_from = $('#filter_date_from').val().trim();
        var date_to   = $('#filter_date_to').val().trim();
        var obat_name = $('#filter_obat').val();

        if (date_from == '' || date_to == '') {
            notifNo('Silakan pilih rentang tanggal terlebih dahulu');
            return;
        }

        var url = site_url + 'reportobat/download_excel?date_from=' + encodeURIComponent(date_from) +
                  '&date_to=' + encodeURIComponent(date_to);
        if (obat_name) {
            url += '&obat_name=' + encodeURIComponent(obat_name);
        }

        window.open(url, '_blank');
    });

});
