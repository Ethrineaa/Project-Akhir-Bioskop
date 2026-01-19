-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 19, 2026 at 07:55 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bioskop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `films`
--

CREATE TABLE `films` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sinopsis` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `durasi` int NOT NULL,
  `harga` int NOT NULL,
  `genre_id` bigint UNSIGNED NOT NULL,
  `poster` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `films`
--

INSERT INTO `films` (`id`, `judul`, `sinopsis`, `durasi`, `harga`, `genre_id`, `poster`, `created_at`, `updated_at`) VALUES
(1, 'Spiderman: No Way Home', 'Spider-Man: No Way Home mengisahkan Peter Parker yang kehidupannya kacau setelah identitasnya sebagai Spider-Man terbongkar ke seluruh dunia. Berusaha mengembalikan kehidupannya seperti semula, Peter meminta bantuan Doctor Strange untuk membuat mantra agar semua orang melupakan identitasnya. Namun, mantra tersebut gagal dan membuka multiverse, membawa musuh-musuh Spider-Man dari dimensi lain ke dunianya. Peter pun harus menghadapi ancaman besar ini sambil belajar arti pengorbanan sejati, tanggung jawab, dan konsekuensi dari setiap pilihan yang ia buat.', 40, 20000, 1, '1768740277.jpg', '2026-01-18 05:44:37', '2026-01-18 05:44:37');

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'Action', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(2, 'Adventure', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(3, 'Animation', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(4, 'Comedy', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(5, 'Drama', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(6, 'Fantasy', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(7, 'Horror', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(8, 'Mystery', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(9, 'Romance', '2026-01-18 01:03:03', '2026-01-18 01:03:03');

-- --------------------------------------------------------

--
-- Table structure for table `jadwals`
--

CREATE TABLE `jadwals` (
  `id` bigint UNSIGNED NOT NULL,
  `film_id` bigint UNSIGNED NOT NULL,
  `studio_id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwals`
--

INSERT INTO `jadwals` (`id`, `film_id`, `studio_id`, `tanggal`, `jam`, `created_at`, `updated_at`) VALUES
(1, 1, 3, '2026-01-21', '01:00:00', '2026-01-18 05:56:36', '2026-01-18 05:56:36');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kursis`
--

