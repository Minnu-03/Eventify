-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 24, 2025 at 08:13 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `event_booking_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `event_id`, `booking_date`) VALUES
(1, 2, 1, '2025-02-22 19:29:01'),
(2, 2, 6, '2025-02-23 12:06:50'),
(3, 2, 8, '2025-02-23 12:06:52'),
(4, 3, 1, '2025-02-23 13:19:28'),
(5, 2, 9, '2025-02-23 15:23:27'),
(16, 2, 10, '2025-02-24 07:07:46');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date` datetime NOT NULL,
  `venue` varchar(255) NOT NULL,
  `available_seats` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `date`, `venue`, `available_seats`, `created_at`) VALUES
(1, 'Concert in the Park', 'An outdoor concert featuring local bands.', '2025-03-15 19:00:00', 'Central Park', 503, '2025-02-22 12:08:23'),
(6, 'Birthday', 'Birthday', '2025-02-28 18:15:00', 'ABC park', 29, '2025-02-22 19:41:15'),
(8, 'Tech Conference', 'Tech Conference', '2025-02-27 16:30:00', 'T-HUB', 19, '2025-02-23 11:59:39'),
(9, 'Wedding', 'Wedding Ceremony', '2025-02-26 18:30:00', 'Wedding Hall', 299, '2025-02-23 15:22:34'),
(10, 'Farewell', 'Farewell Party', '2025-03-01 15:00:00', 'XYZ Hall', 99, '2025-02-23 15:33:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin', 'admin@example.com', 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec', 'admin', '2025-02-21 16:17:46'),
(2, 'John Doe', 'johndoe@example.com', 'cbda1c52153b137c8543bec07a3eccd5a478c73930bbe46167e02abd1faf570bd6651890913a49574c64c70f6924e4075309da7a76b3ae7a94673c47a1086ba5', 'user', '2025-02-21 16:17:46'),
(3, 'John Cena', 'johncena@example.com', '333a488fcdd1de4fe4bc1349c7a094379d1329bfe4e4e4a52122f35ede720f2e281350ed87e755e31c7686bfc1c4e994d69dde9e44985a9def2a578c43732570', 'user', '2025-02-21 16:17:46'),
(6, 'Jam', 'jam@example.com', 'ae5d5c5008afa2b858350528a3bdeb1c20da21185667dbb0e0dae3f8df0fe8df81405a82c2d159d3911c36d0d37122a388ffc5fe1cd246eeea38674ee75e28ff', 'user', '2025-02-23 12:51:08'),
(7, 'Jin', 'jin@example.com', '3033e4fb0beb3dae78f7ffbeb74f371ab0ffbda30a36ee22f154782e4a9654e75236eaf185812c08ba0e0eb9b86b9630d5d6e1495c4ba8e557abaecf3bd51e85', 'user', '2025-02-23 15:24:09'),
(8, 'Jammy', 'jammy@example.com', 'fe91e0964b78b648db99ae856086cbb64d7ac4e18f9c1cc07901eb6468001993dabf4459fa3ce751d3e2ef89ecf23490ad0d53170a70f9f915096da62ba26eb7', 'user', '2025-02-23 15:34:57'),
(9, 'raji', 'raji@example.com', '3b9f2aed269172a777e81f539c5f5d536af2822c34d9940fb0f829dd8423c665a73f636ada42557b63af369dd62e7dbf00a9acba4e85f8798ff6f42ced96a4f1', 'user', '2025-02-24 06:10:23'),
(10, 'Sam', 'sam@gmail.com', 'aa9a88785afb81fcb66da5b86d0aaf543dd883c8cf1e74f2f42c62195006606c69613170d56d2ecb8db6fb03f5acb6bdd0ffaf54bdf788854ddafc6becfdf3c7', 'user', '2025-02-24 06:18:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
