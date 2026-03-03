-- ============================================================
-- SQL UNTUK SISTEM POINT OF SALE (POS)
-- ============================================================

-- Tabel untuk menyimpan transaksi penjualan
CREATE TABLE `pos_transactions` (
  `id_transaction` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) DEFAULT NULL COMMENT 'Link ke orders jika dari order',
  `split_number` INT(11) DEFAULT NULL COMMENT 'Nomor split jika split bill',
  `split_total` INT(11) DEFAULT NULL COMMENT 'Total split dari order ini',
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
  UNIQUE KEY `no_invoice` (`no_invoice`),
  KEY `idx_tanggal` (`tanggal`),
  KEY `idx_kasir` (`kasir_id`),
  KEY `idx_order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk menyimpan detail item per transaksi
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
  KEY `idx_item` (`id_item`),
  CONSTRAINT `fk_transaction` FOREIGN KEY (`id_transaction`) REFERENCES `pos_transactions` (`id_transaction`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk meja (opsional untuk dine in)
CREATE TABLE `pos_tables` (
  `id_table` INT(11) NOT NULL AUTO_INCREMENT,
  `no_meja` VARCHAR(10) NOT NULL,
  `kapasitas` INT(11) DEFAULT 4,
  `status` ENUM('AVAILABLE', 'OCCUPIED', 'RESERVED') DEFAULT 'AVAILABLE',
  `created_at` DATETIME NULL,
  PRIMARY KEY (`id_table`),
  UNIQUE KEY `no_meja` (`no_meja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data untuk meja
INSERT INTO `pos_tables` (`no_meja`, `kapasitas`, `status`) VALUES
('M01', 2, 'AVAILABLE'),
('M02', 4, 'AVAILABLE'),
('M03', 4, 'AVAILABLE'),
('M04', 6, 'AVAILABLE'),
('M05', 2, 'AVAILABLE'),
('M06', 4, 'AVAILABLE'),
('M07', 6, 'AVAILABLE'),
('M08', 8, 'AVAILABLE');

-- ============================================================
-- QUERY UNTUK LAPORAN
-- ============================================================

-- Total penjualan hari ini
-- SELECT SUM(total_bayar) as total_penjualan FROM pos_transactions WHERE DATE(tanggal) = CURDATE() AND status = 'COMPLETED';

-- Transaksi per metode pembayaran
-- SELECT jenis_pembayaran, COUNT(*) as jumlah, SUM(total_bayar) as total FROM pos_transactions WHERE status = 'COMPLETED' GROUP BY jenis_pembayaran;

-- Top selling items
-- SELECT namaitem, SUM(quantity) as terjual, SUM(subtotal) as pendapatan FROM pos_transaction_details GROUP BY id_item ORDER BY terjual DESC LIMIT 10;
