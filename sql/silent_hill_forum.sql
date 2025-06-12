-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1
-- Čas generovania: Št 12.Jún 2025, 13:04
-- Verzia serveru: 10.4.32-MariaDB
-- Verzia PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `silent_hill_forum`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `comments`
--

INSERT INTO `comments` (`id`, `section_id`, `user_id`, `content`, `created_at`) VALUES
(6, 11, 3, 'dddgg', '2025-06-12 08:14:08');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `forum_sections`
--

CREATE TABLE `forum_sections` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `forum_sections`
--

INSERT INTO `forum_sections` (`id`, `name`, `description`, `user_id`) VALUES
(1, 'General Discussion', 'Talk about anything related to Silent Hill.', NULL),
(2, 'Game Theories', 'Discuss theories about the games and story.', NULL),
(3, 'Fan Art', 'Share your fan art and creations.', NULL),
(11, 'FF', 'FF', 3);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `profile_picture`) VALUES
(1, 'admin', '$2y$10$yDnUI1c6I1I6K7qvlfBtuOOjjP9qSTv4jNq4SMrLVn1cIPDjEk3xG', 'admin@example.com', NULL),
(2, 'tengrrr', '$2y$10$I3eUP7TWNgNW0yKASWbRN.D4Hve5r6bltZGWo/u1oKYQjG0HqqV8q', 'tengr72544@gmail.com', NULL),
(3, 'tengrg', '$2y$10$m0BWPXKdie73gOMYveE2ZOTEu3hil.2xJXAbPXVin/T3XaGr1H/3m', 'tengrqix@proton.me', 'images/profile_pics/dd.jpg'),
(4, 'tengrrrrrrr', '$2y$10$iycD42gSCTY3.dSZcT8oaOjXqtAkHEawKvCJsC0A14pmbFFy/eXZy', 'tengrqixfdfdf@proton.me', NULL),
(5, 'JankoHrasko', '$2y$10$LeaV2lmxaJ0O9.LcQZMf7.oYJe2WUABkAVp2/6hACAVaSHZJxPu6u', 'te@gmail.com', NULL),
(7, 'tengr', '$2y$10$KgnEZJAjo1zB29uoe29Yk.SqCCFvU7JXFeVYiGa0neUfQLASRd5su', 'fvr@studnet.kokk', NULL);

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexy pre tabuľku `forum_sections`
--
ALTER TABLE `forum_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexy pre tabuľku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pre tabuľku `forum_sections`
--
ALTER TABLE `forum_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pre tabuľku `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `forum_sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Obmedzenie pre tabuľku `forum_sections`
--
ALTER TABLE `forum_sections`
  ADD CONSTRAINT `forum_sections_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