CREATE TABLE `kursis` (
  `id` bigint UNSIGNED NOT NULL,
  `studio_id` bigint UNSIGNED NOT NULL,
  `nomor_kursi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kursis`
--

INSERT INTO `kursis` (`id`, `studio_id`, `nomor_kursi`, `created_at`, `updated_at`) VALUES
(1, 1, 'A1', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(2, 1, 'A2', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(3, 1, 'A3', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(4, 1, 'A4', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(5, 1, 'A5', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(6, 1, 'A6', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(7, 1, 'A7', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(8, 1, 'A8', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(9, 1, 'A9', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(10, 1, 'A10', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(11, 1, 'B1', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(12, 1, 'B2', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(13, 1, 'B3', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(14, 1, 'B4', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(15, 1, 'B5', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(16, 1, 'B6', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(17, 1, 'B7', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(18, 1, 'B8', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(19, 1, 'B9', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(20, 1, 'B10', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(21, 1, 'C1', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(22, 1, 'C2', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(23, 1, 'C3', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(24, 1, 'C4', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(25, 1, 'C5', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(26, 1, 'C6', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(27, 1, 'C7', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(28, 1, 'C8', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(29, 1, 'C9', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(30, 1, 'C10', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(31, 1, 'D1', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(32, 1, 'D2', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(33, 1, 'D3', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(34, 1, 'D4', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(35, 1, 'D5', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(36, 1, 'D6', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(37, 1, 'D7', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(38, 1, 'D8', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(39, 1, 'D9', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(40, 1, 'D10', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(41, 1, 'E1', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(42, 1, 'E2', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(43, 1, 'E3', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(44, 1, 'E4', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(45, 1, 'E5', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(46, 1, 'E6', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(47, 1, 'E7', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(48, 1, 'E8', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(49, 1, 'E9', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(50, 1, 'E10', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(51, 2, 'A1', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(52, 2, 'A2', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(53, 2, 'A3', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(54, 2, 'A4', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(55, 2, 'A5', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(56, 2, 'A6', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(57, 2, 'A7', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(58, 2, 'A8', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(59, 2, 'A9', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(60, 2, 'A10', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(61, 2, 'A11', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(62, 2, 'A12', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(63, 2, 'A13', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(64, 2, 'A14', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(65, 2, 'A15', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(66, 2, 'B1', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(67, 2, 'B2', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(68, 2, 'B3', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(69, 2, 'B4', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(70, 2, 'B5', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(71, 2, 'B6', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(72, 2, 'B7', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(73, 2, 'B8', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(74, 2, 'B9', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(75, 2, 'B10', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(76, 2, 'B11', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(77, 2, 'B12', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(78, 2, 'B13', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(79, 2, 'B14', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(80, 2, 'B15', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(81, 2, 'C1', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(82, 2, 'C2', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(83, 2, 'C3', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(84, 2, 'C4', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(85, 2, 'C5', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(86, 2, 'C6', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(87, 2, 'C7', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(88, 2, 'C8', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(89, 2, 'C9', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(90, 2, 'C10', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(91, 2, 'C11', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(92, 2, 'C12', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(93, 2, 'C13', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(94, 2, 'C14', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(95, 2, 'C15', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(96, 2, 'D1', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(97, 2, 'D2', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(98, 2, 'D3', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(99, 2, 'D4', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(100, 2, 'D5', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(101, 2, 'D6', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(102, 2, 'D7', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(103, 2, 'D8', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(104, 2, 'D9', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(105, 2, 'D10', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(106, 2, 'D11', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(107, 2, 'D12', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(108, 2, 'D13', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(109, 2, 'D14', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(110, 2, 'D15', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(111, 2, 'E1', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(112, 2, 'E2', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(113, 2, 'E3', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(114, 2, 'E4', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(115, 2, 'E5', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(116, 2, 'E6', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(117, 2, 'E7', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(118, 2, 'E8', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(119, 2, 'E9', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(120, 2, 'E10', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(121, 2, 'E11', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(122, 2, 'E12', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(123, 2, 'E13', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(124, 2, 'E14', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(125, 2, 'E15', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(126, 3, 'A1', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(127, 3, 'A2', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(128, 3, 'A3', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(129, 3, 'A4', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(130, 3, 'A5', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(131, 3, 'A6', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(132, 3, 'A7', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(133, 3, 'A8', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(134, 3, 'A9', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(135, 3, 'A10', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(136, 3, 'B1', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(137, 3, 'B2', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(138, 3, 'B3', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(139, 3, 'B4', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(140, 3, 'B5', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(141, 3, 'B6', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(142, 3, 'B7', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(143, 3, 'B8', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(144, 3, 'B9', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(145, 3, 'B10', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(146, 3, 'C1', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(147, 3, 'C2', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(148, 3, 'C3', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(149, 3, 'C4', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(150, 3, 'C5', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(151, 3, 'C6', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(152, 3, 'C7', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(153, 3, 'C8', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(154, 3, 'C9', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(155, 3, 'C10', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(156, 3, 'D1', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(157, 3, 'D2', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(158, 3, 'D3', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(159, 3, 'D4', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(160, 3, 'D5', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(161, 3, 'D6', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(162, 3, 'D7', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(163, 3, 'D8', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(164, 3, 'D9', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(165, 3, 'D10', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(166, 3, 'E1', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(167, 3, 'E2', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(168, 3, 'E3', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(169, 3, 'E4', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(170, 3, 'E5', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(171, 3, 'E6', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(172, 3, 'E7', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(173, 3, 'E8', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(174, 3, 'E9', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(175, 3, 'E10', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(176, 3, 'F1', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(177, 3, 'F2', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(178, 3, 'F3', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(179, 3, 'F4', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(180, 3, 'F5', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(181, 3, 'F6', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(182, 3, 'F7', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(183, 3, 'F8', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(184, 3, 'F9', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(185, 3, 'F10', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(186, 3, 'G1', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(187, 3, 'G2', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(188, 3, 'G3', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(189, 3, 'G4', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(190, 3, 'G5', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(191, 3, 'G6', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(192, 3, 'G7', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(193, 3, 'G8', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(194, 3, 'G9', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(195, 3, 'G10', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(196, 3, 'H1', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(197, 3, 'H2', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(198, 3, 'H3', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(199, 3, 'H4', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(200, 3, 'H5', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(201, 3, 'H6', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(202, 3, 'H7', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(203, 3, 'H8', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(204, 3, 'H9', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(205, 3, 'H10', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(206, 3, 'I1', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(207, 3, 'I2', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(208, 3, 'I3', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(209, 3, 'I4', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(210, 3, 'I5', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(211, 3, 'I6', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(212, 3, 'I7', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(213, 3, 'I8', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(214, 3, 'I9', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(215, 3, 'I10', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(216, 3, 'J1', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(217, 3, 'J2', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(218, 3, 'J3', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(219, 3, 'J4', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(220, 3, 'J5', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(221, 3, 'J6', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(222, 3, 'J7', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(223, 3, 'J8', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(224, 3, 'J9', '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(225, 3, 'J10', '2026-01-18 01:03:03', '2026-01-18 01:03:03');

-- --------------------------------------------------------

--
-- Table structure for table `kursi_pemesanan`
--

CREATE TABLE `kursi_pemesanan` (
  `id` bigint UNSIGNED NOT NULL,
  `pemesanan_id` bigint UNSIGNED NOT NULL,
  `kursi_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kursi_pemesanan`
--

INSERT INTO `kursi_pemesanan` (`id`, `pemesanan_id`, `kursi_id`, `created_at`, `updated_at`) VALUES
(1, 1, 171, '2026-01-18 06:11:12', '2026-01-18 06:11:12'),
(2, 2, 160, '2026-01-18 18:16:04', '2026-01-18 18:16:04'),
(3, 3, 187, '2026-01-18 18:18:53', '2026-01-18 18:18:53'),
(4, 3, 188, '2026-01-18 18:18:53', '2026-01-18 18:18:53');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_20_065152_create_genres_table', 1),
(5, '2025_11_20_065159_create_films_table', 1),
(6, '2025_11_20_065206_create_studios_table', 1),
(7, '2025_11_20_065213_create_kursis_table', 1),
(8, '2025_11_20_065220_create_jadwals_table', 1),
(9, '2025_11_20_065247_create_pemesanans_table', 1),
(10, '2025_11_20_065256_create_pembayaran_table', 1),
(11, '2025_11_20_065306_create_kursi_pemesanan_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` bigint UNSIGNED NOT NULL,
  `pemesanan_id` bigint UNSIGNED NOT NULL,
  `bukti_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('waiting','pending','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `pemesanan_id`, `bukti_pembayaran`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'paid', '2026-01-18 06:11:12', '2026-01-18 06:11:39'),
(2, 2, NULL, 'paid', '2026-01-18 18:16:04', '2026-01-18 18:16:32'),
(3, 3, NULL, 'paid', '2026-01-18 18:18:53', '2026-01-18 18:19:17');

-- --------------------------------------------------------

--
-- Table structure for table `pemesanans`
--

CREATE TABLE `pemesanans` (
  `id` bigint UNSIGNED NOT NULL,
  `jadwal_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `jumlah_tiket` int NOT NULL,
  `total_harga` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pemesanans`
--

INSERT INTO `pemesanans` (`id`, `jadwal_id`, `user_id`, `jumlah_tiket`, `total_harga`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 20000, '2026-01-18 06:11:12', '2026-01-18 06:11:12'),
(2, 1, 2, 1, 20000, '2026-01-18 18:16:04', '2026-01-18 18:16:04'),
(3, 1, 2, 2, 40000, '2026-01-18 18:18:53', '2026-01-18 18:18:53');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `studios`
--

CREATE TABLE `studios` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kapasitas` int NOT NULL,
  `layout` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `studios`
--

INSERT INTO `studios` (`id`, `nama`, `kapasitas`, `layout`, `created_at`, `updated_at`) VALUES
(1, 'Studio 1', 50, NULL, '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(2, 'Studio 2', 75, NULL, '2026-01-18 01:03:03', '2026-01-18 01:03:03'),
(3, 'Studio 3', 100, NULL, '2026-01-18 01:03:03', '2026-01-18 01:03:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `last_view_payment` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `last_view_payment`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', NULL, '$2y$12$PAEexwu33eFdMpF.dwhYDeswpvgv3Ut7rnhNGEc0v8d1tFRpkDK8S', 'admin', NULL, NULL, '2026-01-18 01:03:02', '2026-01-18 01:03:02'),
(2, 'Hafidz', 'hafidz@gmail.com', NULL, '$2y$12$pkK0li4E4fF9TUNBn.8uXedmjw1f7osE0SN1uz7VRYCfKb8cZyJNW', 'user', '2026-01-18 18:41:30', NULL, '2026-01-18 01:03:02', '2026-01-18 18:41:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `films`
--
ALTER TABLE `films`
  ADD PRIMARY KEY (`id`),
  ADD KEY `films_genre_id_foreign` (`genre_id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwals`
--
ALTER TABLE `jadwals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwals_film_id_foreign` (`film_id`),
  ADD KEY `jadwals_studio_id_foreign` (`studio_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kursis`
--
ALTER TABLE `kursis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kursis_studio_id_foreign` (`studio_id`);

--
-- Indexes for table `kursi_pemesanan`
--
ALTER TABLE `kursi_pemesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kursi_pemesanan_pemesanan_id_foreign` (`pemesanan_id`),
  ADD KEY `kursi_pemesanan_kursi_id_foreign` (`kursi_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembayaran_pemesanan_id_foreign` (`pemesanan_id`);

--
-- Indexes for table `pemesanans`
--
ALTER TABLE `pemesanans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pemesanans_jadwal_id_foreign` (`jadwal_id`),
  ADD KEY `pemesanans_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `studios`
--
ALTER TABLE `studios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `films`
--
ALTER TABLE `films`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `jadwals`
--
ALTER TABLE `jadwals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kursis`
--
ALTER TABLE `kursis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- AUTO_INCREMENT for table `kursi_pemesanan`
--
ALTER TABLE `kursi_pemesanan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pemesanans`
--
ALTER TABLE `pemesanans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `studios`
--
ALTER TABLE `studios`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `films`
--
ALTER TABLE `films`
  ADD CONSTRAINT `films_genre_id_foreign` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `jadwals`
--
ALTER TABLE `jadwals`
  ADD CONSTRAINT `jadwals_film_id_foreign` FOREIGN KEY (`film_id`) REFERENCES `films` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `jadwals_studio_id_foreign` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `kursis`
--
ALTER TABLE `kursis`
  ADD CONSTRAINT `kursis_studio_id_foreign` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `kursi_pemesanan`
--
ALTER TABLE `kursi_pemesanan`
  ADD CONSTRAINT `kursi_pemesanan_kursi_id_foreign` FOREIGN KEY (`kursi_id`) REFERENCES `kursis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kursi_pemesanan_pemesanan_id_foreign` FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_pemesanan_id_foreign` FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pemesanans`
--
ALTER TABLE `pemesanans`
  ADD CONSTRAINT `pemesanans_jadwal_id_foreign` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemesanans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
