-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 23 mei 2025 om 15:46
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ziekmeldingen`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `docenten`
--

CREATE TABLE `docenten` (
  `id` int(11) NOT NULL,
  `naam` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `docenten`
--

INSERT INTO `docenten` (`id`, `naam`, `email`) VALUES
(1, 'meneer Barry', 'barry@school.nl'),
(2, 'meneer Wigmans', 'wigmans@school.nl'),
(3, 'meneer asrar', 'asrar@school.nl');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ziekmeldingen`
--

CREATE TABLE `ziekmeldingen` (
  `id` int(11) NOT NULL,
  `docent_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `reden` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `ziekmeldingen`
--

INSERT INTO `ziekmeldingen` (`id`, `docent_id`, `datum`, `reden`) VALUES
(13, 1, '2025-05-04', 'Door koude weer is meneer Barry ziek.'),
(14, 3, '2025-05-12', 'dfsgdrsfvdrs');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `docenten`
--
ALTER TABLE `docenten`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexen voor tabel `ziekmeldingen`
--
ALTER TABLE `ziekmeldingen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `docent_id` (`docent_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `docenten`
--
ALTER TABLE `docenten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT voor een tabel `ziekmeldingen`
--
ALTER TABLE `ziekmeldingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `ziekmeldingen`
--
ALTER TABLE `ziekmeldingen`
  ADD CONSTRAINT `ziekmeldingen_ibfk_1` FOREIGN KEY (`docent_id`) REFERENCES `docenten` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
