<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Pembayaran - <?=$transaction->no_invoice?></title>
    <style>
        @media print {
            @page {
                margin: 0;
                size: 80mm auto;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
        }

        .receipt-header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .store-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .store-info {
            font-size: 11px;
            line-height: 1.4;
        }

        .receipt-info {
            margin-bottom: 10px;
            font-size: 11px;
        }

        .receipt-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .items-table {
            width: 100%;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 5px 0;
            margin: 10px 0;
        }

        .item-row {
            margin-bottom: 5px;
        }

        .item-name {
            font-weight: bold;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-left: 5px;
        }

        .summary {
            margin-top: 10px;
            padding-top: 5px;
            border-top: 1px dashed #000;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .summary-row.total {
            font-size: 14px;
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }

        .payment-info {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #000;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 2px dashed #000;
            font-size: 11px;
        }

        .print-button {
            text-align: center;
            margin: 20px 0;
        }

        .print-button button {
            padding: 10px 30px;
            font-size: 14px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .print-button button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="print-button no-print">
        <button onclick="window.print()">🖨️ Print Struk</button>
        <button onclick="window.close()" style="background:#666; margin-left:10px;">❌ Tutup</button>
    </div>

    <div class="receipt-header">
        <div class="store-name">NAMA TOKO ANDA</div>
        <div class="store-info">
            Jl. Contoh Alamat No. 123<br>
            Telp: 0812-3456-7890<br>
            Website: www.tokanda.com
        </div>
    </div>

    <div class="receipt-info">
        <div class="receipt-info-row">
            <span>Invoice:</span>
            <strong><?=$transaction->no_invoice?></strong>
        </div>
        <div class="receipt-info-row">
            <span>Tanggal:</span>
            <span><?=date('d/m/Y H:i', strtotime($transaction->tanggal))?></span>
        </div>
        <div class="receipt-info-row">
            <span>Kasir:</span>
            <span><?=$transaction->kasir_name?></span>
        </div>
        <div class="receipt-info-row">
            <span>Tipe:</span>
            <span><?=$transaction->tipe_order == 'DINE_IN' ? 'Makan di Tempat' : 'Takeaway'?></span>
        </div>
    </div>

    <div class="items-table">
        <?php foreach($details as $item): ?>
        <div class="item-row">
            <div class="item-name"><?=$item->namaitem?></div>
            <div class="item-detail">
                <span><?=$item->quantity?> x Rp <?=number_format($item->harga_satuan, 0, ',', '.')?></span>
                <span>Rp <?=number_format($item->subtotal, 0, ',', '.')?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="summary">
        <div class="summary-row">
            <span>Subtotal:</span>
            <span>Rp <?=number_format($transaction->subtotal, 0, ',', '.')?></span>
        </div>
        <?php if($transaction->diskon > 0): ?>
        <div class="summary-row">
            <span>Diskon:</span>
            <span>Rp <?=number_format($transaction->diskon, 0, ',', '.')?></span>
        </div>
        <?php endif; ?>
        <?php if($transaction->pajak > 0): ?>
        <div class="summary-row">
            <span>Pajak:</span>
            <span>Rp <?=number_format($transaction->pajak, 0, ',', '.')?></span>
        </div>
        <?php endif; ?>
        <div class="summary-row total">
            <span>TOTAL:</span>
            <span>Rp <?=number_format($transaction->total_bayar, 0, ',', '.')?></span>
        </div>
    </div>

    <div class="payment-info">
        <div class="summary-row">
            <span>Metode Bayar:</span>
            <strong><?=$transaction->jenis_pembayaran?></strong>
        </div>
        <?php if($transaction->jenis_pembayaran == 'CASH'): ?>
        <div class="summary-row">
            <span>Jumlah Bayar:</span>
            <span>Rp <?=number_format($transaction->jumlah_bayar, 0, ',', '.')?></span>
        </div>
        <div class="summary-row">
            <span>Kembalian:</span>
            <span>Rp <?=number_format($transaction->kembalian, 0, ',', '.')?></span>
        </div>
        <?php endif; ?>
    </div>

    <?php if(!empty($transaction->catatan)): ?>
    <div style="margin-top: 10px; padding: 5px; background: #f5f5f5; font-size: 11px;">
        <strong>Catatan:</strong><br>
        <?=$transaction->catatan?>
    </div>
    <?php endif; ?>

    <div class="footer">
        ═══════════════════════<br>
        TERIMA KASIH<br>
        ATAS KUNJUNGAN ANDA<br>
        ═══════════════════════
    </div>

    <script>
        // Auto print saat halaman dibuka (opsional)
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>
</html>
