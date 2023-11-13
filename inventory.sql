-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2023 at 06:18 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int(11) NOT NULL,
  `idb` varchar(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `qty` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `idb`, `nama_barang`, `qty`, `sku`, `note`) VALUES
(1, '7k2suic6', 'tepung rose brand', '30', '4343434', ''),
(2, 'r150bn40', 'minyak babi magenta', '97', '8896673', ''),
(3, 'l4vqxyb3', 'tepung kanji', '23', 'trtrrtrt', ''),
(4, '1ob6x1pa', 'tepung kanji', '23', 'trtrrtrt', '');

-- --------------------------------------------------------

--
-- Table structure for table `barang_keluar`
--

CREATE TABLE `barang_keluar` (
  `id` int(11) NOT NULL,
  `tanggal` varchar(255) NOT NULL,
  `id_rec` varchar(255) NOT NULL,
  `idb` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `qty` varchar(255) NOT NULL,
  `tujuan` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang_keluar`
--

INSERT INTO `barang_keluar` (`id`, `tanggal`, `id_rec`, `idb`, `sku`, `nama_barang`, `qty`, `tujuan`, `note`) VALUES
(2, '23-Feb-2023', 'xau55fke', '7k2suic6', '4343434', 'tepung rose brand', '20', '2323', '');

-- --------------------------------------------------------

--
-- Table structure for table `kodeakseshapus`
--

CREATE TABLE `kodeakseshapus` (
  `id` int(11) NOT NULL,
  `kodeakses` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kodeakseshapus`
--

INSERT INTO `kodeakseshapus` (`id`, `kodeakses`) VALUES
(1, 223);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idb` (`idb`);

--
-- Indexes for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_rec` (`id_rec`);

--
-- Indexes for table `kodeakseshapus`
--
ALTER TABLE `kodeakseshapus`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kodeakseshapus`
--
ALTER TABLE `kodeakseshapus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
