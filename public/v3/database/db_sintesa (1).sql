-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2020 at 02:05 PM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sintesa`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(200) NOT NULL,
  `kode_barang` varchar(200) NOT NULL,
  `satuan_barang` varchar(200) NOT NULL,
  `harga_barang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `kode_barang`, `satuan_barang`, `harga_barang`) VALUES
(1, 'Cell Number/10*20CM/PVC Foam Board', 'TCD01-00680', 'Each', 1000);

-- --------------------------------------------------------

--
-- Table structure for table `bc_cs`
--

CREATE TABLE `bc_cs` (
  `id_bc_cs` int(11) NOT NULL,
  `nama_vendor` varchar(200) NOT NULL,
  `tanggal` date NOT NULL,
  `npwp` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bc_fabrikasi`
--

CREATE TABLE `bc_fabrikasi` (
  `id_bc_fabrikasi` int(11) NOT NULL,
  `nama_vendor` varchar(200) NOT NULL,
  `tanggal` date NOT NULL,
  `npwp` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bc_fabrikasi`
--

INSERT INTO `bc_fabrikasi` (`id_bc_fabrikasi`, `nama_vendor`, `tanggal`, `npwp`) VALUES
(2, 'CV. SINTESA NIAGA', '2020-11-30', '94.786.928.5-439.00');

-- --------------------------------------------------------

--
-- Table structure for table `cs`
--

CREATE TABLE `cs` (
  `id_cs` int(11) NOT NULL,
  `no_invoice` varchar(200) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cs`
--

INSERT INTO `cs` (`id_cs`, `no_invoice`, `tanggal`) VALUES
(1, '1/TKII/XII/2020', '2020-12-01');

-- --------------------------------------------------------

--
-- Table structure for table `detail_bc_cs`
--

CREATE TABLE `detail_bc_cs` (
  `id_detail_bc_cs` int(11) NOT NULL,
  `id_bc_cs` int(11) NOT NULL,
  `id_cs` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `detail_bc_fabrikasi`
--

CREATE TABLE `detail_bc_fabrikasi` (
  `id_detail_bc_fabrikasi` int(11) NOT NULL,
  `id_bc_fabrikasi` int(11) NOT NULL,
  `id_fabrikasi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail_bc_fabrikasi`
--

INSERT INTO `detail_bc_fabrikasi` (`id_detail_bc_fabrikasi`, `id_bc_fabrikasi`, `id_fabrikasi`) VALUES
(4, 2, 6);

-- --------------------------------------------------------

--
-- Table structure for table `detail_cs`
--

CREATE TABLE `detail_cs` (
  `id_detail_cs` int(11) NOT NULL,
  `id_cs` int(11) NOT NULL,
  `id_detail_po_cs` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(200) NOT NULL,
  `kode_barang` varchar(200) NOT NULL,
  `satuan_barang` varchar(200) NOT NULL,
  `jumlah_barang` int(11) NOT NULL,
  `harga_barang` int(11) NOT NULL,
  `total_harga_barang` int(11) NOT NULL,
  `no_po` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail_cs`
--

INSERT INTO `detail_cs` (`id_detail_cs`, `id_cs`, `id_detail_po_cs`, `id_barang`, `nama_barang`, `kode_barang`, `satuan_barang`, `jumlah_barang`, `harga_barang`, `total_harga_barang`, `no_po`) VALUES
(1, 1, 1, 1, 'Cell Number/10*20CM/PVC Foam Board', 'TCD01-00680', 'Each', 2, 1000, 2000, '12431'),
(2, 1, 2, 1, 'Cell Number/10*20CM/PVC Foam Board', 'TCD01-00680', 'Each', 2, 1000, 2000, '12431');

-- --------------------------------------------------------

--
-- Table structure for table `detail_fabrikasi`
--

CREATE TABLE `detail_fabrikasi` (
  `id_detail_fabrikasi` int(11) NOT NULL,
  `id_fabrikasi` int(11) NOT NULL,
  `id_detail_po_fabrikasi` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(200) NOT NULL,
  `kode_barang` varchar(200) NOT NULL,
  `satuan_barang` varchar(200) NOT NULL,
  `jumlah_barang` int(11) NOT NULL,
  `harga_barang` int(11) NOT NULL,
  `total_harga_barang` int(11) NOT NULL,
  `no_po` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail_fabrikasi`
--

INSERT INTO `detail_fabrikasi` (`id_detail_fabrikasi`, `id_fabrikasi`, `id_detail_po_fabrikasi`, `id_barang`, `nama_barang`, `kode_barang`, `satuan_barang`, `jumlah_barang`, `harga_barang`, `total_harga_barang`, `no_po`) VALUES
(7, 6, 3, 1, 'Cell Number/10*20CM/PVC Foam Board', 'TCD01-00680', 'Each', 3, 1000, 3000, '12345'),
(8, 6, 3, 1, 'Cell Number/10*20CM/PVC Foam Board', 'TCD01-00680', 'Each', 2, 1000, 2000, '12345');

-- --------------------------------------------------------

--
-- Table structure for table `detail_po_cs`
--

CREATE TABLE `detail_po_cs` (
  `id_detail_po_cs` int(11) NOT NULL,
  `id_po_cs` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(200) NOT NULL,
  `kode_barang` varchar(200) NOT NULL,
  `satuan_barang` varchar(200) NOT NULL,
  `jumlah_barang` int(11) NOT NULL,
  `harga_barang` int(11) NOT NULL,
  `total_harga_barang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail_po_cs`
--

INSERT INTO `detail_po_cs` (`id_detail_po_cs`, `id_po_cs`, `id_barang`, `nama_barang`, `kode_barang`, `satuan_barang`, `jumlah_barang`, `harga_barang`, `total_harga_barang`) VALUES
(1, 1, 1, 'Cell Number/10*20CM/PVC Foam Board', 'TCD01-00680', 'Each', -1, 1000, -1000);

-- --------------------------------------------------------

--
-- Table structure for table `detail_po_fabrikasi`
--

CREATE TABLE `detail_po_fabrikasi` (
  `id_detail_po_fabrikasi` int(11) NOT NULL,
  `id_po_fabrikasi` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(200) NOT NULL,
  `kode_barang` varchar(200) NOT NULL,
  `satuan_barang` varchar(200) NOT NULL,
  `jumlah_barang` int(11) NOT NULL,
  `harga_barang` int(11) NOT NULL,
  `total_harga_barang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fabrikasi`
--

CREATE TABLE `fabrikasi` (
  `id_fabrikasi` int(11) NOT NULL,
  `no_invoice` varchar(100) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fabrikasi`
--

INSERT INTO `fabrikasi` (`id_fabrikasi`, `no_invoice`, `tanggal`) VALUES
(6, '1/TKII/XI/2020', '2020-11-30');

-- --------------------------------------------------------

--
-- Table structure for table `po_cs`
--

CREATE TABLE `po_cs` (
  `id_po_cs` int(11) NOT NULL,
  `no_po` int(11) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `po_cs`
--

INSERT INTO `po_cs` (`id_po_cs`, `no_po`, `tanggal`) VALUES
(1, 12431, '2020-12-01');

-- --------------------------------------------------------

--
-- Table structure for table `po_fabrikasi`
--

CREATE TABLE `po_fabrikasi` (
  `id_po_fabrikasi` int(11) NOT NULL,
  `no_po` varchar(10) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `po_fabrikasi`
--

INSERT INTO `po_fabrikasi` (`id_po_fabrikasi`, `no_po`, `tanggal`) VALUES
(2, '12345', '2020-12-01');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_po_cs`
--

CREATE TABLE `riwayat_po_cs` (
  `id_riwayat` int(11) NOT NULL,
  `id_detail_po_cs` int(11) NOT NULL,
  `no_po` varchar(200) NOT NULL,
  `tanggal_po` date NOT NULL,
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(200) NOT NULL,
  `kode_barang` varchar(200) NOT NULL,
  `satuan_barang` varchar(200) NOT NULL,
  `jumlah_barang` int(11) NOT NULL,
  `harga_barang` int(11) NOT NULL,
  `total_harga_barang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `riwayat_po_cs`
--

INSERT INTO `riwayat_po_cs` (`id_riwayat`, `id_detail_po_cs`, `no_po`, `tanggal_po`, `id_barang`, `nama_barang`, `kode_barang`, `satuan_barang`, `jumlah_barang`, `harga_barang`, `total_harga_barang`) VALUES
(1, 2, '12431', '2020-12-01', 1, 'Cell Number/10*20CM/PVC Foam Board', 'TCD01-00680', 'Each', 2, 1000, 2000);

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_po_fabrikasi`
--

CREATE TABLE `riwayat_po_fabrikasi` (
  `id_riwayat` int(11) NOT NULL,
  `id_detail_po_fabrikasi` int(11) NOT NULL,
  `no_po` varchar(200) NOT NULL,
  `tanggal_po` date NOT NULL,
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(200) NOT NULL,
  `kode_barang` varchar(200) NOT NULL,
  `satuan_barang` varchar(200) NOT NULL,
  `jumlah_barang` int(11) NOT NULL,
  `harga_barang` int(11) NOT NULL,
  `total_harga_barang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `riwayat_po_fabrikasi`
--

INSERT INTO `riwayat_po_fabrikasi` (`id_riwayat`, `id_detail_po_fabrikasi`, `no_po`, `tanggal_po`, `id_barang`, `nama_barang`, `kode_barang`, `satuan_barang`, `jumlah_barang`, `harga_barang`, `total_harga_barang`) VALUES
(2, 3, '12345', '2020-12-01', 1, 'Cell Number/10*20CM/PVC Foam Board', 'TCD01-00680', 'Each', 2, 1000, 2000);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `nama` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `role` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama`, `email`, `no_hp`, `role`) VALUES
(1, 'admin', '$2y$10$5VifqomOAsoe39zJDc/GJefzvAwOmvdqMbDeNjocX0piQd5KDOKbS', 'administrator', 'admin@admin.com', '089123123123', 'administrator'),
(2, 'fajar', '$2y$10$5VifqomOAsoe39zJDc/GJefzvAwOmvdqMbDeNjocX0piQd5KDOKbS', 'Fajar', 'fajar@gmail.com', '0891231213', 'fabrikasi'),
(3, 'rama', '$2y$10$5VifqomOAsoe39zJDc/GJefzvAwOmvdqMbDeNjocX0piQd5KDOKbS', 'Rama', 'rama@gmail.com', '0891231', 'supplier');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `bc_cs`
--
ALTER TABLE `bc_cs`
  ADD PRIMARY KEY (`id_bc_cs`);

--
-- Indexes for table `bc_fabrikasi`
--
ALTER TABLE `bc_fabrikasi`
  ADD PRIMARY KEY (`id_bc_fabrikasi`);

--
-- Indexes for table `cs`
--
ALTER TABLE `cs`
  ADD PRIMARY KEY (`id_cs`);

--
-- Indexes for table `detail_bc_cs`
--
ALTER TABLE `detail_bc_cs`
  ADD PRIMARY KEY (`id_detail_bc_cs`);

--
-- Indexes for table `detail_bc_fabrikasi`
--
ALTER TABLE `detail_bc_fabrikasi`
  ADD PRIMARY KEY (`id_detail_bc_fabrikasi`);

--
-- Indexes for table `detail_cs`
--
ALTER TABLE `detail_cs`
  ADD PRIMARY KEY (`id_detail_cs`);

--
-- Indexes for table `detail_fabrikasi`
--
ALTER TABLE `detail_fabrikasi`
  ADD PRIMARY KEY (`id_detail_fabrikasi`);

--
-- Indexes for table `detail_po_cs`
--
ALTER TABLE `detail_po_cs`
  ADD PRIMARY KEY (`id_detail_po_cs`);

--
-- Indexes for table `detail_po_fabrikasi`
--
ALTER TABLE `detail_po_fabrikasi`
  ADD PRIMARY KEY (`id_detail_po_fabrikasi`);

--
-- Indexes for table `fabrikasi`
--
ALTER TABLE `fabrikasi`
  ADD PRIMARY KEY (`id_fabrikasi`);

--
-- Indexes for table `po_cs`
--
ALTER TABLE `po_cs`
  ADD PRIMARY KEY (`id_po_cs`);

--
-- Indexes for table `po_fabrikasi`
--
ALTER TABLE `po_fabrikasi`
  ADD PRIMARY KEY (`id_po_fabrikasi`);

--
-- Indexes for table `riwayat_po_cs`
--
ALTER TABLE `riwayat_po_cs`
  ADD PRIMARY KEY (`id_riwayat`);

--
-- Indexes for table `riwayat_po_fabrikasi`
--
ALTER TABLE `riwayat_po_fabrikasi`
  ADD PRIMARY KEY (`id_riwayat`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bc_cs`
--
ALTER TABLE `bc_cs`
  MODIFY `id_bc_cs` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bc_fabrikasi`
--
ALTER TABLE `bc_fabrikasi`
  MODIFY `id_bc_fabrikasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cs`
--
ALTER TABLE `cs`
  MODIFY `id_cs` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `detail_bc_cs`
--
ALTER TABLE `detail_bc_cs`
  MODIFY `id_detail_bc_cs` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_bc_fabrikasi`
--
ALTER TABLE `detail_bc_fabrikasi`
  MODIFY `id_detail_bc_fabrikasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `detail_cs`
--
ALTER TABLE `detail_cs`
  MODIFY `id_detail_cs` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detail_fabrikasi`
--
ALTER TABLE `detail_fabrikasi`
  MODIFY `id_detail_fabrikasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `detail_po_cs`
--
ALTER TABLE `detail_po_cs`
  MODIFY `id_detail_po_cs` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detail_po_fabrikasi`
--
ALTER TABLE `detail_po_fabrikasi`
  MODIFY `id_detail_po_fabrikasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fabrikasi`
--
ALTER TABLE `fabrikasi`
  MODIFY `id_fabrikasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `po_cs`
--
ALTER TABLE `po_cs`
  MODIFY `id_po_cs` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `po_fabrikasi`
--
ALTER TABLE `po_fabrikasi`
  MODIFY `id_po_fabrikasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `riwayat_po_cs`
--
ALTER TABLE `riwayat_po_cs`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `riwayat_po_fabrikasi`
--
ALTER TABLE `riwayat_po_fabrikasi`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
