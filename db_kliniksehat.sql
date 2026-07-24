-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2026 at 12:00 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kliniksehat`
--

-- --------------------------------------------------------

--
-- Table structure for table `conf_level`
--

CREATE TABLE `conf_level` (
  `id_level` tinyint(2) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `conf_level`
--

INSERT INTO `conf_level` (`id_level`, `name`) VALUES
(1, 'Superadmin'),
(2, 'Admin'),
(3, 'Dokter');

-- --------------------------------------------------------

--
-- Table structure for table `conf_menu`
--

CREATE TABLE `conf_menu` (
  `id_menu` int(10) NOT NULL,
  `icon` varchar(30) NOT NULL,
  `icon2` varchar(150) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `link` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `akses` tinyint(1) NOT NULL,
  `sub` tinyint(1) NOT NULL,
  `level` text NOT NULL,
  `position` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `conf_menu`
--

INSERT INTO `conf_menu` (`id_menu`, `icon`, `icon2`, `name`, `link`, `status`, `akses`, `sub`, `level`, `position`) VALUES
(1, 'fa-desktop', NULL, 'Beranda', 'home', 1, 1, 1, '\"1\",\"2\",\"3\"', 1),
(2, 'fa-cogs', NULL, 'Pengaturan', 'admin/gen_modul', 1, 1, 1, '\"1\"', 2),
(7, 'fa-user-cog', NULL, 'Pengguna', 'pengguna', 1, 1, 1, '\"1\",\"2\",\"3\"', 3),
(8, 'fa-user-md', NULL, 'Dokter', 'dokter', 1, 1, 1, '\"1\",\"3\"', 4),
(9, 'fa-user-injured', NULL, 'Pendaftaran', 'patient', 1, 1, 1, '\"1\",\"2\",\"3\"', 5),
(10, 'fa-stethoscope', NULL, 'Anamnesa', 'anamnesa', 1, 1, 1, '\"1\",\"2\",\"3\"', 6),
(11, 'fa-briefcase-medical', NULL, 'Master Obat', 'obat', 1, 1, 1, '\"1\",\"2\",\"3\"', 10),
(12, 'fa-diagnoses', NULL, 'Master Diagnosa', 'diagnosa', 1, 1, 1, '\"1\",\"2\",\"3\"', 11),
(13, 'fa-book', NULL, 'Surat Keterangan Sakit', 'sks', 1, 1, 1, '\"1\",\"2\",\"3\"', 7),
(14, 'fa-book-open', NULL, 'SKBS', 'skbs', 1, 1, 1, '\"1\",\"2\",\"3\"', 8),
(15, 'fa-newspaper', NULL, 'SKMB', 'skmb', 1, 1, 1, '\"1\",\"2\",\"3\"', 9),
(16, 'fa-chart-pie', NULL, 'Laporan Obat Harian', 'reportobat', 1, 1, 1, '\"1\",\"2\",\"3\"', 12),
(17, 'fa-chart-bar', NULL, 'Laporan Obat Bulanan', 'monthlyobat', 1, 1, 1, '\"1\",\"2\",\"3\"', 13);

-- --------------------------------------------------------

--
-- Table structure for table `conf_submenu`
--

CREATE TABLE `conf_submenu` (
  `id_submenu` int(5) NOT NULL,
  `id_menu` int(5) NOT NULL,
  `icon` varchar(30) NOT NULL,
  `icon2` varchar(150) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `link` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `level` text NOT NULL,
  `position` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `conf_users`
--

CREATE TABLE `conf_users` (
  `id_user` int(10) NOT NULL,
  `fullname` varchar(60) NOT NULL,
  `nip` varchar(225) DEFAULT NULL,
  `avatar` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `salt` varchar(15) NOT NULL,
  `level` tinyint(2) NOT NULL,
  `last_login` datetime NOT NULL,
  `ip_address` varchar(25) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `conf_users`
--

INSERT INTO `conf_users` (`id_user`, `fullname`, `nip`, `avatar`, `username`, `password`, `salt`, `level`, `last_login`, `ip_address`, `status`) VALUES
(1, 'Superadmin', NULL, 'img/avatar/6U6lk2At.jpg', 'admin', 'fdbc3a37c635fe6b2a378ebb04c44a1c1d68fb8b', 'lxN/LwbZ', 1, '2026-07-23 14:44:31', '::1', 1),
(4, 'dr. Steve Kojongian', '440/188/DPM-PTSP/SIPTM/XII/2023', '', 'Steve', '732684aab216c0616b6fdca34618b9ae413994e1', 'u&qU0XDt', 3, '2026-07-23 12:54:58', '::1', 1),
(6, 'Admin Apotek', '', '', 'adminapotek', 'eadb874ccb9b5eb0468871d2c3ad20cee6f39af4', 't2Dn6yiX', 2, '0000-00-00 00:00:00', '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `start_event` datetime NOT NULL,
  `end_event` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `start_event`, `end_event`) VALUES
(1, 'Meeting with Mike', '2019-11-08 12:00:00', '2019-11-08 13:00:00'),
(4, 'Meeting with Mike', '2019-11-11 15:30:00', '2019-11-11 16:30:00'),
(5, 'makan', '2021-03-02 00:00:00', '2021-03-03 00:00:00'),
(6, 'minum', '2021-03-01 00:00:00', '2021-03-02 00:00:00'),
(7, 'Serapan', '2021-03-02 00:00:00', '2021-03-03 00:00:00'),
(8, 'breakfast', '2021-02-28 07:00:00', '2021-02-28 07:30:00'),
(9, 'Work', '2021-02-28 08:30:00', '2021-02-28 09:00:00'),
(10, 'asa', '2021-03-15 06:00:00', '2021-03-15 06:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `ms_city`
--

CREATE TABLE `ms_city` (
  `id_city` int(11) NOT NULL,
  `city_name` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ms_city`
--

INSERT INTO `ms_city` (`id_city`, `city_name`) VALUES
(1, 'MENUI KEPULAUAN'),
(2, 'BUNGKU SELATAN'),
(3, 'BAHODOPI'),
(4, 'BUNGKU PESISIR'),
(5, 'BUNGKU TENGAH'),
(6, 'BUNGKU TIMUR'),
(7, 'BUNGKU BARAT'),
(8, 'BUMI RAYA'),
(9, 'WITA PONDA');

-- --------------------------------------------------------

--
-- Table structure for table `ms_diagnosa`
--

