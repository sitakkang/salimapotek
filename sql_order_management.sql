-- =====================================================
-- SISTEM MANAJEMEN PESANAN RESTAURANT
-- Untuk alur: Order Entry → Kitchen → Payment
-- =====================================================

-- Table: orders (Pesanan utama)
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(30) NOT NULL,
  `table_id` int(11) DEFAULT NULL COMMENT 'NULL jika takeaway',
  `table_number` varchar(20) DEFAULT NULL COMMENT 'Snapshot nomor meja',
  `order_type` enum('DINE_IN','TAKEAWAY') NOT NULL DEFAULT 'DINE_IN',
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `status` enum('PENDING','PREPARING','READY','SERVED','PAID','CANCELLED') NOT NULL DEFAULT 'PENDING',
  `total_items` int(11) NOT NULL DEFAULT 0,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_by` int(11) NOT NULL COMMENT 'User ID pelayan/kasir',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `idx_table` (`table_id`),
  KEY `idx_status` (`status`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: order_items (Item dalam pesanan)
CREATE TABLE IF NOT EXISTS `order_items` (
  `order_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `kodeitem` varchar(10) DEFAULT NULL COMMENT 'Snapshot kode item',
  `namaitem` varchar(100) NOT NULL COMMENT 'Snapshot nama item',
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(15,2) NOT NULL COMMENT 'Harga saat order',
  `subtotal` decimal(15,2) NOT NULL COMMENT 'Quantity x Price',
  `status` enum('PENDING','PREPARING','READY','SERVED','CANCELLED') NOT NULL DEFAULT 'PENDING',
  `notes` text DEFAULT NULL COMMENT 'Catatan khusus (extra pedas, dll)',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_item_id`),
  KEY `idx_order` (`order_id`),
  KEY `idx_item` (`item_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Update pos_transactions untuk link ke orders (split bill support)
ALTER TABLE `pos_transactions` 
  ADD COLUMN `order_id` int(11) DEFAULT NULL COMMENT 'Link ke orders jika dari order' AFTER `id_transaction`,
  ADD COLUMN `split_number` int(11) DEFAULT NULL COMMENT 'Nomor split jika split bill' AFTER `order_id`,
  ADD COLUMN `split_total` int(11) DEFAULT NULL COMMENT 'Total split dari order ini' AFTER `split_number`,
  ADD KEY `idx_order` (`order_id`);

-- Note: Table pos_tables sudah ada, tidak perlu insert data lagi
-- Struktur yang ada: id_table, no_meja, kapasitas, status, created_at

-- =====================================================
-- STATUS FLOW:
-- PENDING    = Order baru masuk
-- PREPARING  = Sedang dimasak di dapur  
-- READY      = Siap disajikan
-- SERVED     = Sudah disajikan ke customer
-- PAID       = Sudah dibayar (untuk tracking)
-- CANCELLED  = Dibatalkan
-- =====================================================
