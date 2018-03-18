-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2018 at 07:02 AM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sipsman1c`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `id` varchar(18) NOT NULL,
  `password` varchar(20) NOT NULL,
  `level` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`id`, `password`, `level`) VALUES
('10123', '10123', 'anggota'),
('10124', '10124', 'anggota'),
('10456', '10456', 'anggota'),
('10567', '10567', 'anggota'),
('10619', '10619', 'anggota'),
('10654', '10654', 'anggota'),
('10666', '10666', 'anggota'),
('10765', '10765', 'anggota'),
('10777', '10777', 'anggota'),
('10876', '10876', 'anggota'),
('10987', '10987', 'anggota'),
('11567', '11567', 'anggota'),
('admin', 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `no_induk` varchar(18) NOT NULL,
  `nama` varchar(40) NOT NULL,
  `alamat` varchar(40) NOT NULL,
  `email` varchar(30) NOT NULL,
  `telepon` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `anggota`
--

INSERT INTO `anggota` (`no_induk`, `nama`, `alamat`, `email`, `telepon`) VALUES
('10123', 'Zubaidah', 'Jati', 'zub@gmail.com', '085765432111'),
('10124', 'Zumala', 'Undaan', 'zum@gmail.com', '08924314352'),
('10456', 'Nur Falah', 'Kalirejo Ungaran', 'falahnur@gmail.com', '087864154345'),
('10567', 'Ahmad Fathoni', 'Kudus', 'ahmad@gmail.com', '081234521312'),
('10619', 'Rey Mysterio', 'Mexico City', 'rey.mysterio@wwe.com', '081234567890'),
('10654', 'Cava Billa', 'Kudus', 'cavabilla@gmail.com', '08978978676'),
('10666', 'Laatansa', 'Jepangpakis', 'laatansaslowmotion@gmail.com', '085225754909'),
('10765', 'Mahmud Abbas', 'Pati', 'mahmud@gmail.com', '085121412432'),
('10777', 'Fadilla', 'Semarang', 'fadil@gmail.com', '085674253154'),
('10876', 'Nico Weliyanto', 'Pati', 'niconico@gmail.com', '08978776564'),
('10987', 'Daru Namus', 'Jepangpakis', 'darunamus@gmail.com', '085225362789'),
('11567', 'Alif Hidayatullah', 'Surabaya', 'alifheat@gmail.com', '081234123556');

-- --------------------------------------------------------

--
-- Table structure for table `aturan`
--

CREATE TABLE `aturan` (
  `id_aturan` tinyint(3) UNSIGNED NOT NULL,
  `denda` mediumint(8) UNSIGNED NOT NULL,
  `durasi` tinyint(3) UNSIGNED NOT NULL,
  `maksimal_pinjam` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `aturan`
--

INSERT INTO `aturan` (`id_aturan`, `denda`, `durasi`, `maksimal_pinjam`) VALUES
(1, 500, 7, 3);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `kode_klasifikasi` varchar(7) NOT NULL,
  `nama_kategori` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`kode_klasifikasi`, `nama_kategori`) VALUES
('210', 'Filosofi dan teori agama'),
('335', 'Sosialisme dan sistem terkait'),
('570', 'Biologi'),
('576', 'Genetik dan evolusi');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `kode_transaksi` varchar(10) NOT NULL,
  `nomor_panggil` varchar(16) NOT NULL,
  `no_induk` varchar(18) NOT NULL,
  `tanggal_pinjam` varchar(11) NOT NULL,
  `tanggal_kembali` varchar(18) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`kode_transaksi`, `nomor_panggil`, `no_induk`, `tanggal_pinjam`, `tanggal_kembali`) VALUES
('180201-001', '576-Cha-t.1', '10987', '01 Feb 2018', 'Belum dikembalikan'),
('180214-001', '210-Fri-b.1', '10123', '14 Feb 2018', '02 Mar 2018'),
('180215-001', '335-Tan-m.1', '10456', '15 Feb 2018', '15 Mar 2018'),
('180301-001', '210-Fri-b.1', '10666', '01 Mar 2018', 'Belum dikembalikan'),
('180304-001', '335-Tan-m.1', '10777', '04 Mar 2018', '05 Mar 2018'),
('180306-001', '335-Tan-m.1', '10124', '06 Mar 2018', '15 Mar 2018'),
('180315-001', '210-Fri-b.1', '10123', '15 Mar 2018', 'Belum dikembalikan'),
('180315-002', '210-Fri-b.1', '10666', '15 Mar 2018', 'Belum dikembalikan'),
('180315-003', '576-Cha-t.1', '10619', '15 Mar 2018', '15 Mar 2018'),
('180315-004', '576-Cha-t.2', '10777', '15 Mar 2018', 'Belum dikembalikan'),
('180318-001', '335-Tan-m.1', '10777', '18 Mar 2018', '18 Mar 2018'),
('180318-002', '335-Tan-m.1', '10777', '18 Mar 2018', 'Belum dikembalikan'),
('180318-003', '335-Tan-m.1', '10777', '18 Mar 2018', 'Belum dikembalikan');

-- --------------------------------------------------------

--
-- Table structure for table `pustaka`
--

CREATE TABLE `pustaka` (
  `nomor_panggil` varchar(16) NOT NULL,
  `isbn` varchar(13) NOT NULL,
  `kode_klasifikasi` varchar(7) NOT NULL,
  `judul` varchar(250) NOT NULL,
  `pengarang` varchar(50) NOT NULL,
  `penerbit` varchar(50) NOT NULL,
  `kota_terbit` varchar(50) NOT NULL,
  `tahun_terbit` varchar(4) NOT NULL,
  `sampul` varchar(200) NOT NULL,
  `jumlah_pustaka` tinyint(3) UNSIGNED NOT NULL,
  `jumlah_dipinjam` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pustaka`
--

INSERT INTO `pustaka` (`nomor_panggil`, `isbn`, `kode_klasifikasi`, `judul`, `pengarang`, `penerbit`, `kota_terbit`, `tahun_terbit`, `sampul`, `jumlah_pustaka`, `jumlah_dipinjam`) VALUES
('210-Fri-b.1', '9780679724650', '210', 'Beyond Good & Evil: Prelude to a Philosophy of the Future', 'Friedrich Nietzche', 'Vintage Publisher', 'New York', '1989', 'assets/cover/210-Fri-b_11.jpg', 4, 4),
('335-Tan-m.1', '9789791683746', '335', 'Madilog Tan Malaka Edisi Terbaru (Hard Cover)', 'Tan Malaka', 'Pustaka Narasi', 'Yogyakarta', '2014', 'assets/cover/335-Tan-m_1.jpg', 3, 3),
('576-Cha-t.1', '9780451529060', '576', 'The Origin of Species: 150th Anniversary Edition', 'Charles Darwin', 'Signet', 'New York', '2003', 'assets/cover/576-Cha-t_1.jpg', 3, 1),
('576-Cha-t.2', '9781108005487', '576', 'The Origin of Species: By Means of Natural Selection, or the Preservation of Favoured Races in the Struggle for Life (Cambridge Library Collection - Darwin, Evolution and Genetics)', 'Charles Darwin', 'Cambridge University Press', 'Cambridge', '2009', 'assets/cover/576-Cha-t_2.jpg', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`no_induk`);

--
-- Indexes for table `aturan`
--
ALTER TABLE `aturan`
  ADD PRIMARY KEY (`id_aturan`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`kode_klasifikasi`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`kode_transaksi`);

--
-- Indexes for table `pustaka`
--
ALTER TABLE `pustaka`
  ADD PRIMARY KEY (`nomor_panggil`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
