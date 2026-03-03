// ============================================
// POS History - JavaScript
// ============================================

$(document).ready(function(){
    const url_ctrl = site_url + "pos/";
    let table;

    // Initialize DataTable
    table = $('#tabel_history').DataTable({
        "ajax": {
            "url": url_ctrl + 'get_history',
            "data": function(d) {
                d.start_date = $('#start-date').val();
                d.end_date = $('#end-date').val();
            }
        },
        "deferRender": true,
        "order": [["0", "desc"]],
        "pageLength": 25,
        "language": {
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "search": "Cari:",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        },
        "drawCallback": function() {
            updateStatistics();
        }
    });

    // Toggle Date Filter
    $('#filter-btn').on('click', function() {
        $('#date-filter').slideToggle();
    });

    // Apply Filter
    $('#apply-filter').on('click', function() {
        table.ajax.reload();
    });

    // Update Statistics
    function updateStatistics() {
        const tableData = table.rows().data();
        let totalTransactions = 0;
        let totalRevenue = 0;
        let totalItems = 0;

        for(let i = 0; i < tableData.length; i++) {
            const row = tableData[i];
            totalTransactions++;
            
            // Parse revenue (remove "Rp " and ".")
            const revenueStr = row[4].replace('Rp ', '').replace(/\./g, '');
            totalRevenue += parseFloat(revenueStr);
            
            // Parse items
            totalItems += parseInt(row[3]);
        }

        const avgTransaction = totalTransactions > 0 ? totalRevenue / totalTransactions : 0;

        $('#total-transactions').text(totalTransactions);
        $('#total-revenue').text('Rp ' + formatNumber(totalRevenue));
        $('#total-items').text(totalItems);
        $('#avg-transaction').text('Rp ' + formatNumber(Math.round(avgTransaction)));
    }

    // Click row to see detail
    $('#tabel_history tbody').on('click', 'tr', function() {
        const rowId = table.row(this).id();
        if(rowId) {
            showTransactionDetail(rowId);
        }
    });

    // Show Transaction Detail
    function showTransactionDetail(transactionId) {
        $.ajax({
            url: url_ctrl + 'get_transaction_detail/' + transactionId,
            method: 'GET',
            success: function(response) {
                $('#detail-content').html(response);
                $('#detailModal').modal('show');
            },
            error: function() {
                swal('Error', 'Gagal memuat detail transaksi', 'error');
            }
        });
    }

    // Format Number Helper
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Export functions (optional)
    $('#export-excel').on('click', function() {
        // Implement export to Excel
        window.location.href = url_ctrl + 'export_excel?start_date=' + $('#start-date').val() + '&end_date=' + $('#end-date').val();
    });

    $('#export-pdf').on('click', function() {
        // Implement export to PDF
        window.location.href = url_ctrl + 'export_pdf?start_date=' + $('#start-date').val() + '&end_date=' + $('#end-date').val();
    });

});
