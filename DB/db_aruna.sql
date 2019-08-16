-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 16 Agu 2019 pada 15.59
-- Versi server: 10.2.26-MariaDB
-- Versi PHP: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pojcityc_aruna`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_update_ubs`
--

CREATE TABLE `t_update_ubs` (
  `IDX` bigint(20) NOT NULL,
  `UPDATE_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `HRG_BELI` text NOT NULL,
  `HRG_JUAL` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `t_update_ubs`
--

INSERT INTO `t_update_ubs` (`IDX`, `UPDATE_AT`, `HRG_BELI`, `HRG_JUAL`) VALUES
(6, '2019-07-26 07:30:02', '706,805', '630,500'),
(7, '2019-07-27 03:50:03', '707,309', '631,000'),
(8, '2019-07-29 03:50:01', '708,318', '632,000'),
(9, '2019-07-30 03:55:02', '709,327', '633,000'),
(10, '2019-07-31 04:00:03', '710,841', '634,500'),
(11, '2019-08-01 01:40:04', '710,841', '626,500'),
(12, '2019-08-01 01:45:03', '710,841', '626,000'),
(13, '2019-08-01 04:00:02', '707,814', '631,500'),
(14, '2019-08-02 03:40:01', '720,022', '643,500'),
(15, '2019-08-03 05:20:02', '721,940', '645,500'),
(16, '2019-08-05 03:40:01', '730,112', '653,600'),
(17, '2019-08-05 03:45:03', '730,112', '653,500'),
(18, '2019-08-06 03:30:02', '740,404', '663,500'),
(19, '2019-08-07 03:40:03', '747,871', '671,000'),
(20, '2019-08-07 03:45:02', '747,669', '665,000'),
(21, '2019-08-07 06:05:02', '748,174', '665,000'),
(22, '2019-08-08 03:35:03', '751,705', '665,000'),
(23, '2019-08-09 04:05:02', '753,723', '665,000'),
(24, '2019-08-10 01:25:02', '753,723', '635,000'),
(25, '2019-08-10 03:40:03', '750,192', '635,000'),
(26, '2019-08-10 05:00:03', '750,192', '660,000'),
(27, '2019-08-10 05:05:01', '750,192', '665,000'),
(28, '2019-08-12 03:40:03', '750,696', '674,000'),
(29, '2019-08-13 03:55:01', '762,300', '675,000'),
(30, '2019-08-13 04:05:05', '762,300', '680,000'),
(31, '2019-08-14 03:55:03', '757,759', '674,000'),
(32, '2019-08-15 03:40:01', '764,822', '682,000'),
(33, '2019-08-16 03:40:02', '763,309', '680,500');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `t_update_ubs`
--
ALTER TABLE `t_update_ubs`
  ADD PRIMARY KEY (`IDX`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `t_update_ubs`
--
ALTER TABLE `t_update_ubs`
  MODIFY `IDX` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
