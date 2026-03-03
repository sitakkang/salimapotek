# 🚀 QUICK START - Order Management System

## Instalasi Cepat (5 Menit)

### ✅ Step 1: Database
```bash
# Buka phpMyAdmin
# Pilih database kasir
# Import file:
sql_order_management.sql
```

File ini akan:
- Create table `orders`
- Create table `order_items`
- Update table `pos_transactions` (tambah kolom order_id, split_number, split_total)
- Insert sample data table (T1-T8)

### ✅ Step 2: Verify Files
Pastikan files ini ada:

**Controllers:**
- ✅ `app/controllers/Orders.php`
- ✅ `app/controllers/Kitchen.php`
- ✅ `app/controllers/Pos.php` (updated)

**Models:**
- ✅ `app/models/M_orders.php`

**Views:**
- ✅ `app/views/orders/create.php`
- ✅ `app/views/orders/list.php`
- ✅ `app/views/kitchen/display.php`

**JavaScript:**
- ✅ `src/js/orders_create.js`
- ✅ `src/js/pos.js` (updated)

### ✅ Step 3: Test Access
Buka browser dan coba:

1. **Order Entry (Pelayan)**
   ```
   http://localhost/kasir/orders/create
   ```
   - Buat pesanan baru
   - Pilih Dine In / Takeaway
   - Add items
   - Submit order

2. **Kitchen Display**
   ```
   http://localhost/kasir/kitchen
   ```
   - Lihat order yang masuk
   - Update status: Pending → Preparing → Ready

3. **POS (Kasir)**
   ```
   http://localhost/kasir/pos
   ```
   - Klik "Pilih dari Pesanan yang Ada"
   - Select order untuk dibayar
   - Proses payment

---

## 🎯 Workflow Testing

### Skenario 1: Dine In Order
```
1. Pelayan buka: /orders/create
2. Pilih "Dine In"
3. Pilih "Meja T1"
4. Add items: Nasi Goreng x2, Es Teh x2
5. Submit → Order created (ORD-20260302-0001)

6. Dapur buka: /kitchen
7. Lihat order baru masuk (status PENDING)
8. Klik "Mulai Masak" → status PREPARING
9. Setelah selesai, klik "Siap Sajikan" → status READY

10. Pelayan sajikan ke customer
11. Customer minta bill

12. Kasir buka: /pos
13. Klik "Pilih dari Pesanan yang Ada"
14. Pilih order ORD-20260302-0001
15. Items auto-masuk ke cart
16. Klik "Pembayaran"
17. Pilih CASH, input jumlah bayar
18. Selesaikan pembayaran
19. Print receipt

Result:
✅ Order status → PAID
✅ Meja T1 → available lagi
✅ Transaksi tersimpan
```

### Skenario 2: Takeaway Order
```
1. Pelayan: /orders/create
2. Pilih "Takeaway"
3. Input nama customer (optional)
4. Add items
5. Submit order

6. Kitchen: Update status sampai READY
7. Kasir: Load order, process payment
8. Customer ambil pesanan

Result:
✅ Order PAID
✅ No table involved
```

---

## 📊 Verify Database

### Check orders table:
```sql
SELECT order_number, order_type, table_number, status, total_amount 
FROM orders 
ORDER BY created_at DESC 
LIMIT 10;
```

Expected:
- order_number: ORD-20260302-0001
- order_type: DINE_IN atau TAKEAWAY
- status: PENDING/PREPARING/READY/SERVED/PAID

### Check order_items:
```sql
SELECT oi.*, o.order_number 
FROM order_items oi
JOIN orders o ON o.order_id = oi.order_id
ORDER BY oi.created_at DESC;
```

Expected:
- Setiap order ada items nya
- quantity, price, subtotal terisi

### Check transactions linked to orders:
```sql
SELECT t.*, o.order_number 
FROM pos_transactions t
LEFT JOIN orders o ON o.order_id = t.order_id
ORDER BY t.tanggal DESC;
```

Expected:
- Transaksi yang dari order punya order_id
- Transaksi manual (direct POS) order_id = NULL

---

## 🎨 Menu Integration

### Tambahkan menu di sidebar (optional)

Edit `app/views/skin/sidebar.php` atau menu config:

```json
{
  "title": "Restaurant",
  "icon": "fa-cutlery",
  "submenu": [
    {
      "title": "Buat Pesanan",
      "url": "orders/create",
      "icon": "fa-plus"
    },
    {
      "title": "Daftar Pesanan",
      "url": "orders",
      "icon": "fa-list"
    },
    {
      "title": "Kitchen Display",
      "url": "kitchen",
      "icon": "fa-fire"
    }
  ]
}
```

---

## 🔧 Troubleshooting

### Error: Table orders doesn't exist
**Fix:** Run `sql_order_management.sql`

### Error: Class M_orders not found
**Fix:** Check file `app/models/M_orders.php` exists

### Error: Cannot add or update child row
**Fix:** 
```sql
-- Disable foreign key checks temporarily
SET FOREIGN_KEY_CHECKS=0;
-- Run your SQL
SET FOREIGN_KEY_CHECKS=1;
```

### Items tidak muncul di order/create
**Fix:**
- Pastikan table `items` ada data
- Cek endpoint: `http://localhost/kasir/orders/get_items`
- Lihat console browser untuk error

### Kitchen display tidak auto-refresh
**Fix:**
- Check JavaScript console
- Verify `site_url` defined
- Clear browser cache

---

## 📱 Akses Mobile

Untuk kitchen display di tablet/HP:

1. Cari IP komputer server:
   ```bash
   ipconfig  # Windows
   ifconfig  # Linux/Mac
   ```

2. Buka di device lain:
   ```
   http://192.168.x.x/kasir/kitchen
   ```

3. Bookmark untuk quick access
4. Set browser ke fullscreen mode

---

## ⚡ Performance Tips

### Auto-refresh Kitchen
Default: 10 detik
Ubah di `kitchen/display.php`:
```javascript
refreshInterval = setInterval(loadOrders, 5000); // 5 detik
```

### Limit Order Display
Show only today's orders:
Edit `M_orders.php` → `get_pending_orders()`:
```php
$this->db->where('DATE(orders.created_at)', date('Y-d'));
```

---

## ✅ Post-Installation Checklist

- [ ] SQL imported successfully
- [ ] All files uploaded
- [ ] Orders/create accessible
- [ ] Kitchen display works
- [ ] POS can load orders
- [ ] Payment process works
- [ ] Receipt prints correctly
- [ ] Table status updates
- [ ] Order status flow works
- [ ] No JavaScript errors in console

---

## 🎉 Ready to Go!

**Sistem siap dipakai!**

Login → Orders/Create → Buat pesanan → Kitchen monitor → POS bayar

Untuk dokumentasi lengkap, lihat: `DOKUMENTASI_ORDER_MANAGEMENT.md`
