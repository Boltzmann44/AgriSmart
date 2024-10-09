-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Ott 09, 2024 alle 13:24
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agrismart_db`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `campi`
--

CREATE TABLE `campi` (
  `id_campo` int(11) NOT NULL,
  `nome_campo` varchar(255) NOT NULL,
  `coltura` varchar(255) NOT NULL,
  `dimensione` int(11) NOT NULL,
  `data_semina` date NOT NULL DEFAULT current_timestamp(),
  `data_raccolta` date DEFAULT NULL,
  `id_utente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `campi`
--

INSERT INTO `campi` (`id_campo`, `nome_campo`, `coltura`, `dimensione`, `data_semina`, `data_raccolta`, `id_utente`) VALUES
(17, 'Pomodori', 'Pomodori', 15, '2024-09-12', '2025-06-12', 3),
(26, 'Grano', 'Grano', 22, '2024-09-12', NULL, 3),
(29, 'Piselli', 'Piselli', 15, '2024-08-15', '2024-09-10', 3),
(54, 'Uva', 'Uva', 20, '2024-09-29', '2024-10-29', 3),
(62, 'Sezione 1', 'Mele', 23, '2024-10-09', '2025-01-09', 7),
(63, 'Sezione 2', 'Mais', 12, '2024-10-09', '2025-07-09', 7),
(64, 'Sezione 3', 'Uva', 5, '2024-10-09', NULL, 7),
(66, 'Sezione 4', 'Piselli', 5, '2024-09-10', '2024-10-06', 7);

-- --------------------------------------------------------

--
-- Struttura della tabella `prodotti_market`
--

