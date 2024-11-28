-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2024 at 05:50 AM
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
-- Database: `movie_recommendation_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `genre_id` int(11) NOT NULL,
  `genre_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`genre_id`, `genre_name`) VALUES
(5, 'Comedy'),
(4, 'Horror'),
(1, 'Romance'),
(3, 'Thriller');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `image_id` int(11) NOT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`image_id`, `movie_id`, `image_url`) VALUES
(1, 1, '../public/uploads/movies/cat.jpg'),
(2, 2, '../public/uploads/movies/cats-by-the-window-desktop-wallpaper-preview.jpg'),
(3, 3, '../public/uploads/movies/cute-cat-sleeping-on-sofa-beige-desktop-wallpaper-preview.jpg'),
(4, 4, '../public/uploads/movies/ethereal-flower-minimalist-desktop-wallpaper-preview.jpg'),
(5, 5, '../public/uploads/movies/funny-raccoon-detective-beige-desktop-wallpaper-preview.jpg'),
(6, 6, '../public/uploads/movies/ethereal-flower-minimalist-desktop-wallpaper-preview.jpg'),
(7, 7, '../public/uploads/movies/cat.jpg'),
(8, 8, '../public/uploads/movies/The_Hustle_film_poster.png'),
(9, 9, '../public/uploads/movies/afraid.jpg'),
(10, 10, '../public/uploads/movies/Encanto_poster.jpg'),
(11, 11, '../public/uploads/movies/sector-36-poster.png'),
(12, 12, '../public/uploads/movies/Father of the bride.jpg'),
(13, 13, '../public/uploads/movies/The_Hustle_film_poster.png'),
(14, 14, '../public/uploads/movies/The_Hustle_film_poster.png'),
(15, 15, '../public/uploads/movies/Father of the bride.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `movie_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `release_date` date NOT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `director` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movie_id`, `title`, `release_date`, `genre_id`, `director`, `description`) VALUES
(1, 'devdash', '1997-09-12', 1, 'alisha', 'In the 1900s, Kausalya Mukherjee is happy to receive a letter about her son Devdas\'s arrival from London after his stay there for ten years for law school. Kausalya informs her neighbour and close friend Sumitra, whose daughter Parvati \"Paro\" was a close childhood friend of Devdas. When Devdas was sent to London, Paro was advised to light a lamp to bring about his return and never permitted it to extinguish. Devdas arrives and his and Paro\'s friendship transforms into deep love; Kausalya disapproves, owing to Paro\'s maternal lineage of nautch girls and nautanki performers, which she considers inappropriate for a Zamindari family like hers. Kumud, Devdas\'s manipulative sister-in-law, fuels her mother-in-law\'s thoughts.'),
(2, 'Aliha Darliing', '2121-02-11', NULL, 'fini', 'qwertyuioplkjabfbfb'),
(3, 'mad rara', '1213-11-11', 3, 'khara', 'rara went mad'),
(4, 'baby', '1111-12-31', NULL, 'anila', 'qwertyuio'),
(5, 'bloody maniac', '1999-12-21', 4, 'niraj', 'horror houe'),
(6, 'hum aap key hey kon', '2222-11-22', 4, 'aliha', 'pata nahi'),
(7, 'papa', '1122-02-12', NULL, 'fini', 'papa cat'),
(8, 'The Hustle', '2019-05-10', 5, 'Chris Addison', 'The Hustle is a 2019 American comedy film directed by Chris Addison, starring Anne Hathaway, Rebel Wilson and Alex Sharp. It was written by Stanley Shapiro, Paul Henning, Dale Launer, and Jac Schaeffer. It is a female-centered remake of the 1988 film Dirty Rotten Scoundrels, which is a remake of the 1964 film Bedtime Story.[3] The film follows two women who set out to con an Internet millionaire.'),
(9, 'Afraid', '2024-08-30', 5, 'Chris Weitz, Jason Blum', 'From Blumhouse, producer of M3gan and The Black Phone, comes AFRAID.'),
(10, 'Encanto', '2021-11-24', 1, 'Jared Bush, Byron Howard', 'Walt Disney Animation Studios’ ENCANTO tells the tale of an extraordinary family, the Madrigals, who live hidden in the mountains of Colombia, in a magical house, in a vibrant town, in a wondrous, charmed place called an Encanto. '),
(11, 'Sector 36', '2024-09-13', 3, 'Aditya Nimbalkar', 'A previously corrupt and ignorant cop, Ram Charan Pandey decides to confront the serial killer Prem Singh after several children go missing, following a near assault on his own daughter, exposing the dark underbelly of the city.'),
(12, 'Father of The Bride', '1991-12-20', 3, ' Charles Shyer', 'George Banks leads the perfect life with his wife, daughter and son. However, when Annie, his daughter, decides to get married, he has a hard time letting go of her.'),
(13, 'The Hustle', '2023-12-23', 5, 'anila', 'The hustle is comedy.'),
(14, 'The Hustle', '2023-12-23', 4, 'anila', 'The hustle is comedy.'),
(15, 'azu', '2123-11-22', 4, 'jni', 'aaa');

-- --------------------------------------------------------

--
-- Table structure for table `movie_genres`
--

CREATE TABLE `movie_genres` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `genre_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movie_genres`
--

INSERT INTO `movie_genres` (`id`, `movie_id`, `genre_id`) VALUES
(2, 7, 1),
(3, 8, 5),
(4, 9, 4),
(5, 9, 3),
(6, 10, 5),
(7, 10, 1),
(8, 11, 4),
(9, 11, 3),
(10, 12, 5),
(11, 13, 5),
(12, 14, 4),
(13, 15, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'alisha', 'alishanepal95@gmail.com', '$2y$10$6epMivLvAQUKP7NGm8W9DeU72lLlm8zcOG6YfvVTDgUaz0lX8hNme', 'user', '2024-09-20 12:33:56'),
(2, 'nalina', 'nalina@gmail.com', '$2y$10$.zgvpIxfcV.KCQeLp9ttxe2OViHvYhLuZnZP8mvQ1gkdg.XrF2WpK', 'admin', '2024-09-20 12:34:29'),
(3, 'jiniha', 'jiniha@gmail.com', '$2y$10$NC5if9hSCfDBli7uSOL2D.RQ7SSyoceNwq4jclDAL/.j9Vn/qCs.a', 'user', '2024-09-22 07:19:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genre_id`),
  ADD UNIQUE KEY `genre_name` (`genre_name`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`movie_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indexes for table `movie_genres`
--
ALTER TABLE `movie_genres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movie_id` (`movie_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `genre_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `movie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `movie_genres`
--
ALTER TABLE `movie_genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE;

--
-- Constraints for table `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `movies_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`genre_id`) ON DELETE SET NULL;

--
-- Constraints for table `movie_genres`
--
ALTER TABLE `movie_genres`
  ADD CONSTRAINT `movie_genres_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `movie_genres_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`genre_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
