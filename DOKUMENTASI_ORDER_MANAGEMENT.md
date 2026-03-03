# 📋 DOKUMENTASI SISTEM MANAJEMEN PESANAN RESTAURANT

## 🎯 Overview

Sistem manajemen pesanan terintegrasi untuk restaurant/warung makan yang menghubungkan:
- **Order Entry** (Pencatatan pesanan oleh pelayan)
- **Kitchen Display** (Tampilan dapur untuk monitor pesanan)
- **POS System** (Kasir untuk proses pembayaran)

---

## 🗄️ Database Schema

### 1. Table `orders`
Menyimpan data pesanan utama.

```sql
CREATE TABLE `orders` (
  `order_id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `order_number` varchar(30) UNIQUE NOT NULL,
  `table_id` int(11) DEFAULT NULL,
  `table_number` varchar(20) DEFAULT NULL,
  `order_type` enum('DINE_IN','TAKEAWAY') NOT NULL,
  `customer_name` varchar(100),
  `customer_phone` varchar(20),
  `status` enum('PENDING','PREPARING','READY','SERVED','PAID','CANCELLED'),
  `total_items` int(11) NOT NULL DEFAULT 0,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `notes` text DEFAULT NULL
);
```

### 2. Table `order_items`
Menyimpan detail item dalam setiap pesanan.

```sql
CREATE TABLE `order_items` (
  `order_item_id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `kodeitem` varchar(10),
  `namaitem` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `status` enum('PENDING','PREPARING','READY','SERVED','CANCELLED'),
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(order_id)
);
```

### 3. Update `pos_transactions`
Menambahkan kolom untuk link ke orders dan split bill support.

```sql
ALTER TABLE `pos_transactions` 
  ADD COLUMN `order_id` int(11) DEFAULT NULL,
  ADD COLUMN `split_number` int(11) DEFAULT NULL,
  ADD COLUMN `split_total` int(11) DEFAULT NULL;
```

### 4. Table `pos_tables` (existing)
Untuk manajemen meja restaurant.

---

## 📊 Status Flow

### Order Status:
```
PENDING     → Pesanan baru masuk
PREPARING   → Sedang dimasak di dapur
READY       → Siap disajikan
SERVED      → Sudah disajikan ke customer
PAID        → Sudah dibayar
CANCELLED   → Dibatalkan
```

### Item Status (per item dalam order):
```
PENDING     → Belum mulai dimasak
PREPARING   → Sedang dimasak
READY       → Siap disajikan
SERVED      → Sudah disajikan
CANCELLED   → Item dibatalkan
```

---

## 🚀 Instalasi

### Step 1: Jalankan SQL
```bash
# Jalankan file SQL di phpMyAdmin atau MySQL console
sql_order_management.sql
```

### Step 2: Upload Files
Pastikan semua file sudah ada di lokasi yang tepat:

**Controllers:**
- `app/controllers/Orders.php` - Manajemen pesanan
- `app/controllers/Kitchen.php` - Kitchen display
- `app/controllers/Pos.php` - Updated untuk order support

**Models:**
- `app/models/M_orders.php` - Order data access

**Views:**
- `app/views/orders/create.php` - Form buat pesanan
- `app/views/orders/list.php` - Daftar pesanan
- `app/views/kitchen/display.php` - Kitchen display
- `app/views/pos/main.php` - Updated POS interface

**JavaScript:**
- `src/js/orders_create.js` - Order creation logic
- `src/js/pos.js` - Updated dengan order functions

---

## 🎬 Alur Kerja Restaurant

### 1️⃣ Order Entry (Pelayan/Waiter)
**URL:** `http://localhost/kasir/orders/create`

**Langkah-langkah:**
1. Pilih jenis pesanan: **Dine In** atau **Takeaway**
2. Jika Dine In → Pilih nomor meja
3. (Opsional) Input nama customer & no HP
4. Pilih menu dari grid yang tersedia
5. Klik item untuk tambahkan ke keranjang
6. Atur quantity dengan tombol +/-
7. Tambahkan catatan khusus (opsional)
8. Klik **"Buat Pesanan"**

**Output:**
- Order disimpan dengan status **PENDING**
- Nomor order: `ORD-YYYYMMDD-XXXX`
- Meja berubah status ke **occupied**
- Order muncul di Kitchen Display

---

### 2️⃣ Kitchen Display (Dapur)
**URL:** `http://localhost/kasir/kitchen`

**Fitur:**
- **Auto-refresh** setiap 10 detik
- Dashboard statistik (Pending, Preparing, Ready)
- Tampilan card per order dengan:
  - Nomor order
  - Nomor meja / Takeaway
  - Waktu tunggu (berapa lama order dibuat)
  - Daftar items & quantity
  - Catatan khusus (jika ada)

**Aksi:**
1. **Order PENDING** → Tombol "Mulai Masak"
   - Mengubah status ke **PREPARING**
   - Card berubah warna biru
   
2. **Order PREPARING** → Tombol "Siap Sajikan"
   - Mengubah status ke **READY**
   - Card berubah warna hijau
   - Tampil notifikasi "SIAP DISAJIKAN"

3. **Order READY** → Pelayan mengambil dan menyajikan ke customer

---

### 3️⃣ Payment (Kasir/POS)
**URL:** `http://localhost/kasir/pos`

**Dua Cara Input:**

#### A. Pilih dari Pesanan yang Ada
1. Klik tombol **"Pilih dari Pesanan yang Ada"**
2. Muncul modal dengan daftar pesanan belum bayar
3. Pilih pesanan yang mau dibayar
4. Items otomatis masuk ke keranjang
5. Lanjut ke proses pembayaran

#### B. Input Manual (seperti biasa)
1. Pilih item langsung dari menu
2. Masukkan ke keranjang
3. Proses pembayaran normal

**Proses Pembayaran:**
1. Review items di keranjang
2. Klik **"Pembayaran"**
3. Pilih metode: CASH / QRIS / TRANSFER
4. Jika Cash → Input jumlah bayar
5. Sistem hitung kembalian otomatis
6. Klik **"Selesaikan Pembayaran"**

**Output:**
- Transaksi tersimpan dengan link ke order
- Order status berubah ke **PAID**
- Meja berubah ke **available**
- Cetak struk (opsional)

---

## 🔧 API Endpoints

### Orders Controller

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/orders` | Daftar semua pesanan |
| GET | `/orders/create` | Form buat pesanan baru |
| GET | `/orders/get_items` | Get items untuk order |
| GET | `/orders/get_tables` | Get available tables |
| POST | `/orders/save_order` | Simpan pesanan baru |
| GET | `/orders/get_orders?status=PENDING` | Get orders by status |
| GET | `/orders/get_order_detail/{id}` | Detail pesanan |
| POST | `/orders/update_status` | Update status pesanan |
| POST | `/orders/cancel_order` | Batalkan pesanan |

### Kitchen Controller

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/kitchen` | Kitchen display page |
| GET | `/kitchen/get_pending_orders` | Get pending/preparing/ready orders |
| POST | `/kitchen/update_status` | Update order status |
| POST | `/kitchen/update_item_status` | Update item status |

### POS Controller (Updated)

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/pos/get_unpaid_orders` | Get orders belum bayar |
| GET | `/pos/load_order/{id}` | Load order ke POS |
| POST | `/pos/save_transaction_from_order` | Simpan transaksi dari order |

---

## 💡 Fitur Unggulan

### ✅ Order Number Generator
```php
Format: ORD-YYYYMMDD-XXXX
Example: ORD-20260302-0001
```
Auto-increment per hari, reset setiap hari baru.

### ✅ Table Management
- Track table status (available/occupied)
- Auto-update saat order dibuat & dibayar
- Support multiple tables

### ✅ Real-time Kitchen Display
- Auto-refresh setiap 10 detik
- Color-coded status (Kuning=Pending, Biru=Preparing, Hijau=Ready)
- Time tracker (berapa lama order tunggu)

### ✅ Order-Based Payment
- Link transaksi ke order
- Tracking order mana yang sudah bayar
- History lengkap dari order → payment

### ✅ Split Bill Support (Prepared)
Database sudah siap untuk split bill:
- `split_number` - Nomor split (1, 2, 3, dst)
- `split_total` - Total berapa split dari order ini
- Logic untuk mark order as PAID setelah semua split dibayar

---

## 🎨 UI/UX Highlights

### Order Creation (Waiter App):
- Grid layout items dengan gambar
- Search/filter items
- Real-time cart update
- Visual feedback saat add item
- Clear summary total

### Kitchen Display:
- Full-screen optimized
- Large fonts untuk visibility
- Card-based layout
- Color-coded priority
- One-click status update

### POS Integration:
- Seamless order selection
- Auto-populate cart from order
- Maintain existing POS workflow
- Clear order indicator

---

## 📱 Akses URLs

```
Order Entry (Pelayan):
http://localhost/kasir/orders/create

Daftar Pesanan:
http://localhost/kasir/orders

Kitchen Display:
http://localhost/kasir/kitchen

POS (Kasir):
http://localhost/kasir/pos
```

---

## 🔐 User Permissions

**Semua user yang login bisa akses:**
- ✅ Order Entry
- ✅ Kitchen Display
- ✅ POS

Untuk production, sebaiknya tambahkan role-based access:
- **Waiter** → Order Entry only
- **Kitchen** → Kitchen Display only
- **Cashier** → POS only
- **Manager** → All access + Reports

---

## 🐛 Troubleshooting

### Issue: Order tidak muncul di Kitchen
**Solusi:**
- Cek status order di database (harus PENDING/PREPARING/READY)
- Cek auto-refresh kitchen display (setiap 10 detik)
- Manual refresh browser

### Issue: Meja tetap occupied setelah bayar
**Solusi:**
- Pastikan order status berubah ke PAID
- Cek function `mark_order_as_paid()` di M_orders

### Issue: Items tidak muncul di order creation
**Solusi:**
- Pastikan ada data di table `items`
- Cek field `hargasatuan` tidak NULL
- Cek AJAX endpoint `/orders/get_items`

### Issue: Payment gagal dari order
**Solusi:**
- Cek `order_id` di `pos_transactions` nullable
- Pastikan function `save_transaction_from_order()` dipanggil
- Cek console browser untuk error JavaScript

---

## 📊 Database Queries Berguna

### Lihat pesanan hari ini:
```sql
SELECT * FROM orders 
WHERE DATE(created_at) = CURDATE()
ORDER BY created_at DESC;
```

### Pesanan yang belum dibayar:
```sql
SELECT * FROM orders 
WHERE status IN ('PENDING','PREPARING','READY','SERVED')
ORDER BY created_at ASC;
```

### Pendapatan hari ini:
```sql
SELECT SUM(total_amount) as revenue
FROM orders 
WHERE status = 'PAID' 
AND DATE(created_at) = CURDATE();
```

### Top selling items:
```sql
SELECT namaitem, SUM(quantity) as total_qty
FROM order_items 
WHERE DATE(created_at) = CURDATE()
GROUP BY namaitem 
ORDER BY total_qty DESC 
LIMIT 10;
```

---

## 🚀 Next Steps / Enhancements

1. **Split Bill Implementation**
   - UI untuk select items mana yang mau dibayar
   - Track partial payments
   - Print split receipts

2. **Print Order to Kitchen**
   - Generate kitchen receipt per order
   - Printer support untuk dapur

3. **Order Notifications**
   - Sound notification di kitchen
   - Push notification untuk ready orders

4. **Table Layout Visual**
   - Visual table map
   - Drag & drop table management

5. **Reporting Dashboard**
   - Sales by order type (Dine In vs Takeaway)
   - Average preparation time
   - Peak hours analysis

6. **Customer Display System**
   - Public display untuk order status
   - Queue number display

---

## ✅ Testing Checklist

- [ ] Create order Dine In dengan meja
- [ ] Create order Takeaway
- [ ] View order di kitchen display
- [ ] Update status PENDING → PREPARING
- [ ] Update status PREPARING → READY
- [ ] Load order ke POS
- [ ] Process payment dari order
- [ ] Verify order status PAID
- [ ] Verify table kembali available
- [ ] Print receipt
- [ ] Cancel order
- [ ] View order history

---

## 📞 Support

Jika ada pertanyaan atau issue:
1. Cek error log: `app/logs/`
2. Cek browser console untuk JavaScript errors
3. Verify database structure dengan SQL files

---

**Sistem siap digunakan! 🎉**

Jalankan SQL, upload files, dan test workflow dari order → kitchen → payment.
