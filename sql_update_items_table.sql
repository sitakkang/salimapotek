-- ============================================================
-- QUERY SQL UNTUK MENAMBAHKAN FIELD BARU KE TABEL ITEMS
-- ============================================================

-- Alternatif 1: ALTER TABLE items (menambahkan kolom ke tabel yang sudah ada)
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

-- ============================================================
-- ATAU
-- ============================================================

-- Alternatif 2: CREATE TABLE items_new (membuat tabel baru dengan struktur lengkap)
CREATE TABLE `items_new` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `kodeitem` VARCHAR(10) NOT NULL,
  `namaitem` VARCHAR(50) NOT NULL,
  `hargasatuan` DECIMAL(15,2) DEFAULT 0,
  `deskripsi` VARCHAR(225) NULL,
  `itemimage` VARCHAR(255) NULL,
  `createby` INT(11) NULL,
  `updateby` INT(11) NULL,
  `createdt` DATETIME NULL,
  `updatedt` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kodeitem` (`kodeitem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- REKOMENDASI: Gunakan Alternatif 1 (ALTER TABLE)
-- jika Anda ingin mempertahankan data yang sudah ada
-- ============================================================

-- Setelah ALTER TABLE, Anda bisa update data lama dengan query:
UPDATE `items` SET 
  `kodeitem` = `item_number`,
  `namaitem` = `item_name`,
  `createdt` = NOW()
WHERE `kodeitem` IS NULL;
