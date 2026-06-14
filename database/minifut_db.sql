-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Jun 2026 pada 04.49
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `minifut_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `ID_ADMIN` int(11) NOT NULL,
  `USERNAME` varchar(50) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `NAMA` varchar(100) NOT NULL,
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`ID_ADMIN`, `USERNAME`, `PASSWORD`, `NAMA`, `CREATED_AT`) VALUES
(1, 'admin', '$2y$10$lGitCqvVJNg/93Ax09cZJ.k3UTC2QPfyuK3eMVIBTW9TnhqhnDOG6', 'Administrator', '2026-05-30 14:49:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `booking`
--

CREATE TABLE `booking` (
  `ID_BOOKING` varchar(50) NOT NULL,
  `ID_PELANGGAN` int(11) DEFAULT NULL,
  `TANGGAL_BOOKING` datetime DEFAULT NULL,
  `STATUS_BOOKING` varchar(20) DEFAULT NULL,
  `ID_JADWAL` int(11) DEFAULT NULL,
  `TEAM_NAME` varchar(100) DEFAULT NULL,
  `NOTES` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `booking`
--

INSERT INTO `booking` (`ID_BOOKING`, `ID_PELANGGAN`, `TANGGAL_BOOKING`, `STATUS_BOOKING`, `ID_JADWAL`, `TEAM_NAME`, `NOTES`) VALUES
('MF-030AF8', 4, '2026-05-31 08:51:42', 'LUNAS', 11, NULL, NULL),
('MF-1E7A34', 2, '2026-05-31 00:42:15', 'LUNAS', 3, NULL, NULL),
('MF-3484A0', 5, '2026-06-14 03:20:32', 'DP', 20, 'KING MU', NULL),
('MF-43CDCB', 5, '2026-06-13 23:56:31', 'LUNAS', 19, 'MU', NULL),
('MF-4F3E9B', 1, '2026-05-31 08:51:06', 'LUNAS', 10, NULL, NULL),
('MF-521EE6', 1, '2026-05-31 14:53:40', 'LUNAS', 12, NULL, NULL),
('MF-720236', 1, '2026-05-21 09:44:50', 'LUNAS', 1, NULL, NULL),
('MF-7B4623', 3, '2026-05-31 08:47:05', 'LUNAS', 4, NULL, NULL),
('MF-868961', 1, '2026-05-31 08:48:08', 'LUNAS', 6, NULL, NULL),
('MF-86AE25', 1, '2026-05-31 08:48:35', 'LUNAS', 7, NULL, NULL),
('MF-91F976', 4, '2026-05-31 08:50:32', 'LUNAS', 9, NULL, NULL),
('MF-9B2ABE', 5, '2026-06-01 11:17:29', 'DP', 18, 'MU', NULL),
('MF-ADF647', 2, '2026-05-31 08:49:18', 'LUNAS', 8, NULL, NULL),
('MF-BD031F', 3, '2026-05-31 08:47:49', 'LUNAS', 5, NULL, NULL),
('MF-C83A1E', 3, '2026-05-30 22:53:19', 'LUNAS', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `bookings_lama`
--

CREATE TABLE `bookings_lama` (
  `id` int(11) NOT NULL,
  `booking_code` varchar(20) NOT NULL,
  `field_id` int(11) NOT NULL,
  `book_date` date NOT NULL,
  `time_slots` varchar(100) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `team_name` varchar(100) DEFAULT NULL,
  `pay_type` varchar(20) NOT NULL,
  `total_price` int(11) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bookings_lama`
--

