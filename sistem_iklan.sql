-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 02, 2026 at 10:50 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_iklan`
--

-- --------------------------------------------------------

--
-- Table structure for table `iklan`
--

CREATE TABLE `iklan` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `judul_iklan` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `media` varchar(255) NOT NULL,
  `jenis_iklan` enum('Reels Instagram','Video TikTok','Story Instagram','Postingan Instagram','Popup Website') NOT NULL,
  `durasi` int NOT NULL,
  `harga_dasar` bigint DEFAULT NULL,
  `harga_per_hari` bigint DEFAULT NULL,
  `jumlah_hari` int DEFAULT NULL,
  `harga` bigint NOT NULL,
  `kode_pembayaran` varchar(100) NOT NULL,
  `status` enum('Belum Dibayar','Pending','Proses','Aktif','Selesai') NOT NULL DEFAULT 'Belum Dibayar',
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tipe_media` enum('foto','video') NOT NULL,
  `jenis_iklan_id` int NOT NULL,
  `metode_pembayaran_id` int DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `tanggal_pembayaran` datetime DEFAULT NULL,
  `diverifikasi_oleh` int DEFAULT NULL,
  `tanggal_verifikasi` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `iklan`
--

INSERT INTO `iklan` (`id`, `user_id`, `judul_iklan`, `deskripsi`, `media`, `jenis_iklan`, `durasi`, `harga_dasar`, `harga_per_hari`, `jumlah_hari`, `harga`, `kode_pembayaran`, `status`, `tanggal_mulai`, `tanggal_selesai`, `created_at`, `tipe_media`, `jenis_iklan_id`, `metode_pembayaran_id`, `bukti_pembayaran`, `tanggal_pembayaran`, `diverifikasi_oleh`, `tanggal_verifikasi`) VALUES
(5, 2, 'z', 'a', '1779608563_CamScanner 18-05-2026 21.05_2.jpg.jpeg', 'Reels Instagram', 0, 100000, 1000000, 1, 1100000, 'INV-6A12ABF39E3FA', 'Selesai', '2026-05-24', '2026-05-24', '2026-05-24 07:42:43', 'foto', 3, 3, '1779611884_CamScanner 18-05-2026 21.05_1.jpg.jpeg', '2026-05-24 15:38:04', 1, '2026-05-24 15:38:48'),
(8, 2, 'Judol onlen', 'sadsad', '1779704083_fa4f1bae-bb7c-4cfd-ac84-b36380a77372.jpg', 'Reels Instagram', 0, 100000, 1000000, 2, 2100000, 'INV-6A142113BF6F7', 'Selesai', '2026-05-25', '2026-05-26', '2026-05-25 10:14:43', 'foto', 3, 11, '1779704657_CamScanner 18-05-2026 21.05_1.jpg.jpeg', '2026-05-25 17:24:17', 1, '2026-05-25 17:30:06'),
(9, 2, 'Judol onlen', '1213', '1779709531_fa4f1bae-bb7c-4cfd-ac84-b36380a77372.jpg', 'Reels Instagram', 0, 100000, 1000000, 2, 2100000, 'INV-6A14365BB93AA', 'Selesai', '2026-05-25', '2026-05-26', '2026-05-25 11:45:31', 'foto', 3, 11, '1779709559_CamScanner 18-05-2026 21.05_1.jpg.jpeg', '2026-05-25 18:46:00', 1, '2026-05-25 18:47:21'),
(10, 2, 'Judol onlen', 'asdsad', '1779709871_CamScanner 18-05-2026 21.05_1.jpg.jpeg', 'Reels Instagram', 0, 100000, 1000000, 2, 2100000, 'INV-6A1437AF325AC', 'Selesai', '2026-02-23', '2026-02-24', '2026-05-25 11:51:11', 'foto', 3, 11, '1779711961_CamScanner 18-05-2026 21.05_1.jpg.jpeg', '2026-05-25 19:26:01', 1, '2026-05-25 19:26:12'),
(11, 2, 'Judol onlen', 'sadsad', '1779712124_CamScanner 18-05-2026 21.05_2.jpg.jpeg', 'Reels Instagram', 0, 100000, 1000000, 1, 1100000, 'INV-6A14407C086A2', 'Selesai', '2026-05-25', '2026-05-25', '2026-05-25 12:28:44', 'foto', 3, 11, '1779712139_CamScanner 18-05-2026 21.05_1.jpg.jpeg', '2026-05-25 19:28:59', 1, '2026-05-25 19:29:28'),
(12, 2, 'Judol onlen', '12', '1779713350_CamScanner 18-05-2026 21.05_2.jpg.jpeg', 'Reels Instagram', 0, 100000, 1000000, 1, 1100000, 'INV-6A1445460FB8B', 'Belum Dibayar', '2026-05-25', '2026-05-25', '2026-05-25 12:49:10', 'foto', 3, NULL, NULL, NULL, NULL, NULL),
(13, 2, 'ad', 'a', '1779780681_fa4f1bae-bb7c-4cfd-ac84-b36380a77372.jpg', 'Reels Instagram', 0, 100000, 1000000, 3, 3100000, 'INV-6A154C49666AA', 'Belum Dibayar', '2026-05-25', '2026-05-27', '2026-05-26 07:31:21', 'foto', 3, NULL, NULL, NULL, NULL, NULL),
(14, 2, 'a', 'a', '1779781692_fa4f1bae-bb7c-4cfd-ac84-b36380a77372.jpg', 'Reels Instagram', 0, 100000, 1000000, 1, 1100000, 'INV-6A15503C5A8AF', 'Belum Dibayar', '2026-05-26', '2026-05-26', '2026-05-26 07:48:12', 'foto', 3, NULL, NULL, NULL, NULL, NULL),
(15, 2, 'Judol onlen', 'sadsa', '1779781805_fa4f1bae-bb7c-4cfd-ac84-b36380a77372.jpg', 'Reels Instagram', 0, 100000, 1000000, 1, 1100000, 'INV-6A1550AD13747', 'Belum Dibayar', '2026-05-26', '2026-05-26', '2026-05-26 07:50:05', 'foto', 3, NULL, NULL, NULL, NULL, NULL),
(19, 2, 'Judol onlen', 'dsadsadsd', '1779782872_𝐃𝐈𝐓𝐙𝐙 __ 𝟗_𝟏𝟔 __ 𝐏𝐀𝐏𝐀 𝐏𝐔𝐋𝐀𝐍𝐆 😋 [B6A4642].mp4', 'Reels Instagram', 18, 360000, 1000000, 1, 1360000, 'INV-6A1554D805480', 'Belum Dibayar', '2026-05-26', '2026-05-26', '2026-05-26 08:07:52', 'video', 3, NULL, NULL, NULL, NULL, NULL),
(20, 2, 'Judol onlen', 'assaf', '1779785074_screencapture-snbt-undip-ac-id-2026-05-25-22_04_14.png', 'Reels Instagram', 0, 100000, 1000000, 1, 1100000, 'INV-6A155D726C272', 'Selesai', '2026-05-26', '2026-05-26', '2026-05-26 08:44:34', 'foto', 3, 11, '1779785145_screencapture-snbt-undip-ac-id-2026-05-25-22_04_14.png', '2026-05-26 15:45:45', 1, '2026-05-26 15:46:11'),
(21, 4, 'NAGA969', 'Situs web judi Gacorr 999', '1779871155_casino-gambling-ad-digital-display-ad-design-template-30e76c1bd68fa82a28a746d3ec9f4d0e_screen.jpg', 'Reels Instagram', 0, 100000, 1000000, 1, 1100000, 'INV-6A16ADB3364D8', 'Selesai', '2026-05-27', '2026-05-27', '2026-05-27 08:39:15', 'foto', 3, 11, '1779871579_CamScanner 18-05-2026 21.05_1.jpg.jpeg', '2026-05-27 15:46:19', 1, '2026-05-27 15:52:51'),
(22, 92, 'NAGA969', 'lorem20', '1780382815_d.jpg', 'Reels Instagram', 0, 100000, 1000000, 3, 3100000, 'INV-6A1E7C5F56653', 'Aktif', '2026-06-02', '2026-06-04', '2026-06-02 06:46:55', 'foto', 3, 12, '1780383958_bukti tf.jpg', '2026-06-02 14:05:58', 1, '2026-06-02 14:06:56'),
(23, 92, 'TURUK999', 'lorem200', '1780385359_d.jpg', 'Reels Instagram', 0, 100000, 1000000, 13, 13100000, 'INV-6A1E864FADC64', 'Pending', '2026-06-29', '2026-07-11', '2026-06-02 07:29:19', 'foto', 3, 12, '1780385372_bukti tf.jpg', '2026-06-02 14:29:32', NULL, NULL),
(24, 92, 'SKY969', 'lorem430', '1780395989_d.jpg', 'Reels Instagram', 0, 150000, 800000, 1, 950000, 'INV-6A1EAFD5D3777', 'Belum Dibayar', '2026-06-10', '2026-06-10', '2026-06-02 10:26:29', 'foto', 8, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_iklan`
--

CREATE TABLE `jenis_iklan` (
  `id` int NOT NULL,
  `nama_jenis` varchar(100) NOT NULL,
  `tipe_media` enum('foto','video','keduanya') NOT NULL,
  `harga_foto` bigint DEFAULT NULL,
  `harga_video_per5detik` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `harga_per_hari` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jenis_iklan`
--

INSERT INTO `jenis_iklan` (`id`, `nama_jenis`, `tipe_media`, `harga_foto`, `harga_video_per5detik`, `created_at`, `harga_per_hari`) VALUES
(3, 'Reels Instagram', 'keduanya', 100000, 90000, '2026-05-23 13:16:51', 1000000),
(5, 'Tiktok Story', 'keduanya', 100000, 74999, '2026-06-02 10:28:25', 1050000),
(6, 'Feed Instagram', 'keduanya', 150000, 80000, '2026-06-02 10:29:11', 1100000),
(7, 'Youtube Shorts', 'video', NULL, 120000, '2026-06-02 10:30:17', 1150000),
(8, 'Postingan X', 'keduanya', 150000, 65000, '2026-06-02 10:30:58', 800000),
(9, 'Tiktok Video', 'keduanya', 120000, 90000, '2026-06-02 10:37:16', 850000),
(10, 'Reels Facebook', 'video', NULL, 90000, '2026-06-02 10:38:49', 950000),
(11, 'Postingan Facebook', 'foto', 120000, NULL, '2026-06-02 10:40:04', 780000),
(12, 'TIKTOK ADS', 'keduanya', 200000, 100000, '2026-06-02 10:41:09', 450000),
(13, 'FACEBOOK ADS', 'keduanya', 200000, 200000, '2026-06-02 10:42:17', 450000);

-- --------------------------------------------------------

--
-- Table structure for table `metode_pembayaran`
--

CREATE TABLE `metode_pembayaran` (
  `id` int NOT NULL,
  `judul` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `deskripsi` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `metode_pembayaran`
--

INSERT INTO `metode_pembayaran` (`id`, `judul`, `foto`, `deskripsi`, `created_at`) VALUES
(11, 'BNI', NULL, '219438219', '2026-05-25 10:24:04'),
(12, 'BRI', NULL, '3473287- BA', '2026-05-25 15:37:04'),
(13, 'QRIS', '1780397052_fa4f1bae-bb7c-4cfd-ac84-b36380a77372.jpg', '-', '2026-06-02 10:44:12'),
(14, 'DANA', NULL, '085136825989 - Basyari Atho&#039;illah', '2026-06-02 10:44:26'),
(15, 'ShopeePay', NULL, '085136825989 - Basyari Atho&#039;illah', '2026-06-02 10:45:00'),
(16, 'OVO', NULL, '085136825989 - Basyari Atho&#039;illah', '2026-06-02 10:46:06'),
(17, 'SeaBank', NULL, '085136825989 - Basyari Atho&#039;illah', '2026-06-02 10:46:22'),
(18, 'GOPAY', NULL, '085136825989 - Basyari Atho&#039;illah', '2026-06-02 10:46:39'),
(19, 'DOKU WALLET', NULL, '085136825989 - Basyari Atho&#039;illah', '2026-06-02 10:47:22'),
(20, 'LinkAja', NULL, '085136825989 - Basyari Atho&#039;illah', '2026-06-02 10:48:28');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `iklan_id` int DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` enum('0','1') DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `target_role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `iklan_id`, `title`, `message`, `is_read`, `created_at`, `target_role`) VALUES
(7, 2, NULL, 'Pembayaran Berhasil Dikirim', 'Bukti pembayaran berhasil dikirim dan sedang menunggu verifikasi admin.', '1', '2026-05-25 12:08:41', 'user'),
(9, 2, NULL, 'Pembayaran Berhasil Dikirim', 'Bukti pembayaran berhasil dikirim dan sedang menunggu verifikasi admin.', '1', '2026-05-25 12:24:19', 'user'),
(10, 2, NULL, 'Pembayaran Ditolak', 'Pembayaran untuk iklan \"Judol onlen\" ditolak admin. Silakan upload ulang bukti pembayaran.', '1', '2026-05-25 12:25:06', 'user'),
(11, 2, NULL, 'Pembayaran Berhasil Dikirim', 'Bukti pembayaran berhasil dikirim dan sedang menunggu verifikasi admin.', '1', '2026-05-25 12:26:02', 'user'),
(12, 2, NULL, 'Pembayaran Berhasil Dikirim', 'Bukti pembayaran berhasil dikirim dan sedang menunggu verifikasi admin.', '1', '2026-05-25 12:28:59', 'user'),
(13, 2, NULL, 'Pembayaran Diverifikasi', 'Pembayaran untuk iklan \"Judol onlen\" berhasil diverifikasi admin.', '1', '2026-05-25 12:29:28', 'user'),
(34, 2, NULL, 'Pembayaran Berhasil Dikirim', 'Bukti pembayaran berhasil dikirim dan sedang menunggu verifikasi admin.', '0', '2026-05-25 13:05:20', 'user'),
(36, 2, NULL, 'Pembayaran Berhasil Dikirim', 'Bukti pembayaran berhasil dikirim dan sedang menunggu verifikasi admin.', '0', '2026-05-25 13:05:29', 'user'),
(37, 1, NULL, 'Orderan Iklan Baru', 'Orderan iklan baru dengan kode invoice INV-6A1445460FB8B memerlukan verifikasi pembayaran.', '1', '2026-05-25 13:05:29', 'admin'),
(38, 2, NULL, 'Pembayaran Berhasil Dikirim', 'Bukti pembayaran berhasil dikirim dan sedang menunggu verifikasi admin.', '0', '2026-05-25 13:05:29', 'user'),
(39, 2, NULL, 'Pembayaran Ditolak', 'Pembayaran untuk iklan \"Judol onlen\" ditolak admin. Silakan upload ulang bukti pembayaran.', '0', '2026-05-25 16:02:38', 'user'),
(40, 2, NULL, 'Pembayaran Berhasil Dikirim', 'Bukti pembayaran berhasil dikirim dan sedang menunggu verifikasi admin.', '1', '2026-05-25 16:28:06', 'user'),
(41, 1, NULL, 'Orderan Iklan Baru', 'Orderan iklan baru dengan kode invoice INV-6A1445460FB8B memerlukan verifikasi pembayaran.', '0', '2026-05-25 16:28:06', 'admin'),
(42, 2, NULL, 'Iklan Selesai', 'Iklan \"Judol onlen\" telah selesai tayang. Terima kasih telah menggunakan layanan kami.', '0', '2026-05-25 17:23:17', 'user'),
(43, 2, NULL, 'Pembayaran Berhasil Dikirim', 'Bukti pembayaran berhasil dikirim dan sedang menunggu verifikasi admin.', '0', '2026-05-26 08:45:45', 'user'),
(44, 1, NULL, 'Orderan Iklan Baru', 'Orderan iklan baru dengan kode invoice INV-6A155D726C272 memerlukan verifikasi pembayaran.', '1', '2026-05-26 08:45:45', 'admin'),
(45, 2, NULL, 'Pembayaran Diverifikasi', 'Pembayaran untuk iklan \"Judol onlen\" berhasil diverifikasi admin.', '1', '2026-05-26 08:46:11', 'user'),
(46, 2, NULL, 'Iklan Sedang Tayang', 'Iklan \"Judol onlen\" sedang aktif tayang.', '0', '2026-05-26 08:46:54', 'user'),
(47, 2, NULL, 'Iklan Selesai', 'Iklan \"Judol onlen\" telah selesai tayang. Terima kasih telah menggunakan layanan kami.', '0', '2026-05-27 08:40:36', 'user'),
(48, 2, NULL, 'Iklan Selesai', 'Iklan \"Judol onlen\" telah selesai tayang. Terima kasih telah menggunakan layanan kami.', '0', '2026-05-27 08:40:43', 'user'),
(49, 2, NULL, 'Iklan Selesai', 'Iklan \"Judol onlen\" telah selesai tayang. Terima kasih telah menggunakan layanan kami.', '0', '2026-05-27 08:40:48', 'user'),
(50, 4, NULL, 'Pembayaran Berhasil Dikirim', 'Bukti pembayaran berhasil dikirim dan sedang menunggu verifikasi admin.', '0', '2026-05-27 08:46:19', 'user'),
(51, 1, NULL, 'Orderan Iklan Baru', 'Orderan iklan baru dengan kode invoice INV-6A16ADB3364D8 memerlukan verifikasi pembayaran.', '1', '2026-05-27 08:46:19', 'admin'),
(52, 4, NULL, 'Pembayaran Diverifikasi', 'Pembayaran untuk iklan \"NAGA969\" berhasil diverifikasi admin.', '0', '2026-05-27 08:52:51', 'user'),
(53, 4, NULL, 'Iklan Sedang Tayang', 'Iklan \"NAGA969\" sedang aktif tayang.', '0', '2026-05-27 08:53:03', 'user'),
(54, 92, NULL, 'Pembayaran Berhasil Dikirim', 'Bukti pembayaran berhasil dikirim dan sedang menunggu verifikasi admin.', '1', '2026-06-02 07:05:58', 'user'),
(55, 1, NULL, 'Orderan Iklan Baru', 'Orderan iklan baru dengan kode invoice INV-6A1E7C5F56653 memerlukan verifikasi pembayaran.', '0', '2026-06-02 07:05:58', 'admin'),
(56, 92, NULL, 'Pembayaran Diverifikasi', 'Pembayaran untuk iklan \"NAGA969\" berhasil diverifikasi admin.', '1', '2026-06-02 07:06:56', 'user'),
(57, 92, NULL, 'Iklan Sedang Tayang', 'Iklan \"NAGA969\" sedang aktif tayang.', '0', '2026-06-02 07:11:10', 'user'),
(58, 92, NULL, 'Pembayaran Berhasil Dikirim', 'Bukti pembayaran berhasil dikirim dan sedang menunggu verifikasi admin.', '0', '2026-06-02 07:29:32', 'user'),
(59, 1, NULL, 'Orderan Iklan Baru', 'Orderan iklan baru dengan kode invoice INV-6A1E864FADC64 memerlukan verifikasi pembayaran.', '0', '2026-06-02 07:29:32', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `foto` varchar(255) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `alamat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `created_at`, `foto`, `no_telp`, `alamat`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$L9nH2H9Pvst/Vje8JhKSfeRFwOnXpMPqtM6wEaBAPsjEsRy2F393m', 'admin', '2026-05-20 19:44:05', NULL, NULL, NULL),
(2, 'Farid', 'user@gmail.com', '$2y$10$81XqAuFl/om3d2NnFwLFH.xmrf5FLGqVCE8kIrniKH8Zk4piiQ.Ry', 'user', '2026-05-20 20:31:30', '1779532229_2f2b9d28eef27a279e4fb36aaabc6d1e.jpg', '085136825989', 'Jalan jalan ad'),
(3, 'basyari', 'basyari@gmail.com', '$2y$10$ileKgL2pQLi3Ow5ajQHDP.HjuAWT9uQiAIaGR/lelLKoQYpe5WAzq', 'user', '2026-05-25 18:40:36', NULL, NULL, NULL),
(4, 'Basyari', 'basyar@gmail.com', '$2y$10$BsUHEDyU4C5RLvP7fJV0VO6bIIoXYl3Ql12XgIgycjjbM.7eiO5ze', 'user', '2026-05-27 08:34:04', '1779873464_20250727_231056.jpg.jpeg', '', ''),
(91, 'Faridda', 'faridass@gmail.com', '$2y$10$lCKMzSlkgADr/WzKRlV/buDaWWnnxoF2ZNac.hPBEpXdWL4AApvrq', 'user', '2026-05-31 16:21:17', NULL, NULL, NULL),
(92, 'kairi', 'kairi@gmail.com', '$2y$10$/DSAwtZdrVRnHXNPnQU4ruCEM0WJja9EaAXxvu4VAxwJrGLt7ktlu', 'user', '2026-06-02 06:40:46', '1780382743_20250727_231056.jpg.jpeg', '085136825989', ''),
(93, 'Eko', 'eko@gmail.com', '$2y$10$t04LFD3UkAIccSx8841XQ..PzFtjGtkgpcxuTEtyd6I/DcBHvX7Ii', 'user', '2026-06-02 10:18:58', NULL, NULL, NULL),
(94, 'User Kedua', 'user2@gmail.com', '$2y$10$tI02t51rAVv6FyJRjrjDoOMzudYWA2/qaevqb6KTwlqWb3RqasSAi', 'user', '2026-06-02 10:19:29', NULL, NULL, NULL),
(95, 'User Ketiga', 'user3@gmail.com', '$2y$10$mL4LqtCYeftfiQKuhJSh2e0bwdsTll5h2yrB6yBAYHk1xRaylsIzy', 'user', '2026-06-02 10:19:53', NULL, NULL, NULL),
(96, 'User Keempat', 'user4@gmail.com', '$2y$10$UxKaZJfOqid4dv0aTf5yI.PtzlInP9QndOokhxFjmx1QncTocSAvC', 'user', '2026-06-02 10:20:34', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `iklan`
--
ALTER TABLE `iklan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `jenis_iklan`
--
ALTER TABLE `jenis_iklan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `metode_pembayaran`
--
ALTER TABLE `metode_pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `iklan_id` (`iklan_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `iklan`
--
ALTER TABLE `iklan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `jenis_iklan`
--
ALTER TABLE `jenis_iklan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `metode_pembayaran`
--
ALTER TABLE `metode_pembayaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `iklan`
--
ALTER TABLE `iklan`
  ADD CONSTRAINT `iklan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`iklan_id`) REFERENCES `iklan` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
