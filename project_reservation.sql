-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 02 Lut 2021, 12:00
-- Wersja serwera: 10.4.11-MariaDB
-- Wersja PHP: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `project_reservation`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(40) NOT NULL,
  `pass_hash` varchar(60) NOT NULL,
  `blocked` int(1) NOT NULL COMMENT 'Flaga zablokowanego administratora (0 - niezablokowany, 1- zablokowany).'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `admins`
--

INSERT INTO `admins` (`id`, `email`, `pass_hash`, `blocked`) VALUES
(1, '160746@stud.prz.edu.pl', '$2y$10$83Y9tI6z1TrOUITDP6VfoO45jl/fLhT9NKClatsbpJohxRWWEVysi', 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `project_list`
--

CREATE TABLE `project_list` (
  `id` int(11) NOT NULL,
  `topic` varchar(300) NOT NULL,
  `description` text DEFAULT 'Brak opisu.',
  `technologies` text DEFAULT 'Brak podanych technologii.',
  `blocked` int(1) NOT NULL DEFAULT 0 COMMENT 'Flaga zablokowanego projektu (0 - niezablokowany, 1- zablokowany).'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `project_list`
--

INSERT INTO `project_list` (`id`, `topic`, `description`, `technologies`, `blocked`) VALUES
(1, 'Quiz PRO', 'System on-line przeprowadzania testów z przedmiotu TSW (minimum test demo).', 'Dowolne technologie.', 0),
(2, 'Slide Viewer PRO', 'Wirtualne wykłady (audio, wideo, slajdy), przeglądarka zdjęć z przedmiotu TSW.', 'Dowolne technologie.', 0),
(3, 'Code Viewer PRO', 'Przeglądarka kodów źródłowych przykładów (kodów z laboratorium TSW).', 'Dowolne technologie.', 0),
(4, 'Download system PRO', 'System dostępu do materiałów dydaktycznych z przedmiotu TSW.', 'Dowolne technologie.', 0),
(5, 'Upload system PRO', 'System oddawania spakowanych plików projektów z przedmiotu TSW.', 'Dowolne technologie.', 0),
(6, 'Reservation System PRO', 'System wyboru/rezerwacji tematów projektów z TSW.', 'Dowolne technologie.', 0),
(7, 'Web Tools PRO', 'Witryna z linkami i opisem narzędzi Web, np. Frameworks, Tutorials, Tools itp.', 'Dowolne technologie.', 0),
(8, 'Kalendarz akademicki', 'Ma zawierać: rozkład zajęć, prezentację i zarządzanie elementami w kalendarzu.', 'Dowolne technologie.', 0),
(9, 'Ankieta z graficzną prezentacją wyników.', 'Brak opisu.', 'Brak wybranych technologii.', 0),
(61, 'Testowy projekt', 'xxdsd saas', 'dasdadadadsffffsdsdsff', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `submitted_projects`
--

CREATE TABLE `submitted_projects` (
  `id` int(11) NOT NULL,
  `email` varchar(40) NOT NULL,
  `first_name` varchar(25) DEFAULT NULL,
  `second_name` varchar(35) DEFAULT NULL,
  `project_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `submitted_projects`
--

INSERT INTO `submitted_projects` (`id`, `email`, `first_name`, `second_name`, `project_id`) VALUES
(67, 'user1@gmail.com', 'Maciej', 'Harbuz', 6),
(84, 'user2@gmail.com', 'A', 'N', 4);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(40) NOT NULL,
  `pass_hash` varchar(60) NOT NULL COMMENT 'PASSWORD_BCRYPT (CRYPT_BLOWFISH) format',
  `blocked` int(1) NOT NULL COMMENT 'Flaga zablokowanego użytkownika (0 - niezablokowany, 1- zablokowany). '
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `email`, `pass_hash`, `blocked`) VALUES
(3, 'user1@gmail.com', '$2y$10$83Y9tI6z1TrOUITDP6VfoO45jl/fLhT9NKClatsbpJohxRWWEVysi', 0),
(4, 'user2@gmail.com', '$2y$10$83Y9tI6z1TrOUITDP6VfoO45jl/fLhT9NKClatsbpJohxRWWEVysi', 1),
(13, '160746@stud.prz.edu.pl', '$2y$10$E.so8jHsKV3M8miKEpv84uJr0aBQXvFOZmvyZyW95W.8yTywmzJ6G', 0);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeksy dla tabeli `project_list`
--
ALTER TABLE `project_list`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `submitted_projects`
--
ALTER TABLE `submitted_projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `project_list`
--
ALTER TABLE `project_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT dla tabeli `submitted_projects`
--
ALTER TABLE `submitted_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `submitted_projects`
--
ALTER TABLE `submitted_projects`
  ADD CONSTRAINT `submitted_projects_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
