-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2024 at 02:38 PM
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
-- Database: `todo_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(1, 'nadiradanish@gmail.com', '86885a4b232dcebd2134b8ce184c52db67d7f4cd17db10f681c024a8f4b2d2b3', '2024-10-25 13:55:35', '2024-10-25 10:55:35'),
(2, 'nadiradanish@gmail.com', '5801034fdcbdade7fdc64fcc7612f1e62f67d5970dcbb20f00a700a2fdd964d5', '2024-10-25 13:59:34', '2024-10-25 10:59:34'),
(3, 'nadiradanish@gmail.com', 'f02caad9e48abec301fbfa8c8209cd6c87be9aea52b0cf6ceaa8e047fa3ebf6b', '2024-10-25 14:01:12', '2024-10-25 11:01:12'),
(4, 'nadiradanish@gmail.com', 'e7c64c8280277620f6068dbf89c6d49d93730b537f31be13543fb88a39b6fc8b', '2024-10-26 01:01:42', '2024-10-25 11:01:42'),
(5, 'nadiradanish@gmail.com', '72493fddc8a0c147ecd16ce2032ce73ca0c580dc9bd9579c6146b6197a02b652', '2024-10-26 01:07:31', '2024-10-25 11:07:31'),
(6, 'nadiradanish@gmail.com', '14e0a363d0590ffc7b4115fe31e019c746beb0b3802a9c7b80378c042b1df760', '2024-10-26 01:07:44', '2024-10-25 11:07:44'),
(7, 'nadiradanish@gmail.com', '0cec82d9a7360d9ba2dab4ebe8ff7fa8bc50b61dcc3875718ba5b481cc89c4bf', '2024-10-26 01:08:40', '2024-10-25 11:08:40'),
(8, 'nadiradanish@gmail.com', 'b48afeb11c7ca9477491c3d8d722ccb783d6dee9e094ed086873268262a2b33f', '2024-10-26 01:08:45', '2024-10-25 11:08:45'),
(9, 'nadiradanish@gmail.com', '5c8a4d4c7e19bd39c067fbdb4d979cb12a307b705e3fe4bdf2b83d81b6a44b0f', '2024-10-26 01:11:32', '2024-10-25 11:11:32'),
(10, 'nadiradanish@gmail.com', '0d05cb35659a1bc96c1a3da594c1de0a5635fa763ef4309c726c94087042976c', '2024-10-26 01:12:00', '2024-10-25 11:12:00'),
(11, 'nadiradanish@gmail.com', '28181138ebbd6972b71e59df701deff14b4e99582923abeb23e83b76ea3c55e9', '2024-10-26 01:12:53', '2024-10-25 11:12:53'),
(12, 'nadiradanish@gmail.com', 'c73de1e75ae93ec1b3bc4b6938a24e6e5d01f8478e782ddecff6e6cc7a97cf56', '2024-10-26 01:13:00', '2024-10-25 11:13:00'),
(13, 'nadiradanish@gmail.com', '709a9c9dfe6fd2e2b27c980d6a664647479e1a39295942914291ca79e01d575f', '2024-10-26 01:15:14', '2024-10-25 11:15:14'),
(14, 'nadiradanish@gmail.com', '34cb221fe776440ac9f9e6d8ce44b42f49d3618f706190ab71b460c253435942', '2024-10-26 01:21:46', '2024-10-25 11:21:46');

-- --------------------------------------------------------

--
-- Table structure for table `sub_tasks`
--

CREATE TABLE `sub_tasks` (
  `id` int(11) NOT NULL,
  `todo_list_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` enum('completed','incomplete') DEFAULT 'incomplete',
  `due_date` date DEFAULT NULL,
  `due_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `todo_list_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` enum('completed','incomplete') DEFAULT 'incomplete',
  `priority` enum('high','medium','low') NOT NULL DEFAULT 'medium',
  `due_date` date DEFAULT NULL,
  `due_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `todo_list_id`, `description`, `status`, `priority`, `due_date`, `due_time`) VALUES
(26, 21, 'TIDUR', 'incomplete', 'low', '2024-10-24', '14:42:00'),
(27, 22, 'TIDUR', 'incomplete', 'high', '2024-10-24', '02:10:00'),
(29, 21, 'BELAJAR', 'incomplete', 'high', '2024-10-24', '23:10:00');

-- --------------------------------------------------------

--
-- Table structure for table `todo_lists`
--

CREATE TABLE `todo_lists` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `priority` int(11) DEFAULT 0,
  `description` varchar(255) DEFAULT NULL,
  `color` varchar(7) NOT NULL DEFAULT '#ffffff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `todo_lists`
--

INSERT INTO `todo_lists` (`id`, `user_id`, `title`, `priority`, `description`, `color`) VALUES
(21, 1, 'makan', 0, NULL, '#f9ed69'),
(22, 1, 'as', 0, NULL, '#f9ed69'),
(23, 5, 'Work', 0, NULL, '#f9ed69'),
(39, 6, 'Belajar', 0, 'belajarlah terus sampe mampus ygy', '#ffffff'),
(40, 6, 'Tugas', 0, 'hadeh banyak bgt coyg ini jangan di buka yh ntar kaget', '#ffffff'),
(41, 6, 'Makanan', 0, 'ini makanan yg enaaak nyam\r\n', '#ffffff'),
(42, 6, 'main', 0, 'mengahbiskan uwang untuk self reward ygy\r\n', '#ffffff');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `profile_photo`, `reset_token`, `reset_expiry`) VALUES
(1, 'nadhnads', 'nadiradanish@gmail.com', '$2y$10$OCgpLoky7jNP4.chTPjShewV8JO7Sdczc0vWbuJVpUVd1nVs93Y0m', 'uploads/pinguin.jpg', NULL, NULL),
(2, 'nadhnads', 'naana@gsh.com', '$2y$10$w4i0NKfzrrw8OOn/mfS3B.hjq9OBJQ1BLHvZHvQoAG9cEbFGf3kk.', NULL, NULL, NULL),
(3, 'ty', 'taeyong@gmail.com', '$2y$10$wZY5Yb8MFOy.efguphD7l.9HeHiJxDb1q/a0A77EBEMM1Fu3/q/w6', 'uploads/pinguin.jpg', NULL, NULL),
(4, 'taeyong', 'taeyong18@gmail.com', '$2y$10$OGKzxtuQmquk1609536zv.UMqn3wLAPzMguSivt8.xAuGmdKYksey', NULL, NULL, NULL),
(5, 'tyong', 'ty@gmail.com', '$2y$10$Mao4YE8w.440iUUcCo41LOCkoB6yGC69CKX5Ne6p0.92ngzxUtWuq', 'uploads/icon_profile.jpg', NULL, NULL),
(6, 'nadhs', 'nana@gmail.com', '$2y$10$bFtGEVojrXz0JxkrydqXDevDKN4LgZkgyNmsOFWfs5sILc7.MQaqG', 'uploads/icon_profile.jpg', NULL, NULL),
(7, 'naufal', 'rn@gmail.com', '$2y$10$SyEgK0aDl5s/gcXnfB6EveDByDuxm/Su9fpA5YSGpNY603B7vkkSq', 'uploads/icon_profile.jpg', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_tasks`
--
ALTER TABLE `sub_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `todo_list_id` (`todo_list_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `todo_list_id` (`todo_list_id`);

--
-- Indexes for table `todo_lists`
--
ALTER TABLE `todo_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sub_tasks`
--
ALTER TABLE `sub_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `todo_lists`
--
ALTER TABLE `todo_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sub_tasks`
--
ALTER TABLE `sub_tasks`
  ADD CONSTRAINT `sub_tasks_ibfk_1` FOREIGN KEY (`todo_list_id`) REFERENCES `todo_lists` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`todo_list_id`) REFERENCES `todo_lists` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `todo_lists`
--
ALTER TABLE `todo_lists`
  ADD CONSTRAINT `todo_lists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
