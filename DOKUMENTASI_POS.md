# SISTEM POINT OF SALE (POS) - DOKUMENTASI

## 📦 Instalasi

### 1. Setup Database
Jalankan query SQL berikut di phpMyAdmin:

```sql
-- File: sql_pos_system.sql
```

Query akan membuat 3 tabel:
- `pos_transactions` - Menyimpan data transaksi
- `pos_transaction_details` - Menyimpan detail item per transaksi
- `pos_tables` - Menyimpan data meja (untuk dine in)

### 2. Setup Folder Upload
Pastikan tabel items sudah di-update dengan query:
```sql
-- File: sql_update_items_table.sql
```

Buat folder untuk upload gambar items jika belum ada:
```
c:\xampp\htdocs\kasir\uploads\items\
```
Set permission folder menjadi writable (777).

### 3. Akses Aplikasi
- **POS System**: `http://localhost/kasir/pos`
- **Riwayat Transaksi**: `http://localhost/kasir/pos/history`

---

## 🎯 Fitur Sistem POS

### A. Halaman Utama POS
**URL**: `/pos`

**Fitur**:
1. ✅ Grid menu items dengan gambar
2. ✅ Search/pencarian item
3. ✅ Keranjang belanja interaktif
4. ✅ Tambah/kurangi quantity
5. ✅ Hapus item dari keranjang
6. ✅ Pilih tipe order (Dine In / Takeaway)
7. ✅ Nomor invoice otomatis
8. ✅ Jam real-time

### B. Proses Pembayaran
**Fitur**:
1. ✅ 3 Metode pembayaran:
   - 💵 **CASH** - Tunai dengan kalkulasi kembalian otomatis
   - 📱 **QRIS** - Pembayaran digital QRIS
   - 🏦 **TRANSFER** - Transfer bank

2. ✅ Quick amount buttons (10K, 20K, 50K, 100K, Uang Pas)
3. ✅ Kalkulasi kembalian otomatis
4. ✅ Catatan pesanan (opsional)
5. ✅ Validasi pembayaran

### C. Struk Pembayaran
**URL**: `/pos/print_receipt/{transaction_id}`

**Fitur**:
1. ✅ Format struk thermal 80mm
2. ✅ Detail lengkap transaksi
3. ✅ Informasi kasir dan waktu
4. ✅ Auto print (opsional)
5. ✅ Print friendly design

### D. Riwayat Transaksi
**URL**: `/pos/history`

**Fitur**:
1. ✅ DataTables dengan pagination
2. ✅ Filter berdasarkan tanggal
3. ✅ Statistik dashboard:
   - Total transaksi
   - Total pendapatan
   - Total item terjual
   - Rata-rata transaksi
4. ✅ View detail transaksi
5. ✅ Export data (Excel/PDF)* *opsional

---

## 🎨 User Interface

### Desain Modern & Profesional
- **Color Scheme**: Purple gradient, Green accent
- **Layout**: Responsive grid system
- **Typography**: Clean & readable
- **Icons**: FontAwesome
- **Animations**: Smooth transitions
- **UX**: User-friendly & intuitive

### Responsive Design
- Desktop: 2-panel layout (items | cart)
- Tablet: Optimized layout
- Mobile: Single column stack

---

## 📊 Struktur Data

### Tabel: pos_transactions
```
id_transaction    - ID unik transaksi
no_invoice        - Nomor invoice (INV-YYYYMMDD-XXXX)
tanggal          - Waktu transaksi
total_item       - Jumlah item
subtotal         - Subtotal sebelum diskon/pajak
diskon           - Nilai diskon
pajak            - Nilai pajak
total_bayar      - Total yang harus dibayar
jumlah_bayar     - Jumlah uang yang dibayarkan
kembalian        - Kembalian
jenis_pembayaran - CASH/QRIS/TRANSFER
tipe_order       - DINE_IN/TAKEAWAY
kasir_id         - ID kasir
kasir_name       - Nama kasir
status           - PENDING/COMPLETED/CANCELLED
catatan          - Catatan transaksi
created_at       - Waktu dibuat
```

### Tabel: pos_transaction_details
```
id_detail        - ID detail
id_transaction   - FK ke pos_transactions
id_item          - FK ke items
kodeitem         - Kode item
namaitem         - Nama item
harga_satuan     - Harga per unit
quantity         - Jumlah
diskon_item      - Diskon per item (opsional)
subtotal         - Subtotal item
catatan_item     - Catatan item (opsional)
```

---

## 🔧 Konfigurasi

### Controller: Pos.php
Lokasi: `app/controllers/Pos.php`

Fungsi utama:
- `index()` - Halaman POS
- `get_items()` - Load semua items
- `generate_invoice()` - Generate nomor invoice
- `save_transaction()` - Simpan transaksi
- `print_receipt()` - Cetak struk
- `history()` - Halaman riwayat
- `get_history()` - Data untuk DataTables

### Model: M_pos.php
Lokasi: `app/models/M_pos.php`

Fungsi utama:
- `generate_invoice_number()` - Generate invoice
- `insert_transaction()` - Insert transaksi
- `insert_transaction_detail()` - Insert detail
- `get_transaction_by_id()` - Get transaksi
- `get_transaction_details()` - Get detail items
- `get_all_transactions()` - Get semua transaksi
- `get_daily_report()` - Laporan harian
- `get_top_selling_items()` - Item terlaris

### Views
Lokasi: `app/views/pos/`
- `main.php` - Halaman utama POS
- `receipt.php` - Struk pembayaran
- `history.php` - Riwayat transaksi

### Assets
- CSS: `src/css/pos.css`
- JS: `src/js/pos.js`
- JS History: `src/js/pos_history.js`

---

## 💡 Tips Penggunaan

1. **Tambah Item ke Keranjang**: Klik card item
2. **Ubah Quantity**: Gunakan tombol +/- di keranjang
3. **Hapus Item**: Klik icon trash di keranjang
4. **Kosongkan Keranjang**: Klik tombol "Clear"
5. **Pembayaran**: Klik tombol "Pembayaran"
6. **Quick Amount**: Gunakan tombol cepat (10K, 20K, dst)
7. **Uang Pas**: Klik tombol "Uang Pas"
8. **Print Struk**: Klik tombol print setelah transaksi selesai

---

## 🚀 Pengembangan Lanjutan

### Fitur yang Bisa Ditambahkan:
1. ✨ Diskon per item atau total
2. ✨ Pajak/PPN otomatis
3. ✨ Sistem membership/loyalty
4. ✨ Integrasi dengan printer thermal
5. ✨ Laporan penjualan lengkap
6. ✨ Dashboard analytics
7. ✨ Manajemen meja untuk dine in
8. ✨ Split bill
9. ✨ Void/cancel transaksi
10. ✨ Shift kasir

---

## 📞 Support

Jika ada pertanyaan atau masalah:
1. Periksa error log di browser console (F12)
2. Periksa PHP error di `app/logs/`
3. Pastikan semua tabel database sudah dibuat
4. Pastikan folder uploads sudah ada dan writable

---

## ✅ Checklist Instalasi

- [ ] Jalankan SQL query untuk membuat tabel POS
- [ ] Jalankan SQL query untuk update tabel items
- [ ] Buat folder `uploads/items/` dan set permission
- [ ] Akses halaman POS di browser
- [ ] Test tambah item ke keranjang
- [ ] Test proses pembayaran
- [ ] Test cetak struk
- [ ] Test halaman history

---

**Sistem POS siap digunakan! Happy Selling! 🛒💰**
