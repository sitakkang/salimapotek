var tabel_monthlyobat;

$(document).ready(function () {

    // --- DataTable ---
    tabel_monthlyobat = $('#tabel_monthlyobat').DataTable({
        processing: true,
        serverSide: false,
        scrollY: "500px",
        deferRender: true,
        scrollX: true,
        scrollCollapse: true,
        data: [],
        columns: [
            { data: '0', width: '40px' },
            { data: '1' },
            { data: '2' },
            { data: '3', className: 'text-center' },
            { data: '4', className: 'text-center' },
            {
                data: '5',
                className: 'text-right',
                render: function (data) {
                    return data;
                }
            },
        ],
        language: {
            processing: 'Memuat data...',
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ baris',
            info: 'Menampilkan _START_ – _END_ dari _TOTAL_ data',
            infoEmpty: 'Belum ada data',
            zeroRecords: 'Tidak ada data',
            emptyTable: 'Silakan pilih periode dan klik Tampilkan',
            paginate: { previous: '&laquo;', next: '&raquo;' },
        },
        order: [[0, 'asc']],
        responsive: true,
        autoWidth: false,
        drawCallback: function () {
            var api = this.api();
            var total = api.column(5).data().reduce(function (a, b) {
                var val = b.replace(/[^0-9]/g, '');
                return a + (parseInt(val) || 0);
            }, 0);
            $('#grand_total').text('Rp ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
        },
    });

    // --- Helper ---
    function getParams() {
        return {
            year:  $('#filter_year').val(),
            month: $('#filter_month').val(),
            mode:  $('#filter_mode').val(),
        };
    }

    // --- Load data ---
    function loadData() {
        var p = getParams();
        var url = site_url + 'monthlyobat/table?year=' + p.year + '&month=' + p.month + '&mode=' + p.mode;
        tabel_monthlyobat.ajax.url(url).load();
    }

    // --- Tombol Tampilkan ---
    $('#search_btn').on('click', function () {
        loadData();
    });

    // --- Download Excel ---
    $('#download_excel_btn').on('click', function () {
        var p = getParams();
        var url = site_url + 'monthlyobat/download_excel?year=' + p.year + '&month=' + p.month + '&mode=' + p.mode;
        window.open(url, '_blank');
    });

    // --- Auto load ---
    loadData();

});