CREATE TABLE `prodotti_market` (
  `id_prodotto` int(11) NOT NULL,
  `nome_prodotto` varchar(100) NOT NULL,
  `descrizione_prodotto` text DEFAULT NULL,
  `qnt_prodotto` int(11) NOT NULL,
  `prezzo` int(11) NOT NULL,
  `tipo_qnt` tinyint(1) NOT NULL,
  `tipo_prezzo` int(2) NOT NULL,
  `percorso_img` varchar(255) NOT NULL,
  `id_utente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `prodotti_market`
--

INSERT INTO `prodotti_market` (`id_prodotto`, `nome_prodotto`, `descrizione_prodotto`, `qnt_prodotto`, `prezzo`, `tipo_qnt`, `tipo_prezzo`, `percorso_img`, `id_utente`) VALUES
(28, 'Marmellata', 'Questa marmellata è ottima. Dovresti assolutamente provarla.', 10, 1, 0, 0, './img-users/66e857e0d71548.13263102.jpg', 3),
(29, 'Mele', 'Le mele le ho raccolte io personalmente.', 450, 2, 1, 1, './img-users/66e85816d02b24.51511979.jpeg', 3),
(39, 'Peperoni', 'Peperoni freschissimi. Raccolti a mano.', 20, 5, 1, 1, './img-users/66e8584819f764.63101341.jpg', 3),
(49, 'Marmellata', 'Questa marmellata è ottima. Dovresti assolutamente provarla.', 10, 1, 0, 0, './img-users/66e857e0d71548.13263102.jpg', 7),
(50, 'Mele', 'Le mele le ho raccolte io personalmente.', 450, 2, 1, 1, './img-users/66e85816d02b24.51511979.jpeg', 7),
(51, 'Peperoni', 'Peperoni freschissimi. Raccolti a mano.', 20, 5, 1, 1, './img-users/66e8584819f764.63101341.jpg', 7);

-- --------------------------------------------------------

--
-- Struttura della tabella `province`
--

CREATE TABLE `province` (
  `id_provincia` int(11) NOT NULL,
  `nome_provincia` varchar(255) NOT NULL,
  `id_regione` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `province`
--

INSERT INTO `province` (`id_provincia`, `nome_provincia`, `id_regione`) VALUES
(1, 'Chieti', 1),
(2, 'L\'Aquila', 1),
(3, 'Pescara', 1),
(4, 'Teramo', 1),
(5, 'Matera', 2),
(6, 'Potenza', 2),
(7, 'Catanzaro', 3),
(8, 'Cosenza', 3),
(9, 'Crotone', 3),
(10, 'Reggio Calabria', 3),
(11, 'Vibo Valentia', 3),
(12, 'Avellino', 4),
(13, 'Benevento', 4),
(14, 'Caserta', 4),
(15, 'Napoli', 4),
(16, 'Salerno', 4),
(17, 'Bologna', 5),
(18, 'Ferrara', 5),
(19, 'Forlì-Cesena', 5),
(20, 'Modena', 5),
(21, 'Parma', 5),
(22, 'Piacenza', 5),
(23, 'Ravenna', 5),
(24, 'Reggio Emilia', 5),
(25, 'Rimini', 5),
(26, 'Gorizia', 6),
(27, 'Pordenone', 6),
(28, 'Trieste', 6),
(29, 'Udine', 6),
(30, 'Frosinone', 7),
(31, 'Latina', 7),
(32, 'Rieti', 7),
(33, 'Roma', 7),
(34, 'Viterbo', 7),
(35, 'Genova', 8),
(36, 'Imperia', 8),
(37, 'La Spezia', 8),
(38, 'Savona', 8),
(39, 'Bergamo', 9),
(40, 'Brescia', 9),
(41, 'Como', 9),
(42, 'Cremona', 9),
(43, 'Lecco', 9),
(44, 'Lodi', 9),
(45, 'Mantova', 9),
(46, 'Milano', 9),
(47, 'Monza e Brianza', 9),
(48, 'Pavia', 9),
(49, 'Sondrio', 9),
(50, 'Varese', 9),
(51, 'Ancona', 10),
(52, 'Ascoli Piceno', 10),
(53, 'Fermo', 10),
(54, 'Macerata', 10),
(55, 'Pesaro e Urbino', 10),
(56, 'Campobasso', 11),
(57, 'Isernia', 11),
(58, 'Alessandria', 12),
(59, 'Asti', 12),
(60, 'Biella', 12),
(61, 'Cuneo', 12),
(62, 'Novara', 12),
(63, 'Torino', 12),
(64, 'Verbano-Cusio-Ossola', 12),
(65, 'Vercelli', 12),
(66, 'Bari', 13),
(67, 'Barletta-Andria-Trani', 13),
(68, 'Brindisi', 13),
(69, 'Foggia', 13),
(70, 'Lecce', 13),
(71, 'Taranto', 13),
(72, 'Cagliari', 14),
(73, 'Nuoro', 14),
(74, 'Oristano', 14),
(75, 'Sassari', 14),
(76, 'Sud Sardegna', 14),
(77, 'Agrigento', 15),
(78, 'Caltanissetta', 15),
(79, 'Catania', 15),
(80, 'Enna', 15),
(81, 'Messina', 15),
(82, 'Palermo', 15),
(83, 'Ragusa', 15),
(84, 'Siracusa', 15),
(85, 'Trapani', 15),
(86, 'Arezzo', 16),
(87, 'Firenze', 16),
(88, 'Grosseto', 16),
(89, 'Livorno', 16),
(90, 'Lucca', 16),
(91, 'Massa-Carrara', 16),
(92, 'Pisa', 16),
(93, 'Pistoia', 16),
(94, 'Prato', 16),
(95, 'Siena', 16),
(96, 'Bolzano', 17),
(97, 'Trento', 17),
(98, 'Perugia', 18),
(99, 'Terni', 18),
(100, 'Aosta', 19),
(101, 'Belluno', 20),
(102, 'Padova', 20),
(103, 'Rovigo', 20),
(104, 'Treviso', 20),
(105, 'Venezia', 20),
(106, 'Verona', 20),
(107, 'Vicenza', 20);

-- --------------------------------------------------------

--
-- Struttura della tabella `regioni`
--

CREATE TABLE `regioni` (
  `id_regione` int(11) NOT NULL,
  `nome_regione` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `regioni`
--

INSERT INTO `regioni` (`id_regione`, `nome_regione`) VALUES
(1, 'Abruzzo'),
(2, 'Basilicata'),
(3, 'Calabria'),
(4, 'Campania'),
(5, 'Emilia-Romagna'),
(6, 'Friuli Venezia Giulia'),
(7, 'Lazio'),
(8, 'Liguria'),
(9, 'Lombardia'),
(10, 'Marche'),
(11, 'Molise'),
(12, 'Piemonte'),
(13, 'Puglia'),
(14, 'Sardegna'),
(15, 'Sicilia'),
(16, 'Toscana'),
(17, 'Trentino-Alto Adige'),
(18, 'Umbria'),
(19, 'Valle d\'Aosta'),
(20, 'Veneto');

-- --------------------------------------------------------

--
-- Struttura della tabella `risorse`
--

CREATE TABLE `risorse` (
  `id_risorsa` int(11) NOT NULL,
  `nome_risorsa` varchar(255) NOT NULL,
  `qnt` int(11) NOT NULL,
  `id_utente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `risorse`
--

INSERT INTO `risorse` (`id_risorsa`, `nome_risorsa`, `qnt`, `id_utente`) VALUES
(6, 'Uva', 134190, 3),
(9, 'Grano', 1344, 3),
(10, 'Pere', 20, 3),
(11, 'Pomodori', 134, 3),
(27, 'Uva', 134190, 7),
(28, 'Grano', 1344, 7),
(29, 'Pere', 20, 7),
(30, 'Pomodori', 134, 7);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id_utente` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nome_azienda` varchar(255) NOT NULL,
  `comune` varchar(255) NOT NULL,
  `cap` varchar(10) NOT NULL,
  `id_provincia` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id_utente`, `email`, `password`, `nome_azienda`, `comune`, `cap`, `id_provincia`) VALUES
(3, 'sasy.marrandino@gmail.com', '$2y$10$fDi8tdw9NTZ9gNW77Oe6k.Si8L9kC3L9UPcf2pm0heqw.hbehBqBK', 'Marrandino S.r.l.', 'Cesa', '81030', 14),
(4, 'sasy.031@gmail.com', '$2y$10$YlqKfkocIDjUpoYAMjGlVeeMDuKLnMWYqBSkmL8v1eo1MYLw9.tdy', 'Marra Corporation', 'Cesa', '81030', 14),
(7, 'agrismartassistance@gmail.com', '$2y$10$JTWX.ayjjrM6mT4eQiowYux7nzh5HpDC7bRbHlF51orQiwl1uHQ2m', 'Agrismart S.r.l.', 'Aversa', '81031', 14);

-- --------------------------------------------------------

--
-- Struttura della tabella `veicoli`
--

CREATE TABLE `veicoli` (
  `id_veicolo` int(11) NOT NULL,
  `marchio` varchar(100) NOT NULL,
  `modello` varchar(100) NOT NULL,
  `potenza` int(11) NOT NULL,
  `data_acquisto` date NOT NULL,
  `data_revisione` date NOT NULL,
  `percorso_img` varchar(255) NOT NULL,
  `id_utente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `veicoli`
--

INSERT INTO `veicoli` (`id_veicolo`, `marchio`, `modello`, `potenza`, `data_acquisto`, `data_revisione`, `percorso_img`, `id_utente`) VALUES
(1, 'Lamborghini', 'Gallardo', 75, '2024-09-13', '2024-09-29', './img-users/66e47ccbf0d769.50377873.jpg', 3),
(2, 'Lamborghini', 'Sesto Elemento', 104, '2024-09-13', '2024-12-20', './img-users/66e47d6cb421b4.46817931.jpg', 3),
(32, 'Lamborghini', 'Gallardo', 75, '2024-09-13', '2024-09-29', './img-users/66e47ccbf0d769.50377873.jpg', 7),
(33, 'Lamborghini', 'Sesto Elemento', 104, '2024-09-13', '2024-12-20', './img-users/66e47d6cb421b4.46817931.jpg', 7);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `campi`
--
ALTER TABLE `campi`
  ADD PRIMARY KEY (`id_campo`),
  ADD KEY `id_utente` (`id_utente`);

--
-- Indici per le tabelle `prodotti_market`
--
ALTER TABLE `prodotti_market`
  ADD PRIMARY KEY (`id_prodotto`),
  ADD KEY `id_utente` (`id_utente`);

--
-- Indici per le tabelle `province`
--
ALTER TABLE `province`
  ADD PRIMARY KEY (`id_provincia`),
  ADD KEY `ID_REGIONE` (`id_regione`);

--
-- Indici per le tabelle `regioni`
--
ALTER TABLE `regioni`
  ADD PRIMARY KEY (`id_regione`);

--
-- Indici per le tabelle `risorse`
--
ALTER TABLE `risorse`
  ADD PRIMARY KEY (`id_risorsa`),
  ADD KEY `id_utente` (`id_utente`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id_utente`),
  ADD KEY `ID_PROVINCIA` (`id_provincia`);

--
-- Indici per le tabelle `veicoli`
--
ALTER TABLE `veicoli`
  ADD PRIMARY KEY (`id_veicolo`),
  ADD KEY `id_utente` (`id_utente`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `campi`
--
ALTER TABLE `campi`
  MODIFY `id_campo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT per la tabella `prodotti_market`
--
ALTER TABLE `prodotti_market`
  MODIFY `id_prodotto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT per la tabella `risorse`
--
ALTER TABLE `risorse`
  MODIFY `id_risorsa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id_utente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `veicoli`
--
ALTER TABLE `veicoli`
  MODIFY `id_veicolo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `prodotti_market`
--
ALTER TABLE `prodotti_market`
  ADD CONSTRAINT `prodotti_market_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`ID_UTENTE`);

--
-- Limiti per la tabella `province`
--
ALTER TABLE `province`
  ADD CONSTRAINT `province_ibfk_1` FOREIGN KEY (`ID_REGIONE`) REFERENCES `regioni` (`ID_REGIONE`);

--
-- Limiti per la tabella `risorse`
--
ALTER TABLE `risorse`
  ADD CONSTRAINT `risorse_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`ID_UTENTE`);

--
-- Limiti per la tabella `utenti`
--
ALTER TABLE `utenti`
  ADD CONSTRAINT `utenti_ibfk_1` FOREIGN KEY (`ID_PROVINCIA`) REFERENCES `province` (`ID_PROVINCIA`);

--
-- Limiti per la tabella `veicoli`
--
ALTER TABLE `veicoli`
  ADD CONSTRAINT `veicoli_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`ID_UTENTE`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