CREATE TABLE `ms_diagnosa` (
  `id_diagnosa` int(11) NOT NULL,
  `dgn_name` varchar(100) DEFAULT NULL,
  `dgn_cat` varchar(10) DEFAULT NULL,
  `dgn_status` tinyint(4) DEFAULT 1,
  `dgn_insert_dt` datetime DEFAULT NULL,
  `dgn_insert_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ms_diagnosa`
--

INSERT INTO `ms_diagnosa` (`id_diagnosa`, `dgn_name`, `dgn_cat`, `dgn_status`, `dgn_insert_dt`, `dgn_insert_by`) VALUES
(1, 'AMOEBIC INFECTION OF OTHER SITES', 'A06.8', 1, '2026-07-21 12:33:17', 0),
(2, 'SLOW VIRUS INFECTION OF CENTRAL NERVOUS SYSTEM, UNSPECIFIED', 'A81.9', 1, '2026-07-21 12:53:49', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ms_district`
--

CREATE TABLE `ms_district` (
  `id_district` int(11) NOT NULL,
  `district_name` varchar(225) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ms_district`
--

INSERT INTO `ms_district` (`id_district`, `district_name`, `city_id`) VALUES
(1, 'PADEI LAUT', 1),
(2, 'ULUNAMBO', 1),
(3, 'KOFALAGADI', 1),
(4, 'TORUKUNO', 1),
(5, 'TEREBINO', 1),
(6, 'NGAPAEA', 1),
(7, 'MOROMPAITONGA', 1),
(8, 'PADALAA', 1),
(9, 'PADEI DARAT', 1),
(10, 'MASADIAN', 1),
(11, 'SAMARENGGA', 1),
(12, 'PULAU TIGA', 1),
(13, 'MATANO', 1),
(14, 'MATARAPE', 1),
(15, 'BURANGA', 1),
(16, 'ULUNIPA', 1),
(17, 'WAWONGKOLONO', 1),
(18, 'DONGKALANG', 1),
(19, 'TANJUNG HARAPAN', 1),
(20, 'TAFAGAPI', 1),
(21, 'PULAU TENGAH', 1),
(22, 'TANJUNG TIRAM', 1),
(23, 'MBOKITTA', 1),
(24, 'TANONA', 1),
(25, 'SAINOA', 2),
(26, 'POLEWALI', 2),
(27, 'PULAU DUA', 2),
(28, 'UMBELE', 2),
(29, 'JAWI JAWI', 2),
(30, 'BUTON', 2),
(31, 'KOBURU', 2),
(32, 'BUNGINGKELA', 2),
(33, 'LOKOMBULO', 2),
(34, 'PAKU', 2),
(35, 'BAKALA', 2),
(36, 'BUAJANGKA', 2),
(37, 'KALEROANG', 2),
(38, 'WARU WARU', 2),
(39, 'PADABALE', 2),
(40, 'PADO PADO', 2),
(41, 'PULAU BAPA', 2),
(42, 'LALEMO', 2),
(43, 'LAMONTOLI', 2),
(44, 'BUNGINTENDE', 2),
(45, 'BOELIMAU', 2),
(46, 'PANIMBAWANG', 2),
(47, 'PO\'O', 2),
(48, 'UMBELE LAMA', 2),
(49, 'PULAU DUA DARAT', 2),
(50, 'POARO', 2),
(51, 'BETE BETE', 3),
(52, 'PADABAHO', 3),
(53, 'MAKARTI JAYA', 3),
(54, 'LABOTA', 3),
(55, 'FATUFIA', 3),
(56, 'KEUREA', 3),
(57, 'BAHOMAKMUR', 3),
(58, 'BAHODOPI', 3),
(59, 'LALAMPU', 3),
(60, 'SIUMBATU', 3),
(61, 'DAMPALA', 3),
(62, 'LELE', 3),
(63, 'WERE EA', 4),
(64, 'SAMBALAGI', 4),
(65, 'LAROENAI', 4),
(66, 'BULELENG', 4),
(67, 'TORETE', 4),
(68, 'LAFEU', 4),
(69, 'TANDA OLEO', 4),
(70, 'ONE ETE', 4),
(71, 'TANGOFA', 4),
(72, 'PUUNGKEU', 4),
(73, 'PUUNGKOILU', 5),
(74, 'BAHONTOBUNGKU', 5),
(75, 'TOFUTI', 5),
(76, 'SAKITA', 5),
(77, 'MENDUI', 5),
(78, 'TOFOISO', 5),
(79, 'MARSAOLEH', 5),
(80, 'LAMBEREA', 5),
(81, 'BUNGI', 5),
(82, 'MATANO', 5),
(83, 'MATANSALA', 5),
(84, 'BAHORURU', 5),
(85, 'IPI', 5),
(86, 'BENTE', 5),
(87, 'BAHOMOHONI', 5),
(88, 'BAHOMOLEO', 5),
(89, 'BAHOMANTE', 5),
(90, 'LANONA', 5),
(91, 'TUDUA', 5),
(92, 'ONEPUTE JAYA', 6),
(93, 'BAHOMOTEFE', 6),
(94, 'BAHOMOAHI', 6),
(95, 'ULULERE', 6),
(96, 'KOLONO', 6),
(97, 'GERESA', 6),
(98, 'LAROUE', 6),
(99, 'NAMBO', 6),
(100, 'UNSONGI', 6),
(101, 'LAHUAFU', 6),
(102, 'BAHOEA REKO REKO', 7),
(103, 'WOSU', 7),
(104, 'LAROBENU', 7),
(105, 'UMPANGA', 7),
(106, 'TOFOGARO', 7),
(107, 'TONDO', 7),
(108, 'AMBUNU', 7),
(109, 'MARGA MULYA', 7),
(110, 'UEDAGO', 7),
(111, 'WATA', 7),
(112, 'PARILANGKE', 8),
(113, 'BERINGIN JAYA', 8),
(114, 'BAHONSUAI', 8),
(115, 'SAMARENDA', 8),
(116, 'ATANANGA', 8),
(117, 'LAMBELU', 8),
(118, 'LIMBO MAKMUR', 8),
(119, 'PEBATAE', 8),
(120, 'KARAUPA', 8),
(121, 'UMBELE', 8),
(122, 'HARAPAN JAYA', 8),
(123, 'PEBOTOA', 8),
(124, 'LASAMPI', 8),
(125, 'PUNTARI MAKMUR', 9),
(126, 'SAMPEANTABA', 9),
(127, 'LANTULA JAYA', 9),
(128, 'BUMI HARAPAN', 9),
(129, 'EMEA', 9),
(130, 'MOAHINO', 9),
(131, 'UNGKAYA', 9),
(132, 'SOLONSA JAYA', 9),
(133, 'SOLONSA', 9);

-- --------------------------------------------------------

--
-- Table structure for table `ms_obat`
--

CREATE TABLE `ms_obat` (
  `id_obat` int(11) NOT NULL,
  `obat_name` varchar(100) DEFAULT NULL,
  `obat_price` decimal(18,2) DEFAULT NULL,
  `obat_satuan` varchar(20) DEFAULT NULL,
  `obat_status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ms_obat`
--

INSERT INTO `ms_obat` (`id_obat`, `obat_name`, `obat_price`, `obat_satuan`, `obat_status`) VALUES
(1, 'EMTURNAS (PARACETAMOL 500 MG)', '1200.00', 'KPT', 1),
(2, 'AMBROXOL 15 MG/5 ML SYRUP', '30000.00', 'BT', 1),
(3, 'AMBEVEN (ANTI-HEMORRHOID)', '3600.00', 'KAP', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ms_patient`
--

CREATE TABLE `ms_patient` (
  `id_patient` int(11) NOT NULL,
  `patient_code` varchar(25) DEFAULT NULL,
  `patient_name` varchar(100) DEFAULT NULL,
  `patient_nik` varchar(10) DEFAULT NULL,
  `patient_company` varchar(100) DEFAULT NULL,
  `patient_department` varchar(100) DEFAULT NULL,
  `patient_ktp` varchar(20) DEFAULT NULL,
  `patient_bod` date DEFAULT NULL,
  `patient_gender` varchar(10) DEFAULT NULL,
  `patient_city_id` int(11) DEFAULT NULL,
  `patient_city_name` varchar(100) DEFAULT NULL,
  `patient_district_id` int(11) DEFAULT NULL,
  `patient_district_name` varchar(100) DEFAULT NULL,
  `patient_address` varchar(100) DEFAULT NULL,
  `patient_phone` varchar(25) DEFAULT NULL,
  `patient_status` tinyint(4) DEFAULT 1,
  `insert_by` int(11) DEFAULT NULL,
  `insert_dt` datetime DEFAULT NULL,
  `updateby` int(11) DEFAULT NULL,
  `updatedt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ms_patient`
--

INSERT INTO `ms_patient` (`id_patient`, `patient_code`, `patient_name`, `patient_nik`, `patient_company`, `patient_department`, `patient_ktp`, `patient_bod`, `patient_gender`, `patient_city_id`, `patient_city_name`, `patient_district_id`, `patient_district_name`, `patient_address`, `patient_phone`, `patient_status`, `insert_by`, `insert_dt`, `updateby`, `updatedt`) VALUES
(3, '2607220001', 'FITRI GURNING', '', '', '', '', '1993-07-01', 'P', 3, 'BAHODOPI', 58, 'BAHODOPI', 'BAHOMAKMUR', '081211903174', 1, 1, '2026-07-22 13:27:26', NULL, NULL),
(4, '2607220002', 'FALDI YUDIANTO', '', 'PT ITSS', 'IT', '1208160909890001', '1988-02-22', 'L', 3, 'BAHODOPI', 54, 'LABOTA', 'LABOTA', '081211903173', 1, 1, '2026-07-22 13:31:20', NULL, NULL),
(5, '2607220003', 'PATAR S', '88102624', 'PT IMIP', 'IT', '1208160507900001', '1990-07-05', 'L', 3, 'BAHODOPI', 55, 'FATUFIA', 'FATUFIA', '081211903174', 1, 1, '2026-07-22 13:39:25', NULL, NULL),
(6, '2607220004', 'ELKO DEDDY', '88102635', 'PT IMIP', 'APP DEV', '1208160507900004', '2000-07-13', 'L', 3, 'BAHODOPI', 54, 'LABOTA', '-', '081211903167', 1, 1, '2026-07-22 13:40:41', NULL, NULL),
(7, '2607220005', 'SATRIO', '88102626', 'PT YIP', '', '1208160507900002', '2000-07-05', 'L', 3, 'BAHODOPI', 54, 'LABOTA', '-', '081211903174', 1, 4, '2026-07-22 23:50:31', NULL, NULL),
(8, '2607220006', 'DEDDY SETIAWAN', '88102489', 'PT HYNC', '', '1208169007560001', '2026-07-22', 'L', 3, 'BAHODOPI', 55, 'FATUFIA', '-', '081211903174', 1, 4, '2026-07-22 23:53:03', NULL, NULL),
(9, '2607220007', 'SAMUEL SILITONGA', '', '', '', '', '2003-07-16', 'L', 3, 'BAHODOPI', 55, 'FATUFIA', '-', '081211903174', 1, 4, '2026-07-22 23:56:18', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pos_transactions`
--

CREATE TABLE `pos_transactions` (
  `id_transaction` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL COMMENT 'Link ke orders jika dari order',
  `split_number` int(11) DEFAULT NULL COMMENT 'Nomor split jika split bill',
  `split_total` int(11) DEFAULT NULL COMMENT 'Total split dari order ini',
  `no_invoice` varchar(50) NOT NULL,
  `tanggal` datetime NOT NULL,
  `total_item` int(11) DEFAULT 0,
  `subtotal` decimal(15,2) DEFAULT 0.00,
  `diskon` decimal(15,2) DEFAULT 0.00,
  `pajak` decimal(15,2) DEFAULT 0.00,
  `total_bayar` decimal(15,2) DEFAULT 0.00,
  `jumlah_bayar` decimal(15,2) DEFAULT 0.00,
  `kembalian` decimal(15,2) DEFAULT 0.00,
  `jenis_pembayaran` enum('CASH','QRIS','TRANSFER') NOT NULL DEFAULT 'CASH',
  `tipe_order` enum('DINE_IN','TAKEAWAY') NOT NULL DEFAULT 'DINE_IN',
  `kasir_id` int(11) DEFAULT NULL,
  `kasir_name` varchar(60) DEFAULT NULL,
  `status` enum('PENDING','COMPLETED','CANCELLED') DEFAULT 'PENDING',
  `catatan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pos_transactions`
--

INSERT INTO `pos_transactions` (`id_transaction`, `order_id`, `split_number`, `split_total`, `no_invoice`, `tanggal`, `total_item`, `subtotal`, `diskon`, `pajak`, `total_bayar`, `jumlah_bayar`, `kembalian`, `jenis_pembayaran`, `tipe_order`, `kasir_id`, `kasir_name`, `status`, `catatan`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, NULL, 'INV-20260302-0001', '2026-03-02 07:38:41', 3, '105000.00', '0.00', '0.00', '105000.00', '200000.00', '95000.00', 'CASH', 'DINE_IN', 3, 'Admin Utama', 'COMPLETED', '', '2026-03-02 07:38:41', NULL),
(3, 4, NULL, NULL, 'INV-20260303-0001', '2026-03-03 07:15:29', 6, '74000.00', '0.00', '0.00', '74000.00', '100000.00', '26000.00', 'CASH', 'DINE_IN', 3, 'Admin Utama', 'COMPLETED', '', '2026-03-03 07:15:29', NULL),
(4, NULL, NULL, NULL, 'INV-20260303-0002', '2026-03-03 07:59:53', 5, '115000.00', '0.00', '0.00', '115000.00', '115000.00', '0.00', 'CASH', 'DINE_IN', 3, 'Admin Utama', 'COMPLETED', '', '2026-03-03 07:59:53', NULL),
(5, NULL, NULL, NULL, 'INV-20260303-0003', '2026-03-03 08:54:42', 3, '45000.00', '0.00', '0.00', '45000.00', '45000.00', '0.00', 'CASH', 'DINE_IN', 3, 'Admin Utama', 'COMPLETED', '', '2026-03-03 08:54:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `request_items`
--

CREATE TABLE `request_items` (
  `request_number` varchar(25) NOT NULL,
  `date` date NOT NULL,
  `user` int(8) NOT NULL,
  `status` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `request_items`
--

INSERT INTO `request_items` (`request_number`, `date`, `user`, `status`) VALUES
('100000', '2021-02-15', 1, 1),
('10001', '2021-02-15', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `skkb`
--

CREATE TABLE `skkb` (
  `id` int(11) NOT NULL,
  `insertby` varchar(100) DEFAULT NULL,
  `insertdt` datetime DEFAULT NULL,
  `updateby` varchar(100) DEFAULT NULL,
  `updatedt` datetime DEFAULT NULL,
  `patient_name` varchar(200) NOT NULL COMMENT 'Nama karyawan',
  `age` varchar(50) DEFAULT NULL COMMENT 'Umur',
  `nik` varchar(50) DEFAULT NULL COMMENT 'NIK karyawan',
  `company_name` varchar(200) DEFAULT NULL COMMENT 'Perusahaan',
  `bagian` varchar(200) DEFAULT NULL COMMENT 'Departemen',
  `jabatan` varchar(200) DEFAULT NULL COMMENT 'Jabatan',
  `catatan` text DEFAULT NULL COMMENT 'Catatan Dokter',
  `docdate` date DEFAULT NULL,
  `doctby` varchar(200) DEFAULT NULL,
  `docnumb` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `skmb`
--

CREATE TABLE `skmb` (
  `id` int(11) NOT NULL,
  `visit_id` int(11) DEFAULT NULL,
  `insertby` varchar(100) DEFAULT NULL,
  `insertdt` datetime DEFAULT NULL,
  `updateby` varchar(100) DEFAULT NULL,
  `updatedt` datetime DEFAULT NULL,
  `patient_name` varchar(200) NOT NULL COMMENT 'Nama pengantar',
  `nik` varchar(50) DEFAULT NULL COMMENT 'NIK pengantar',
  `bagian` varchar(200) DEFAULT NULL COMMENT 'Bagian/Section',
  `company_name` varchar(200) DEFAULT NULL COMMENT 'Nama perusahaan',
  `patient_diantar` varchar(200) DEFAULT NULL COMMENT 'Nama pasien yang diantar',
  `age_diantar` varchar(50) DEFAULT NULL COMMENT 'Umur pasien diantar',
  `alamat_diantar` text DEFAULT NULL COMMENT 'Alamat pasien diantar',
  `hubungan` varchar(100) DEFAULT NULL COMMENT 'Hubungan: Suami/Istri/Anak/dll',
  `tgl_datang` date DEFAULT NULL COMMENT 'Tanggal datang mengantar',
  `jam` varchar(20) DEFAULT NULL COMMENT 'Jam datang',
  `docdate` date DEFAULT NULL,
  `doct_by_name` varchar(200) DEFAULT NULL,
  `doct_by_id` int(11) DEFAULT NULL,
  `docnumb` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `skmb`
--

INSERT INTO `skmb` (`id`, `visit_id`, `insertby`, `insertdt`, `updateby`, `updatedt`, `patient_name`, `nik`, `bagian`, `company_name`, `patient_diantar`, `age_diantar`, `alamat_diantar`, `hubungan`, `tgl_datang`, `jam`, `docdate`, `doct_by_name`, `doct_by_id`, `docnumb`) VALUES
(2, NULL, '1', '2026-07-22 14:18:51', NULL, NULL, 'PATAR M', '88102624', '', 'PT IMIP', 'NOVEL SINAGA', '36', 'MEDAN', 'SAUDARA', '2026-07-22', '00:03', '2026-07-22', 'dr. Steve Kojongian', 4, '00001/SKMB/VII/2026'),
(4, 7, '4', '2026-07-22 15:29:12', NULL, NULL, 'PATAR S', '88102624', 'IT', 'PT IMIP', 'MAGDALENA', '24', 'FATUFIA', 'SAUDARA', '2026-07-22', '23:28', '2026-07-22', 'dr. Steve Kojongian', 4, '00002/SKMB/VII/2026');

-- --------------------------------------------------------

--
-- Table structure for table `sks`
--

CREATE TABLE `sks` (
  `id` int(11) NOT NULL,
  `insertby` varchar(100) DEFAULT NULL,
  `insertdt` datetime DEFAULT NULL,
  `updateby` varchar(100) DEFAULT NULL,
  `updatedt` datetime DEFAULT NULL,
  `patient_name` varchar(200) NOT NULL,
  `company_name` varchar(225) DEFAULT NULL,
  `gender` enum('L','P') DEFAULT NULL,
  `age` varchar(225) DEFAULT NULL,
  `desa` varchar(150) DEFAULT NULL,
  `kecamatan_id` int(11) DEFAULT NULL,
  `kecamatan` varchar(150) DEFAULT NULL,
  `kelurahan_id` int(11) DEFAULT NULL,
  `kelurahan` varchar(150) DEFAULT NULL,
  `kabupaten` varchar(150) DEFAULT NULL,
  `provinsi` varchar(150) DEFAULT NULL,
  `diagnosa` text DEFAULT NULL,
  `terapi` text DEFAULT NULL,
  `datefrom` date DEFAULT NULL,
  `dateto` date DEFAULT NULL,
  `docdate` date DEFAULT NULL,
  `doctby` varchar(200) DEFAULT NULL,
  `docnumb` varchar(100) DEFAULT NULL,
  `visit_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `sks`
--

INSERT INTO `sks` (`id`, `insertby`, `insertdt`, `updateby`, `updatedt`, `patient_name`, `company_name`, `gender`, `age`, `desa`, `kecamatan_id`, `kecamatan`, `kelurahan_id`, `kelurahan`, `kabupaten`, `provinsi`, `diagnosa`, `terapi`, `datefrom`, `dateto`, `docdate`, `doctby`, `docnumb`, `visit_id`) VALUES
(25, '1', '2026-07-22 13:45:13', NULL, NULL, 'PATAR S', 'PT IMIP', 'L', '36', 'FATUFIA', 3, 'BAHODOPI', 55, 'FATUFIA', 'MOROWALI', 'SULAWESI TENGAH', '1. SLOW VIRUS INFECTION OF CENTRAL NERVOUS SYSTEM, UNSPECIFIED\n2. AMOEBIC INFECTION OF OTHER SITES', '1. AMBROXOL 15 MG/5 ML SYRUP\n2. AMBEVEN (ANTI-HEMORRHOID) 1 x 1', '2026-07-22', '2026-07-22', '2026-07-22', '4', '00001/SKS/VII/2026', 7),
(26, '4', '2026-07-23 00:09:56', NULL, NULL, 'PATAR S', 'PT IMIP', 'L', '36', 'FATUFIA', 3, 'BAHODOPI', 55, 'FATUFIA', 'MOROWALI', 'SULAWESI TENGAH', '1. AMOEBIC INFECTION OF OTHER SITES', '1. AMBROXOL 15 MG/5 ML SYRUP 1 x 1', '2026-07-23', '2026-07-23', '2026-07-23', '4', '00002/SKS/VII/2026', 8);

-- --------------------------------------------------------

--
-- Table structure for table `temp_login`
--

CREATE TABLE `temp_login` (
  `id_temp` int(10) NOT NULL,
  `id_user` int(5) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `ip_address` varchar(20) DEFAULT NULL,
  `nama_user` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `temp_login`
--

INSERT INTO `temp_login` (`id_temp`, `id_user`, `tanggal`, `ip_address`, `nama_user`) VALUES
(1, 1, '2021-02-11 14:51:12', '::1', 'Superadmin'),
(2, 1, '2021-02-13 18:14:32', '::1', 'Superadmin'),
(3, 1, '2021-02-13 21:40:49', '::1', 'Superadmin'),
(4, 1, '2021-02-14 21:31:25', '::1', 'Superadmin'),
(5, 1, '2021-02-15 11:59:50', '::1', 'Superadmin'),
(6, 1, '2021-02-15 14:00:51', '::1', 'Superadmin'),
(7, 1, '2021-02-15 20:49:18', '::1', 'Superadmin'),
(8, 1, '2021-02-15 22:13:44', '::1', 'Superadmin'),
(9, 1, '2021-02-16 12:04:44', '::1', 'Superadmin'),
(10, 1, '2021-02-24 16:19:03', '::1', 'Superadmin'),
(11, 1, '2021-02-25 14:58:36', '::1', 'Superadmin'),
(12, 1, '2021-02-25 15:49:38', '::1', 'Superadmin'),
(13, 1, '2021-02-25 16:34:45', '::1', 'Superadmin'),
(14, 1, '2021-02-25 23:16:48', '::1', 'Superadmin'),
(15, 1, '2021-02-26 15:10:43', '::1', 'Superadmin'),
(16, 1, '2021-03-01 13:42:14', '::1', 'Superadmin'),
(17, 1, '2021-03-01 15:38:46', '::1', 'Superadmin'),
(18, 1, '2021-03-01 20:29:17', '::1', 'Superadmin'),
(19, 3, '2026-03-02 13:06:49', '::1', 'Admin Utama'),
(20, 3, '2026-03-02 13:12:19', '::1', 'Admin Utama'),
(21, 3, '2026-03-03 13:15:19', '::1', 'Admin Utama'),
(22, 3, '2026-03-03 18:49:02', '::1', 'Admin Utama'),
(23, 3, '2026-03-03 18:55:21', '::1', 'Admin Utama'),
(24, 3, '2026-03-03 20:39:19', '::1', 'Admin Utama'),
(25, 3, '2026-03-04 17:04:27', '::1', 'Admin Utama'),
(26, 1, '2026-07-02 15:46:36', '::1', 'Superadmin'),
(27, 1, '2026-07-02 17:36:12', '::1', 'Superadmin'),
(28, 1, '2026-07-02 21:00:05', '::1', 'Superadmin'),
(29, 1, '2026-07-02 21:12:40', '::1', 'Superadmin'),
(30, 1, '2026-07-04 14:18:25', '::1', 'Superadmin'),
(31, 1, '2026-07-04 14:20:09', '::1', 'Superadmin'),
(32, 1, '2026-07-04 14:26:25', '::1', 'Superadmin'),
(33, 1, '2026-07-04 14:27:38', '::1', 'Superadmin'),
(34, 1, '2026-07-04 14:30:12', '::1', 'Superadmin'),
(35, 1, '2026-07-04 14:51:43', '::1', 'Superadmin'),
(36, 1, '2026-07-04 15:00:29', '::1', 'Superadmin'),
(37, 1, '2026-07-04 17:38:21', '::1', 'Superadmin'),
(38, 1, '2026-07-04 18:29:07', '::1', 'Superadmin'),
(39, 1, '2026-07-04 18:42:36', '::1', 'Superadmin'),
(40, 1, '2026-07-04 18:47:06', '127.0.0.1', 'Superadmin'),
(41, 1, '2026-07-05 00:07:15', '::1', 'Superadmin'),
(42, 1, '2026-07-05 09:56:36', '::1', 'Superadmin'),
(43, 1, '2026-07-05 16:28:38', '::1', 'Superadmin'),
(44, 5, '2026-07-05 16:29:24', '::1', 'admin'),
(45, 1, '2026-07-05 16:30:31', '::1', 'Superadmin'),
(46, 1, '2026-07-05 16:55:45', '::1', 'Superadmin'),
(47, 4, '2026-07-05 16:56:45', '::1', 'Dr. Risha'),
(48, 1, '2026-07-05 17:23:30', '::1', 'Superadmin'),
(49, 1, '2026-07-05 18:37:17', '::1', 'Superadmin'),
(50, 1, '2026-07-05 18:42:54', '::1', 'Superadmin'),
(51, 1, '2026-07-05 18:44:19', '::1', 'Superadmin'),
(52, 1, '2026-07-05 20:07:15', '::1', 'Superadmin'),
(53, 1, '2026-07-05 20:08:00', '::1', 'Superadmin'),
(54, 1, '2026-07-05 20:12:40', '::1', 'Superadmin'),
(55, 1, '2026-07-05 20:21:21', '::1', 'Superadmin'),
(56, 1, '2026-07-05 20:31:08', '::1', 'Superadmin'),
(57, 1, '2026-07-06 09:11:59', '::1', 'Superadmin'),
(58, 1, '2026-07-06 12:32:50', '::1', 'Superadmin'),
(59, 1, '2026-07-06 18:49:44', '::1', 'Superadmin'),
(60, 1, '2026-07-06 20:15:56', '::1', 'Superadmin'),
(61, 1, '2026-07-08 14:31:52', '::1', 'Superadmin'),
(62, 1, '2026-07-09 09:52:54', '::1', 'Superadmin'),
(63, 1, '2026-07-09 15:42:53', '::1', 'Superadmin'),
(64, 1, '2026-07-10 12:30:37', '::1', 'Superadmin'),
(65, 1, '2026-07-12 18:25:25', '::1', 'Superadmin'),
(66, 1, '2026-07-12 18:38:11', '::1', 'Superadmin'),
(67, 1, '2026-07-13 19:05:00', '::1', 'Superadmin'),
(68, 1, '2026-07-15 19:09:09', '::1', 'Superadmin'),
(69, 1, '2026-07-15 22:54:15', '::1', 'Superadmin'),
(70, 1, '2026-07-21 17:29:52', '::1', 'Superadmin'),
(71, 1, '2026-07-21 21:54:19', '::1', 'Superadmin'),
(72, 1, '2026-07-22 11:45:27', '::1', 'Superadmin'),
(73, 1, '2026-07-22 12:59:39', '::1', 'Superadmin'),
(74, 1, '2026-07-22 13:52:20', '::1', 'Superadmin'),
(75, 1, '2026-07-22 13:56:10', '::1', 'Superadmin'),
(76, 1, '2026-07-22 14:38:18', '::1', 'Superadmin'),
(77, 1, '2026-07-22 19:15:57', '::1', 'Superadmin'),
(78, 1, '2026-07-22 22:43:54', '::1', 'Superadmin'),
(79, 4, '2026-07-22 22:44:22', '::1', 'dr. Steve Kojongian'),
(80, 4, '2026-07-22 22:48:02', '::1', 'dr. Steve Kojongian'),
(81, 4, '2026-07-22 23:03:42', '::1', 'dr. Steve Kojongian'),
(82, 1, '2026-07-22 23:12:40', '::1', 'Superadmin'),
(83, 1, '2026-07-23 06:35:36', '::1', 'Superadmin'),
(84, 1, '2026-07-23 11:14:52', '::1', 'Superadmin'),
(85, 1, '2026-07-23 12:43:13', '::1', 'Superadmin'),
(86, 4, '2026-07-23 12:54:58', '::1', 'dr. Steve Kojongian'),
(87, 1, '2026-07-23 14:28:03', '::1', 'Superadmin'),
(88, 1, '2026-07-23 14:43:30', '::1', 'Superadmin'),
(89, 1, '2026-07-23 14:44:31', '::1', 'Superadmin');

-- --------------------------------------------------------

--
-- Table structure for table `trans_anamnesa`
--

CREATE TABLE `trans_anamnesa` (
  `id_trans_anm` int(11) NOT NULL,
  `medical_record_id` int(11) DEFAULT NULL,
  `anm_weight` varchar(25) DEFAULT NULL,
  `anm_temp` varchar(10) DEFAULT NULL,
  `anm_pulse` varchar(10) DEFAULT NULL,
  `anm_respirasi` varchar(10) DEFAULT NULL,
  `anm_blood_press` varchar(10) DEFAULT NULL,
  `anm_height` varchar(10) DEFAULT NULL,
  `anm_note` varchar(225) DEFAULT NULL,
  `anm_stomatch_wide` varchar(10) DEFAULT NULL,
  `anm_insert_dt` datetime DEFAULT NULL,
  `anm_insert_by` int(11) DEFAULT NULL,
  `anm_status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trans_anamnesa`
--

INSERT INTO `trans_anamnesa` (`id_trans_anm`, `medical_record_id`, `anm_weight`, `anm_temp`, `anm_pulse`, `anm_respirasi`, `anm_blood_press`, `anm_height`, `anm_note`, `anm_stomatch_wide`, `anm_insert_dt`, `anm_insert_by`, `anm_status`) VALUES
(5, 6, '65', '36.5', '80', '20', '120/80', '165', '', '90', '2026-07-22 13:29:21', 1, 1),
(6, 7, '63', '36.5', '80', '20', '120/90', '168', '', '90', '2026-07-22 13:42:01', 1, 1),
(7, 8, '68', '36.5', '80', '20', '120/90', '170', '', '90', '2026-07-23 00:08:26', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `trans_diagnosa`
--

CREATE TABLE `trans_diagnosa` (
  `id_trans_dgn` int(11) NOT NULL,
  `trans_dgn_name` varchar(100) DEFAULT NULL,
  `trans_dgn_cat` varchar(10) DEFAULT NULL,
  `trans_dgn_status` tinyint(4) DEFAULT 1,
  `medical_record_id` int(11) DEFAULT NULL,
  `trans_dgn_insert_dt` datetime DEFAULT NULL,
  `trans_dgn_insert_by` int(11) DEFAULT NULL,
  `trans_dgn_note` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trans_diagnosa`
--

INSERT INTO `trans_diagnosa` (`id_trans_dgn`, `trans_dgn_name`, `trans_dgn_cat`, `trans_dgn_status`, `medical_record_id`, `trans_dgn_insert_dt`, `trans_dgn_insert_by`, `trans_dgn_note`) VALUES
(5, 'SLOW VIRUS INFECTION OF CENTRAL NERVOUS SYSTEM, UNSPECIFIED', 'A81.9', 1, 7, '2026-07-22 13:44:28', 1, ''),
(6, 'AMOEBIC INFECTION OF OTHER SITES', 'A06.8', 1, 7, '2026-07-22 13:44:34', 1, ''),
(7, 'AMOEBIC INFECTION OF OTHER SITES', 'A06.8', 1, 8, '2026-07-23 00:09:14', 4, '');

-- --------------------------------------------------------

--
-- Table structure for table `trans_medical_record`
--

CREATE TABLE `trans_medical_record` (
  `id_medical_record` int(11) NOT NULL,
  `visit_id` int(11) DEFAULT NULL,
  `mrd_status` tinyint(4) DEFAULT 1,
  `mrd_vst_type` int(11) DEFAULT NULL,
  `mrd_doct_by` int(11) DEFAULT NULL,
  `mrd_insert_dt` datetime DEFAULT NULL,
  `mrd_insert_by` int(11) DEFAULT NULL,
  `mrd_cancel_dt` datetime DEFAULT NULL,
  `mrd_cancel_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trans_medical_record`
--

INSERT INTO `trans_medical_record` (`id_medical_record`, `visit_id`, `mrd_status`, `mrd_vst_type`, `mrd_doct_by`, `mrd_insert_dt`, `mrd_insert_by`, `mrd_cancel_dt`, `mrd_cancel_by`) VALUES
(6, 6, 0, 1, NULL, '2026-07-22 13:28:16', 1, '2026-07-22 13:29:38', 1),
(7, 7, 1, 1, 4, '2026-07-22 13:41:26', 1, NULL, NULL),
(8, 8, 1, 1, 4, '2026-07-23 00:05:33', 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `trans_obat`
--

CREATE TABLE `trans_obat` (
  `id_trans_obat` int(11) NOT NULL,
  `obat_id` int(11) DEFAULT NULL,
  `medical_record_id` int(11) DEFAULT NULL,
  `trans_obat_name` varchar(100) DEFAULT NULL,
  `trans_obat_satuan` varchar(100) DEFAULT NULL,
  `trans_obat_dosis` varchar(100) DEFAULT NULL,
  `trans_obat_price` decimal(18,2) DEFAULT NULL,
  `trans_obat_qty` int(11) DEFAULT NULL,
  `trans_obat_total_price` decimal(10,0) DEFAULT NULL,
  `trans_obat_status` tinyint(4) DEFAULT 1,
  `trans_obat_insert_dt` datetime DEFAULT NULL,
  `trans_obat_insert_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trans_obat`
--

INSERT INTO `trans_obat` (`id_trans_obat`, `obat_id`, `medical_record_id`, `trans_obat_name`, `trans_obat_satuan`, `trans_obat_dosis`, `trans_obat_price`, `trans_obat_qty`, `trans_obat_total_price`, `trans_obat_status`, `trans_obat_insert_dt`, `trans_obat_insert_by`) VALUES
(9, 2, 7, 'AMBROXOL 15 MG/5 ML SYRUP', 'BT', '', '30000.00', 1, '30000', 1, '2026-07-22 13:44:41', 1),
(10, 3, 7, 'AMBEVEN (ANTI-HEMORRHOID)', 'KAP', '1 x 1', '3600.00', 1, '3600', 1, '2026-07-22 13:44:56', 1),
(11, 2, 8, 'AMBROXOL 15 MG/5 ML SYRUP', 'BT', '1 x 1', '30000.00', 1, '30000', 1, '2026-07-23 00:09:20', 4);

-- --------------------------------------------------------

--
-- Table structure for table `trans_skbs`
--

CREATE TABLE `trans_skbs` (
  `id_skbs` int(11) NOT NULL,
  `visit_id` int(11) DEFAULT NULL,
  `skbs_patient_name` varchar(100) DEFAULT NULL,
  `skbs_patient_nik` varchar(100) DEFAULT NULL,
  `skbs_patient_department` varchar(100) DEFAULT NULL,
  `skbs_patient_company` varchar(100) DEFAULT NULL,
  `skbs_patient_ktp` varchar(20) DEFAULT NULL,
  `skbs_patient_age` varchar(20) DEFAULT NULL,
  `skbs_result_id` int(11) DEFAULT NULL,
  `skbs_result_name` varchar(100) DEFAULT NULL,
  `skbs_desc` varchar(255) DEFAULT NULL,
  `skbs_note` varchar(255) DEFAULT NULL,
  `skbs_td` varchar(100) DEFAULT NULL,
  `skbs_bw` varchar(100) DEFAULT NULL,
  `skbs_tb` varchar(100) DEFAULT NULL,
  `skbs_bb` varchar(100) DEFAULT NULL,
  `skbs_r` varchar(100) DEFAULT NULL,
  `skbs_l` varchar(100) DEFAULT NULL,
  `skbs_koreksi_r` varchar(100) DEFAULT NULL,
  `skbs_koreksi_l` varchar(100) DEFAULT NULL,
  `skbs_doc_date` date DEFAULT NULL,
  `skbs_doct_id` int(11) DEFAULT NULL,
  `skbs_doct_name` varchar(100) DEFAULT NULL,
  `skbs_status` int(11) DEFAULT 1,
  `insert_dt` datetime DEFAULT NULL,
  `insert_by` int(11) DEFAULT NULL,
  `update_dt` datetime DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trans_skbs`
--

INSERT INTO `trans_skbs` (`id_skbs`, `visit_id`, `skbs_patient_name`, `skbs_patient_nik`, `skbs_patient_department`, `skbs_patient_company`, `skbs_patient_ktp`, `skbs_patient_age`, `skbs_result_id`, `skbs_result_name`, `skbs_desc`, `skbs_note`, `skbs_td`, `skbs_bw`, `skbs_tb`, `skbs_bb`, `skbs_r`, `skbs_l`, `skbs_koreksi_r`, `skbs_koreksi_l`, `skbs_doc_date`, `skbs_doct_id`, `skbs_doct_name`, `skbs_status`, `insert_dt`, `insert_by`, `update_dt`, `update_by`) VALUES
(7, 7, 'PATAR S', '88102624', 'IT', 'PT IMIP', '1208160507900001', '36', NULL, 'FIT', '', '', '120/80', 'Normal', '165', '65', '6/6', '6/6', '6/6', '6/6', '2026-07-22', 4, 'dr. Steve Kojongian', 1, '2026-07-22 14:02:03', 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `trans_visit`
--

CREATE TABLE `trans_visit` (
  `id_visit` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `trans_patient_code` varchar(25) DEFAULT NULL,
  `trans_patient_company` varchar(100) DEFAULT NULL,
  `trans_patient_department` varchar(100) DEFAULT NULL,
  `trans_patient_city_id` int(11) DEFAULT NULL,
  `trans_patient_city_name` varchar(100) DEFAULT NULL,
  `trans_patient_district_id` int(11) DEFAULT NULL,
  `trans_patient_district_name` varchar(100) DEFAULT NULL,
  `trans_patient_phone` varchar(25) DEFAULT NULL,
  `trans_vst_type` int(11) DEFAULT NULL,
  `trans_insert_dt` datetime DEFAULT NULL,
  `trans_insert_by` int(11) DEFAULT NULL,
  `trans_cancel_dt` datetime DEFAULT NULL,
  `trans_cancel_by` int(11) DEFAULT NULL,
  `trans_status` tinyint(4) DEFAULT 1,
  `trans_doc` date DEFAULT NULL,
  `trans_doct_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trans_visit`
--

INSERT INTO `trans_visit` (`id_visit`, `patient_id`, `trans_patient_code`, `trans_patient_company`, `trans_patient_department`, `trans_patient_city_id`, `trans_patient_city_name`, `trans_patient_district_id`, `trans_patient_district_name`, `trans_patient_phone`, `trans_vst_type`, `trans_insert_dt`, `trans_insert_by`, `trans_cancel_dt`, `trans_cancel_by`, `trans_status`, `trans_doc`, `trans_doct_by`) VALUES
(6, 3, '2607220001', '', '', 3, 'BAHODOPI', 58, 'BAHODOPI', '081211903174', 1, '2026-07-22 13:28:16', 1, '2026-07-22 13:29:38', 1, 0, '2026-07-22', NULL),
(7, 5, '2607220003', 'PT IMIP', 'IT', 3, 'BAHODOPI', 55, 'FATUFIA', '081211903174', 1, '2026-07-22 13:41:26', 1, NULL, NULL, 1, '2026-07-22', 4),
(8, 5, '2607220003', 'PT IMIP', 'IT', 3, 'BAHODOPI', 55, 'FATUFIA', '081211903174', 1, '2026-07-23 00:05:33', 4, NULL, NULL, 1, '2026-07-23', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `conf_level`
--
ALTER TABLE `conf_level`
  ADD PRIMARY KEY (`id_level`);

--
-- Indexes for table `conf_menu`
--
ALTER TABLE `conf_menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `conf_submenu`
--
ALTER TABLE `conf_submenu`
  ADD PRIMARY KEY (`id_submenu`);

--
-- Indexes for table `conf_users`
--
ALTER TABLE `conf_users`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ms_city`
--
ALTER TABLE `ms_city`
  ADD PRIMARY KEY (`id_city`);

--
-- Indexes for table `ms_diagnosa`
--
ALTER TABLE `ms_diagnosa`
  ADD PRIMARY KEY (`id_diagnosa`);

--
-- Indexes for table `ms_district`
--
ALTER TABLE `ms_district`
  ADD PRIMARY KEY (`id_district`);

--
-- Indexes for table `ms_obat`
--
ALTER TABLE `ms_obat`
  ADD PRIMARY KEY (`id_obat`);

--
-- Indexes for table `ms_patient`
--
ALTER TABLE `ms_patient`
  ADD PRIMARY KEY (`id_patient`);

--
-- Indexes for table `pos_transactions`
--
ALTER TABLE `pos_transactions`
  ADD PRIMARY KEY (`id_transaction`),
  ADD UNIQUE KEY `no_invoice` (`no_invoice`),
  ADD KEY `idx_tanggal` (`tanggal`),
  ADD KEY `idx_kasir` (`kasir_id`),
  ADD KEY `idx_order` (`order_id`);

--
-- Indexes for table `request_items`
--
ALTER TABLE `request_items`
  ADD PRIMARY KEY (`request_number`),
  ADD KEY `user` (`user`);

--
-- Indexes for table `skkb`
--
ALTER TABLE `skkb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skmb`
--
ALTER TABLE `skmb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sks`
--
ALTER TABLE `sks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temp_login`
--
ALTER TABLE `temp_login`
  ADD PRIMARY KEY (`id_temp`);

--
-- Indexes for table `trans_anamnesa`
--
ALTER TABLE `trans_anamnesa`
  ADD PRIMARY KEY (`id_trans_anm`);

--
-- Indexes for table `trans_diagnosa`
--
ALTER TABLE `trans_diagnosa`
  ADD PRIMARY KEY (`id_trans_dgn`);

--
-- Indexes for table `trans_medical_record`
--
ALTER TABLE `trans_medical_record`
  ADD PRIMARY KEY (`id_medical_record`);

--
-- Indexes for table `trans_obat`
--
ALTER TABLE `trans_obat`
  ADD PRIMARY KEY (`id_trans_obat`);

--
-- Indexes for table `trans_skbs`
--
ALTER TABLE `trans_skbs`
  ADD PRIMARY KEY (`id_skbs`);

--
-- Indexes for table `trans_visit`
--
ALTER TABLE `trans_visit`
  ADD PRIMARY KEY (`id_visit`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `conf_level`
--
ALTER TABLE `conf_level`
  MODIFY `id_level` tinyint(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `conf_menu`
--
ALTER TABLE `conf_menu`
  MODIFY `id_menu` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `conf_submenu`
--
ALTER TABLE `conf_submenu`
  MODIFY `id_submenu` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conf_users`
--
ALTER TABLE `conf_users`
  MODIFY `id_user` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ms_city`
--
ALTER TABLE `ms_city`
  MODIFY `id_city` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ms_diagnosa`
--
ALTER TABLE `ms_diagnosa`
  MODIFY `id_diagnosa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ms_district`
--
ALTER TABLE `ms_district`
  MODIFY `id_district` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT for table `ms_obat`
--
ALTER TABLE `ms_obat`
  MODIFY `id_obat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ms_patient`
--
ALTER TABLE `ms_patient`
  MODIFY `id_patient` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pos_transactions`
--
ALTER TABLE `pos_transactions`
  MODIFY `id_transaction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `skkb`
--
ALTER TABLE `skkb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `skmb`
--
ALTER TABLE `skmb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sks`
--
ALTER TABLE `sks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `temp_login`
--
ALTER TABLE `temp_login`
  MODIFY `id_temp` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `trans_anamnesa`
--
ALTER TABLE `trans_anamnesa`
  MODIFY `id_trans_anm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `trans_diagnosa`
--
ALTER TABLE `trans_diagnosa`
  MODIFY `id_trans_dgn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `trans_medical_record`
--
ALTER TABLE `trans_medical_record`
  MODIFY `id_medical_record` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `trans_obat`
--
ALTER TABLE `trans_obat`
  MODIFY `id_trans_obat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `trans_skbs`
--
ALTER TABLE `trans_skbs`
  MODIFY `id_skbs` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `trans_visit`
--
ALTER TABLE `trans_visit`
  MODIFY `id_visit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `request_items`
--
ALTER TABLE `request_items`
  ADD CONSTRAINT `request_items_ibfk_1` FOREIGN KEY (`user`) REFERENCES `conf_users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
