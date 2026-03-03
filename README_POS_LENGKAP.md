# 🛒 SISTEM POINT OF SALE (POS) - PANDUAN LENGKAP

## 📋 DAFTAR ISI
1. [Instalasi](#instalasi)
2. [Fitur Utama](#fitur-utama)
3. [Cara Penggunaan](#cara-penggunaan)
4. [Struktur File](#struktur-file)
5. [Troubleshooting](#troubleshooting)

---

## 🚀 INSTALASI

### Langkah 1: Setup Database Items
Pastikan tabel `items` sudah memiliki kolom yang diperlukan. Jalankan query ini:

```sql
-- File: sql_update_items_table.sql
ALTER TABLE `items` 
ADD COLUMN `kodeitem` VARCHAR(10) NULL AFTER `id`,
ADD COLUMN `namaitem` VARCHAR(50) NULL AFTER `kodeitem`,
ADD COLUMN `hargasatuan` DECIMAL(15,2) DEFAULT 0 AFTER `namaitem`,
ADD COLUMN `deskripsi` VARCHAR(225) NULL AFTER `hargasatuan`,
ADD COLUMN `itemimage` VARCHAR(255) NULL AFTER `deskripsi`,
ADD COLUMN `createby` INT(11) NULL AFTER `itemimage`,
ADD COLUMN `updateby` INT(11) NULL AFTER `createby`,
ADD COLUMN `createdt` DATETIME NULL AFTER `updateby`,
ADD COLUMN `updatedt` DATETIME NULL AFTER `createdt`;

-- Update data lama
UPDATE `items` SET 
  `kodeitem` = `item_number`,
  `namaitem` = `item_name`,
  `createdt` = NOW()
WHERE `kodeitem` IS NULL;
```

### Langkah 2: Setup Database POS
Jalankan query untuk membuat tabel POS:

```sql
-- File: sql_pos_system.sql

-- Tabel Transaksi
CREATE TABLE `pos_transactions` (
  `id_transaction` INT(11) NOT NULL AUTO_INCREMENT,
  `no_invoice` VARCHAR(50) NOT NULL,
  `tanggal` DATETIME NOT NULL,
  `total_item` INT(11) DEFAULT 0,
  `subtotal` DECIMAL(15,2) DEFAULT 0,
  `diskon` DECIMAL(15,2) DEFAULT 0,
  `pajak` DECIMAL(15,2) DEFAULT 0,
  `total_bayar` DECIMAL(15,2) DEFAULT 0,
  `jumlah_bayar` DECIMAL(15,2) DEFAULT 0,
  `kembalian` DECIMAL(15,2) DEFAULT 0,
  `jenis_pembayaran` ENUM('CASH', 'QRIS', 'TRANSFER') NOT NULL DEFAULT 'CASH',
  `tipe_order` ENUM('DINE_IN', 'TAKEAWAY') NOT NULL DEFAULT 'DINE_IN',
  `kasir_id` INT(11) NULL,
  `kasir_name` VARCHAR(60) NULL,
  `status` ENUM('PENDING', 'COMPLETED', 'CANCELLED') DEFAULT 'PENDING',
  `catatan` TEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id_transaction`),
  UNIQUE KEY `no_invoice` (`no_invoice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Detail Transaksi
CREATE TABLE `pos_transaction_details` (
  `id_detail` INT(11) NOT NULL AUTO_INCREMENT,
  `id_transaction` INT(11) NOT NULL,
  `id_item` INT(11) NOT NULL,
  `kodeitem` VARCHAR(10) NOT NULL,
  `namaitem` VARCHAR(50) NOT NULL,
  `harga_satuan` DECIMAL(15,2) NOT NULL,
  `quantity` INT(11) NOT NULL DEFAULT 1,
  `diskon_item` DECIMAL(15,2) DEFAULT 0,
  `subtotal` DECIMAL(15,2) NOT NULL,
  `catatan_item` VARCHAR(255) NULL,
  PRIMARY KEY (`id_detail`),
  KEY `idx_transaction` (`id_transaction`),
  CONSTRAINT `fk_transaction` FOREIGN KEY (`id_transaction`) 
    REFERENCES `pos_transactions` (`id_transaction`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Meja (Opsional)
CREATE TABLE `pos_tables` (
  `id_table` INT(11) NOT NULL AUTO_INCREMENT,
  `no_meja` VARCHAR(10) NOT NULL,
  `kapasitas` INT(11) DEFAULT 4,
  `status` ENUM('AVAILABLE', 'OCCUPIED', 'RESERVED') DEFAULT 'AVAILABLE',
  `created_at` DATETIME NULL,
  PRIMARY KEY (`id_table`),
  UNIQUE KEY `no_meja` (`no_meja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample meja
INSERT INTO `pos_tables` (`no_meja`, `kapasitas`, `status`) VALUES
('M01', 2, 'AVAILABLE'),
('M02', 4, 'AVAILABLE'),
('M03', 4, 'AVAILABLE'),
('M04', 6, 'AVAILABLE'),
('M05', 2, 'AVAILABLE'),
('M06', 4, 'AVAILABLE'),
('M07', 6, 'AVAILABLE'),
('M08', 8, 'AVAILABLE');
```

### Langkah 3: Setup Folder Upload
Buat folder untuk upload gambar items:

**Windows:**
```
C:\xampp\htdocs\kasir\uploads\items\
```

**Linux/Mac:**
```bash
mkdir -p /var/www/html/kasir/uploads/items
chmod 777 /var/www/html/kasir/uploads/items
```

### Langkah 4: Verifikasi File
Pastikan semua file sudah ada:

**Controllers:**
- ✅ `app/controllers/Pos.php`
- ✅ `app/controllers/admin/Items.php`

**Models:**
- ✅ `app/models/M_pos.php`
- ✅ `app/models/admin/M_items.php`

**Views:**
- ✅ `app/views/pos/main.php`
- ✅ `app/views/pos/receipt.php`
- ✅ `app/views/pos/history.php`
- ✅ `app/views/admin/items/view.php`
- ✅ `app/views/admin/items/add.php`
- ✅ `app/views/admin/items/edit.php`

**Assets:**
- ✅ `src/css/pos.css`
- ✅ `src/js/pos.js`
- ✅ `src/js/pos_history.js`
- ✅ `src/js/admin/items.js`

---

## 🎯 FITUR UTAMA

### 1. 📦 Master Items
**URL:** `http://localhost/kasir/admin/items`

Kelola semua produk yang dijual:
- ✅ Tambah item baru
- ✅ Edit item
- ✅ Hapus item
- ✅ Upload gambar item
- ✅ Set harga satuan
- ✅ Deskripsi item
- ✅ DataTables dengan search & pagination

### 2. 🛒 Point of Sale
**URL:** `http://localhost/kasir/pos`

Interface POS yang modern dan user-friendly:
- ✅ Grid menu items dengan gambar
- ✅ Search items real-time
- ✅ Keranjang belanja interaktif
- ✅ Tambah/kurangi quantity dengan mudah
- ✅ Auto-generate nomor invoice
- ✅ Real-time clock display
- ✅ Pilih tipe order (Dine In / Takeaway)

### 3. 💳 Pembayaran
Modal pembayaran yang lengkap:
- ✅ **3 Metode Pembayaran:**
  - 💵 CASH (Tunai)
  - 📱 QRIS
  - 🏦 TRANSFER
- ✅ Quick amount buttons
- ✅ Kalkulasi kembalian otomatis
- ✅ Validasi pembayaran
- ✅ Catatan pesanan

### 4. 🧾 Struk Pembayaran
**URL:** `http://localhost/kasir/pos/print_receipt/{id}`

- ✅ Format thermal 80mm
- ✅ Print-friendly design
- ✅ Detail lengkap transaksi
- ✅ Informasi pembayaran
- ✅ Auto-print support

### 5. 📊 Riwayat Transaksi
**URL:** `http://localhost/kasir/pos/history`

- ✅ DataTables dengan pagination
- ✅ Filter berdasarkan tanggal
- ✅ Dashboard statistik:
  - Total transaksi
  - Total pendapatan
  - Total item terjual
  - Rata-rata transaksi
- ✅ View detail transaksi
- ✅ Re-print struk

---

## 📖 CARA PENGGUNAAN

### A. Menambah Item Produk
1. Login ke sistem
2. Buka menu **Admin → Items**
3. Klik tombol **"Tambah Item"**
4. Isi form:
   - Kode Item (max 10 karakter)
   - Nama Item (max 50 karakter)
   - Harga Satuan (dalam Rupiah)
   - Deskripsi (opsional, max 225 karakter)
   - Upload Gambar (opsional, max 2MB)
5. Klik **"Simpan"**

### B. Transaksi di Kasir (POS)
1. Buka **POS System** (`/pos`)
2. **Pilih Item:**
   - Klik card item untuk menambahkan ke keranjang
   - Atau gunakan search box untuk mencari item
3. **Kelola Keranjang:**
   - Klik tombol **+** untuk tambah quantity
   - Klik tombol **-** untuk kurangi quantity
   - Klik icon **trash** untuk hapus item
   - Klik tombol **"Clear"** untuk kosongkan semua
4. **Pilih Tipe Order:**
   - 🍽️ **Dine In** (Makan di tempat)
   - 🥡 **Takeaway** (Bawa pulang)
5. **Proses Pembayaran:**
   - Klik tombol **"Pembayaran"**
   - Pilih metode pembayaran
   - **Jika CASH:**
     - Masukkan jumlah bayar
     - Atau gunakan quick buttons (10K, 20K, 50K, 100K)
     - Atau klik "Uang Pas"
     - Sistem akan hitung kembalian otomatis
   - **Jika QRIS/TRANSFER:**
     - Pastikan pembayaran sudah diterima
   - Tambahkan catatan jika perlu
6. **Selesaikan:**
   - Klik **"Selesaikan Pembayaran"**
   - Modal sukses akan muncul
   - Klik **"Cetak Struk"** untuk print
   - Klik **"Transaksi Baru"** untuk mulai lagi

### C. Melihat Riwayat
1. Buka **Riwayat Transaksi** (`/pos/history`)
2. Lihat statistik di dashboard
3. **Filter Transaksi:**
   - Klik **"Filter Tanggal"**
   - Pilih tanggal mulai dan akhir
   - Klik **"Cari"**
4. **Detail Transaksi:**
   - Klik baris transaksi untuk melihat detail
   - Modal detail akan muncul
   - Klik **"Cetak Struk"** untuk re-print

---

## 📁 STRUKTUR FILE

```
kasir/
├── app/
│   ├── controllers/
│   │   ├── Pos.php                    # Controller POS
│   │   └── admin/
│   │       └── Items.php              # Controller Items
│   ├── models/
│   │   ├── M_pos.php                  # Model POS
│   │   └── admin/
│   │       └── M_items.php            # Model Items
│   └── views/
│       ├── pos/
│       │   ├── main.php               # Halaman POS utama
│       │   ├── receipt.php            # Struk pembayaran
│       │   └── history.php            # Riwayat transaksi
│       └── admin/
│           └── items/
│               ├── view.php           # List items
│               ├── add.php            # Form tambah
│               └── edit.php           # Form edit
├── src/
│   ├── css/
│   │   └── pos.css                    # CSS POS
│   └── js/
│       ├── pos.js                     # JavaScript POS
│       ├── pos_history.js             # JavaScript History
│       └── admin/
│           └── items.js               # JavaScript Items
├── uploads/
│   └── items/                         # Folder upload gambar
├── sql_update_items_table.sql         # Query update items
├── sql_pos_system.sql                 # Query create POS tables
└── DOKUMENTASI_POS.md                 # Dokumentasi lengkap
```

---

## 🐛 TROUBLESHOOTING

### Problem 1: Items tidak muncul di POS
**Solusi:**
- Pastikan ada data di tabel `items`
- Pastikan kolom `namaitem`, `kodeitem`, `hargasatuan` sudah ada
- Cek browser console (F12) untuk error JavaScript

### Problem 2: Gagal upload gambar
**Solusi:**
- Pastikan folder `uploads/items/` sudah dibuat
- Set permission folder menjadi writable (777)
- Cek ukuran file (max 2MB)
- Format yang didukung: JPG, JPEG, PNG, GIF

### Problem 3: Nomor invoice tidak generate
**Solusi:**
- Pastikan tabel `pos_transactions` sudah dibuat
- Cek koneksi database
- Cek error di browser console

### Problem 4: Transaksi tidak tersimpan
**Solusi:**
- Pastikan semua tabel POS sudah dibuat
- Cek foreign key constraint di `pos_transaction_details`
- Pastikan user sudah login (ada sess_id)
- Cek browser console untuk error

### Problem 5: Struk tidak bisa di-print
**Solusi:**
- Pastikan browser mengizinkan popup
- Gunakan Ctrl+P untuk manual print
- Cek printer sudah terkoneksi

### Problem 6: DataTables tidak muncul
**Solusi:**
- Pastikan library DataTables sudah di-load
- Cek path CSS dan JS DataTables
- Clear browser cache

---

## 🎨 CUSTOMISASI

### Ubah Warna Tema
Edit file `src/css/pos.css`:
```css
/* Gradient utama */
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

/* Ubah menjadi warna lain, contoh: */
background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
```

### Ubah Informasi Struk
Edit file `app/views/pos/receipt.php`:
```html
<div class="store-name">NAMA TOKO ANDA</div>
<div class="store-info">
    Jl. Contoh Alamat No. 123<br>
    Telp: 0812-3456-7890<br>
    Website: www.tokanda.com
</div>
```

### Tambah Diskon/Pajak
Edit file `src/js/pos.js` di fungsi `updateSummary()`:
```javascript
// Tambahkan perhitungan diskon
const diskon = subtotal * 0.1; // 10% diskon
const pajak = subtotal * 0.11; // 11% pajak
totalAmount = subtotal - diskon + pajak;
```

---

## ✨ FITUR LANJUTAN (Opsional)

Beberapa fitur yang bisa ditambahkan:
1. ✨ Dashboard analytics dengan grafik
2. ✨ Laporan penjualan PDF/Excel
3. ✨ Manajemen stok otomatis
4. ✨ Member & loyalty program
5. ✨ Shift kasir
6. ✨ Void/cancel transaksi
7. ✨ Split bill untuk dine in
8. ✨ Integrasi printer thermal
9. ✨ Barcode scanner support
10. ✨ Multi-currency

---

## 📞 SUPPORT

Jika mengalami kendala:
1. Cek file `DOKUMENTASI_POS.md`
2. Periksa browser console (F12) untuk error
3. Periksa PHP error di folder `app/logs/`
4. Pastikan semua requirement sudah terpenuhi

---

## ✅ CHECKLIST INSTALASI

- [ ] Jalankan SQL: `sql_update_items_table.sql`
- [ ] Jalankan SQL: `sql_pos_system.sql`
- [ ] Buat folder `uploads/items/` dan set permission
- [ ] Tambah minimal 1 item di menu Items
- [ ] Akses POS: `http://localhost/kasir/pos`
- [ ] Test tambah item ke keranjang
- [ ] Test pembayaran CASH
- [ ] Test pembayaran QRIS
- [ ] Test pembayaran TRANSFER
- [ ] Test cetak struk
- [ ] Test halaman history
- [ ] Test filter tanggal di history

---

## 🎉 SISTEM SIAP DIGUNAKAN!

**URL Akses:**
- 🏪 **POS System**: `http://localhost/kasir/pos`
- 📦 **Master Items**: `http://localhost/kasir/admin/items`
- 📊 **Riwayat**: `http://localhost/kasir/pos/history`

**Happy Selling! 🛒💰**

---

*Dibuat dengan ❤️ menggunakan CodeIgniter 3*