INSERT INTO `bookings_lama` (`id`, `booking_code`, `field_id`, `book_date`, `time_slots`, `customer_name`, `customer_phone`, `customer_email`, `team_name`, `pay_type`, `total_price`, `notes`, `created_at`) VALUES
(1, 'MF-89337C', 2, '2026-05-20', '8,9,10', 'KHAYR NOUREDINE YUNUS', '+6287888907879', 'khayrnouredineyunus@gmail.com', 'TRPL UGM', 'dp', 3600000, '', '2026-05-15 14:24:38'),
(2, 'MF-0B391F', 2, '2026-05-20', '20,21,22', 'Cristiano Ronaldo', '+6280707070707', 'cristianoronaldo@gmail.com', 'AL NASSR', 'lunas', 3600000, 'Sui', '2026-05-15 14:32:46'),
(3, 'MF-30155C', 3, '2026-05-20', '9,10', 'Kozu', '+6287080907070', 'kozu@gmail.com', '', 'dp', 2000000, '', '2026-05-15 16:03:50'),
(4, 'MF-1BF1E4', 2, '2026-05-20', '17,18,19', 'Bruno Fernandes', '+6281234567810', 'brunofernandes@gmail.com', 'MAN UNITED', 'dp', 3600000, 'Glory Glory ', '2026-05-16 00:55:56'),
(5, 'MF-090FD1', 2, '2026-05-20', '11,12,13', 'Harry Maguire', '+6280987654321', 'harrymaguire@gmail.com', 'MU', 'dp', 3600000, '', '2026-05-16 01:47:17'),
(6, 'MF-5340BE', 1, '2026-05-16', '15,16', 'Kobe Mainoo', '+6287888010101', 'kobemainoo@gmail.com', '', 'lunas', 2000000, '', '2026-05-16 07:48:50'),
(7, 'MF-E57AC8', 2, '2026-05-16', '15,16', 'Sesko', '+6287811111111', 'sesko@gmail.com', 'Kozu', 'dp', 2400000, '', '2026-05-16 08:01:51'),
(8, 'MF-46994E', 3, '2026-05-16', '16,17,18', 'bill', '+6287888909090', 'billclinton@gmail.com', '', 'dp', 3000000, '', '2026-05-16 08:08:26'),
(9, 'MF-02C7C3', 1, '2026-05-16', '17,18', 'joe', '+6287888907878', 'joebiden@gmail.com', '', 'lunas', 2000000, '', '2026-05-16 08:10:56'),
(10, 'MF-BB6BCE', 2, '2026-05-20', '14,15,16', 'KHAYR NOUREDINE YUNUS', '+6287888907879', 'khayrnouredineyunus@gmail.com', 'Kozu', 'dp', 3600000, '', '2026-05-16 08:16:37'),
(11, 'MF-BF90DA', 1, '2026-06-01', '15,16,17', 'KHAYR NOUREDINE YUNUS', '+6287888907879', 'khayrnouredineyunus@gmail.com', 'TGES', 'lunas', 3000000, '', '2026-05-16 08:40:37'),
(12, 'MF-D131DE', 1, '2026-07-11', '11,12,13', 'KHAYR NOUREDINE YUNUS', '+6287888907879', 'khayrnouredineyunus@gmail.com', 'GARASI', 'lunas', 3000000, '', '2026-05-16 08:49:47'),
(13, 'MF-D345C1', 3, '2026-05-17', '8,9', 'Rashford', '+6287888908888', 'kangrashford@gmail.com', '', 'dp', 2000000, 'Pesan 5 Air Putih 5 Es Teh', '2026-05-16 12:48:51'),
(14, 'MF-004255', 1, '2026-05-19', '8,9,10', 'Radya', '+6287888676767', 'radjorheri@gmail.com', 'DOSQ', 'dp', 3000000, '', '2026-05-16 18:05:46'),
(15, 'MF-F4108C', 3, '2026-05-20', '11,12,13', 'KHAYR NOUREDINE YUNUS', '+6287888907879', 'khayrnouredineyunus@gmail.com', 'Kozu', 'dp', 3000000, '', '2026-05-18 03:11:32'),
(16, 'MF-452886', 2, '2026-05-20', '23', 'KHAYR NOUREDINE YUNUS', '+6287888907879', 'khayrnouredineyunus@gmail.com', 'KOJU', 'dp', 1200000, '', '2026-05-18 12:17:21'),
(17, 'MF-DEB795', 3, '2026-05-19', '9,10,11,12,13,14,15,16,17,18,19,20,21,22,23', 'KHAYR NOUREDINE YUNUS', '+6287888907879', 'khayrnouredineyunus@gmail.com', 'SULTAN PRIOK', 'lunas', 15000000, '', '2026-05-19 01:41:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal`
--

CREATE TABLE `jadwal` (
  `ID_JADWAL` int(11) NOT NULL,
  `ID_LAPANGAN` int(11) DEFAULT NULL,
  `TANGGAL` date DEFAULT NULL,
  `JAM_MULAI` varchar(255) DEFAULT NULL,
  `JAM_SELESAI` varchar(255) DEFAULT NULL,
  `STATUS_JADWAL` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwal`
--

INSERT INTO `jadwal` (`ID_JADWAL`, `ID_LAPANGAN`, `TANGGAL`, `JAM_MULAI`, `JAM_SELESAI`, `STATUS_JADWAL`) VALUES
(1, 2, '2026-05-22', '10,11', '11,12', 'TIDAK'),
(2, 1, '2026-05-31', '15,16,17', '16,17,18', 'TIDAK'),
(3, 2, '2026-05-31', '20,21,22,23', '21,22,23,24', 'TIDAK'),
(4, 2, '2026-05-31', '15,16,17,18,19', '16,17,18,19,20', 'TIDAK'),
(5, 1, '2026-05-31', '18,19,20,21,22', '19,20,21,22,23', 'TIDAK'),
(6, 3, '2026-05-31', '19,20,21,22', '20,21,22,23', 'TIDAK'),
(7, 2, '2026-06-02', '19,20,21,22', '20,21,22,23', 'TIDAK'),
(8, 3, '2026-05-31', '16,17,18', '17,18,19', 'TIDAK'),
(9, 2, '2026-06-01', '17,18,19,20,21,22', '18,19,20,21,22,23', 'TIDAK'),
(10, 2, '2026-07-30', '10,11,12,13,14,15,16,17,18,19,20,21,22,23', '11,12,13,14,15,16,17,18,19,20,21,22,23,24', 'TIDAK'),
(11, 3, '2026-05-31', '10,11', '11,12', 'TIDAK'),
(12, 3, '2026-05-31', '23', '24', 'TIDAK'),
(14, 2, '2026-06-17', '8', '10', 'TIDAK'),
(17, 2, '2026-06-21', '12', '16', 'TIDAK'),
(18, 2, '2026-06-30', '16,17,18', '17,18,19', 'TIDAK'),
(19, 3, '2026-06-18', '22,23', '23,24', 'TIDAK'),
(20, 3, '2026-06-14', '15,16,17', '16,17,18', 'TIDAK');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lapangan`
--

CREATE TABLE `lapangan` (
  `ID_LAPANGAN` int(11) NOT NULL,
  `NAMA_LAPANGAN` varchar(100) DEFAULT NULL,
  `JENIS_LAPANGAN` varchar(50) DEFAULT NULL,
  `HARGA_PER_JAM` int(11) DEFAULT NULL,
  `STATUS_LAPANGAN` varchar(20) DEFAULT NULL,
  `FOTO` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `lapangan`
--

INSERT INTO `lapangan` (`ID_LAPANGAN`, `NAMA_LAPANGAN`, `JENIS_LAPANGAN`, `HARGA_PER_JAM`, `STATUS_LAPANGAN`, `FOTO`) VALUES
(1, 'Lapangan 1', 'Rumput Sintetis Pro', 1000000, 'TERSEDIA', 'lapangan_6a2e11f826317.jpg'),
(2, 'Lapangan 2', 'Rumput Sintetis Premium', 1200000, 'TERSEDIA', 'lapangan_6a2e11bb739db.jpg'),
(3, 'Lapangan 3', 'Rumput Sintetis Elite', 1000000, 'TERSEDIA', 'lapangan_6a2e120b12332.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `ID_PELANGGAN` int(11) NOT NULL,
  `U_NAMA` varchar(100) DEFAULT NULL,
  `U_EMAIL` varchar(100) DEFAULT NULL,
  `U_PASSWORD` varchar(255) DEFAULT NULL,
  `U_NOTELP` varchar(20) DEFAULT NULL,
  `FOTO_PROFIL` varchar(255) DEFAULT NULL,
  `FOTO` varchar(255) DEFAULT NULL,
  `SOSMED_INSTAGRAM` varchar(100) DEFAULT NULL,
  `SOSMED_TWITTER` varchar(100) DEFAULT NULL,
  `SOSMED_TIKTOK` varchar(100) DEFAULT NULL,
  `SOSMED_FACEBOOK` varchar(100) DEFAULT NULL,
  `SOSMED_YOUTUBE` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`ID_PELANGGAN`, `U_NAMA`, `U_EMAIL`, `U_PASSWORD`, `U_NOTELP`, `FOTO_PROFIL`, `FOTO`, `SOSMED_INSTAGRAM`, `SOSMED_TWITTER`, `SOSMED_TIKTOK`, `SOSMED_FACEBOOK`, `SOSMED_YOUTUBE`) VALUES
(1, 'Khayr Nouredine Yunus', 'khayrnouredineyunus@gmail.com', '$2y$10$zgkrl/pbK2vZZ3hzCRb0Mej2r/EsZ9vjD9eZefrGoTdSmnSWlGWD.', '+6287888907879', NULL, NULL, 'khayr.ny', '', '', '', NULL),
(2, 'Koju', 'onlyonekozu@gmail.com', '$2y$10$DqIT6ZIACdE1ukWe7J8PauhkIgVXeAvVu5NRzVy3vPDf40jAUREhi', '087888907878', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Cristiano Ronaldo', 'ronaldo7@gmail.com', '$2y$10$D42TlAS.YX7km8.cdm05uOmkjGtsSFXotMey18eq5xDwUrsR4AB9K', '080987654321', 'profil_6a1bcda10e5a1.jpeg', NULL, 'cristiano', '', '', '', NULL),
(4, 'Budi Santoso', 'budisan90@gmail.com', '$2y$10$oDSncGs22FTZnBwAZ1OnEui9ZZj4UN2KqpKna9kCQeMvR/P6LUhDG', '087890909090', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'Bruno', 'brunogoat@gmail.com', '$2y$10$4GeRMX4BzJ6MFdOEGSo.Be8qin.vWaobUM.O8TPThIrXxbdkkHnDS', '087888080808', 'profil_6a1c20ca02ff3.jpeg', NULL, 'brunofernandes9', 'bruno', '', '', ''),
(6, 'Ramos Pelangi', 'ramospelangi@gmail.com', '$2y$10$cKPzPAXlmzCS.BtaWQizZ.3zQA9a1GPCmSxHVW3yTN9qIewKJK4ky', '080987654312', NULL, NULL, '', '', '', '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `ID_PEMBAYARAN` int(11) NOT NULL,
  `ID_BOOKING` varchar(50) DEFAULT NULL,
  `TANGGAL_BAYAR` datetime DEFAULT NULL,
  `METODE_PEMBAYARAN` varchar(50) DEFAULT NULL,
  `STATUS_PEMBAYARAN` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`ID_PEMBAYARAN`, `ID_BOOKING`, `TANGGAL_BAYAR`, `METODE_PEMBAYARAN`, `STATUS_PEMBAYARAN`) VALUES
(1, 'MF-720236', '2026-05-31 09:21:33', 'TRANSFER', 'LUNAS'),
(2, 'MF-C83A1E', '2026-06-01 06:04:51', 'TRANSFER', 'LUNAS'),
(3, 'MF-1E7A34', '2026-06-01 06:04:48', 'TRANSFER', 'LUNAS'),
(4, 'MF-7B4623', '2026-05-31 08:52:47', 'TRANSFER', 'LUNAS'),
(5, 'MF-BD031F', '2026-05-31 08:52:49', 'TRANSFER', 'LUNAS'),
(6, 'MF-868961', '2026-05-31 08:52:51', 'TRANSFER', 'LUNAS'),
(7, 'MF-86AE25', '2026-05-31 08:52:53', 'TRANSFER', 'LUNAS'),
(8, 'MF-ADF647', '2026-05-31 08:52:55', 'TRANSFER', 'LUNAS'),
(9, 'MF-91F976', '2026-05-31 08:52:58', 'TRANSFER', 'LUNAS'),
(10, 'MF-4F3E9B', '2026-05-31 08:52:59', 'TRANSFER', 'LUNAS'),
(11, 'MF-030AF8', '2026-05-31 08:53:01', 'TRANSFER', 'LUNAS'),
(12, 'MF-521EE6', '2026-05-31 14:54:40', 'GOPAY', 'LUNAS'),
(14, 'MF-9B2ABE', '2026-06-01 11:17:29', 'TRANSFER', 'PENDING'),
(15, 'MF-43CDCB', '2026-06-13 23:56:31', 'TRANSFER', 'PENDING'),
(16, 'MF-3484A0', '2026-06-14 03:20:32', 'TRANSFER', 'PENDING');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID_ADMIN`),
  ADD UNIQUE KEY `USERNAME` (`USERNAME`);

--
-- Indeks untuk tabel `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`ID_BOOKING`),
  ADD KEY `ID_PELANGGAN` (`ID_PELANGGAN`),
  ADD KEY `ID_JADWAL` (`ID_JADWAL`);

--
-- Indeks untuk tabel `bookings_lama`
--
ALTER TABLE `bookings_lama`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`ID_JADWAL`),
  ADD KEY `ID_LAPANGAN` (`ID_LAPANGAN`);

--
-- Indeks untuk tabel `lapangan`
--
ALTER TABLE `lapangan`
  ADD PRIMARY KEY (`ID_LAPANGAN`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`ID_PELANGGAN`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`ID_PEMBAYARAN`),
  ADD KEY `ID_BOOKING` (`ID_BOOKING`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `ID_ADMIN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `bookings_lama`
--
ALTER TABLE `bookings_lama`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `ID_JADWAL` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `ID_PELANGGAN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `ID_PEMBAYARAN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`ID_PELANGGAN`) REFERENCES `pelanggan` (`ID_PELANGGAN`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`ID_JADWAL`) REFERENCES `jadwal` (`ID_JADWAL`);

--
-- Ketidakleluasaan untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`ID_LAPANGAN`) REFERENCES `lapangan` (`ID_LAPANGAN`);

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`ID_BOOKING`) REFERENCES `booking` (`ID_BOOKING`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
