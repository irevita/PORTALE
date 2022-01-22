-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Set 13, 2021 alle 11:47
-- Versione del server: 10.4.17-MariaDB
-- Versione PHP: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yourblog`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `blog`
--

CREATE TABLE `blog` (
  `id_blog` int(11) NOT NULL,
  `titolo` varchar(40) NOT NULL,
  `descrizione` varchar(60) CHARACTER SET utf8 NOT NULL,
  `categoria` int(11) NOT NULL,
  `immagine` varchar(80) NOT NULL,
  `autore` int(11) NOT NULL,
  `data_blog` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `blog`
--

INSERT INTO `blog` (`id_blog`, `titolo`, `descrizione`, `categoria`, `immagine`, `autore`, `data_blog`) VALUES
(148, 'Generi Musicali', 'Alla scoperta dei generi muiscali  dai classici ai più conte', 272, 'images/noimage.png', 28, '2020-09-01 18:47:26'),
(149, 'Vacanze nel Mediterraneo', 'Alla scoperta dei luoghi, delle spiagge e degli itinerari pi', 273, 'images/noimage.png', 29, '2020-09-01 18:49:54'),
(150, 'Paracadutismo', 'Prepariamoci al lancio!', 274, 'images/noimage.png', 29, '2020-09-01 18:53:43'),
(151, 'Giardinaggio', 'Tecnica e arte della coltivazione di piante.', 275, 'images/noimage.png', 30, '2020-09-01 18:55:14'),
(152, 'Calcio', 'Sport di squadra', 276, 'images/noimage.png', 31, '2020-09-01 18:57:07'),
(153, 'Decoupage', 'Tecnica decorativa, dal francese découper, ritagliare.', 277, 'images/noimage.png', 31, '2020-09-01 18:57:45');

-- --------------------------------------------------------

--
-- Struttura della tabella `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nomeC` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nomeC`) VALUES
(272, 'Musica'),
(273, 'Viaggiare'),
(274, 'Sport'),
(275, 'Tempo Libero'),
(276, 'Sport'),
(277, 'Tempo Libero');

-- --------------------------------------------------------

--
-- Struttura della tabella `collaboratore`
--

CREATE TABLE `collaboratore` (
  `blog` int(11) NOT NULL,
  `utente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `collaboratore`
--

INSERT INTO `collaboratore` (`blog`, `utente`) VALUES
(153, 28);

-- --------------------------------------------------------

--
-- Struttura della tabella `commento`
--

CREATE TABLE `commento` (
  `id_commento` int(11) NOT NULL,
  `utente` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `testo` text CHARACTER SET utf8 NOT NULL,
  `data_commento` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `commento`
--

INSERT INTO `commento` (`id_commento`, `utente`, `post`, `testo`, `data_commento`) VALUES
(20, 28, 98, 'Poco informativo.', '2020-09-01 19:01:16'),
(21, 29, 98, 'Sono d\'accordo. Potevi scrivere di più', '2020-09-01 19:01:58'),
(22, 29, 95, 'Interessante!!', '2020-09-01 19:02:43');

-- --------------------------------------------------------

--
-- Struttura della tabella `post`
--

CREATE TABLE `post` (
  `id_post` int(11) NOT NULL,
  `autore` int(11) NOT NULL,
  `titolo` varchar(80) NOT NULL,
  `testo` text NOT NULL,
  `data_creazione` date NOT NULL,
  `immagine` varchar(30) DEFAULT NULL,
  `blog` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `post`
--

INSERT INTO `post` (`id_post`, `autore`, `titolo`, `testo`, `data_creazione`, `immagine`, `blog`) VALUES
(94, 28, 'Rock', 'Il rock, o musica rock, è un genere della popular music sviluppatosi negli Stati Uniti e nel Regno Unito nel corso degli anni cinquanta e sessanta del XX secolo.', '2020-09-01', 'images/noimage2.png', 148),
(95, 28, 'Blues', 'Il genere musicale detto blues è una forma di musica vocale e strumentale la cui forma originale è caratterizzata da una struttura ripetitiva di dodici battute e in particolare, nella melodia, dell\\\'uso delle cosiddette blue note.', '2020-09-01', 'images/noimage2.png', 148),
(96, 29, 'Isole Eolie', 'Le Isole Eolie (Ìsuli Eoli in siciliano), dette anche Isole Lipari, sono un arcipelago appartenente all\\\'arco Eoliano situato nel Mar Tirreno meridionale, a nord della costa siciliana.', '2020-09-01', 'images/noimage2.png', 149),
(97, 29, 'Malta', 'Malta, ufficialmente Repubblica di Malta (in maltese Repubblika ta\\\' Malta, in inglese Republic of Malta), è uno stato insulare dell\\\'Europa meridionale, nonché lo Stato membro più piccolo dell\\\'Unione europea. ', '2020-09-01', 'images/noimage2.png', 149),
(98, 30, 'Piante grasse', 'Per la cura scegliete un luogo soleggiato e protetto dalle gelate.', '2020-09-01', 'images/noimage2.png', 151),
(99, 30, 'Pianta sempreverde', 'Richiede un terreno acido e non calcareo.', '2020-09-01', 'images/noimage2.png', 151),
(100, 28, 'Tecnica per principianti', 'Che cosa ti serve per iniziare? Colla vinilica, acqua, gel fissante per découpage. Poi avrai bisogno di pennelli, almeno due e di dimensioni diverse, soprattutto nel caso tu debba agire sia su superfici ampie, sia su dettagli. Naturalmente ti occorreranno anche pezzi di giornale, foto e ritagli', '2020-09-01', 'images/noimage2.png', 153);

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `id_utente` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` char(60) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `cognome` varchar(40) NOT NULL,
  `email` varchar(50) NOT NULL,
  `data_registrazione` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`id_utente`, `username`, `password`, `nome`, `cognome`, `email`, `data_registrazione`) VALUES
(27, 'fabio_98', '$2y$10$9A7VSSnJag0Xsz05BbU26eobw3/axzTLP4o1VE3uC4M4E3H0feWMK', 'fabio', 'armedo', 'fabiomarascia@gmail.com', '2020-09-01 15:34:42'),
(28, 'chiaragiu', '$2y$10$MDDz6zHtr6K9lS1GFGobxuNHMYWMBgPacX8fylze9zlx/WLuMS5Yi', 'Chiara', 'Giurdanella', 'chiaragiurdanella98@gmail.com', '2020-09-01 18:44:16'),
(29, 'nadiagiu', '$2y$10$LnRNB95j49aKeVHJ4uWdTeOnhLlXvsWMn4iIIkd9bCKat60s/tsbO', 'Nadia', 'Giurdanella', 'nadiagiurdanella@hotmail.it', '2020-09-01 18:44:57'),
(30, 'annarmeri98', '$2y$10$OS/639/3h/n4.NEanRkcIOcR9TEgxJiKKu.PDHzHPKH3ewbWmK5sq', 'Annalisa', 'Armeri', 'annalisaarmeri98@hotmail.it', '2020-09-01 18:45:29'),
(31, 'lucarossi88', '$2y$10$nRfGZgAMmyiqCiXq6pU1J.OUxI0RTPYgffQLIuMcGkiKO9qbrVQby', 'Luca', 'Rossi', 'lucaros88@hotmail.it', '2020-09-01 18:46:18');

-- --------------------------------------------------------

--
-- Struttura della tabella `valutazione`
--

CREATE TABLE `valutazione` (
  `id_valutazione` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `utente` int(11) NOT NULL,
  `recensione` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `valutazione`
--

INSERT INTO `valutazione` (`id_valutazione`, `post`, `utente`, `recensione`) VALUES
(13, 98, 28, 1),
(14, 95, 29, 4);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id_blog`),
  ADD KEY `autore` (`autore`),
  ADD KEY `categoria` (`categoria`) USING BTREE;

--
-- Indici per le tabelle `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indici per le tabelle `collaboratore`
--
ALTER TABLE `collaboratore`
  ADD PRIMARY KEY (`blog`,`utente`),
  ADD UNIQUE KEY `blog` (`blog`,`utente`),
  ADD KEY `utente` (`utente`) USING BTREE;

--
-- Indici per le tabelle `commento`
--
ALTER TABLE `commento`
  ADD PRIMARY KEY (`id_commento`),
  ADD KEY `post` (`post`),
  ADD KEY `utente` (`utente`);

--
-- Indici per le tabelle `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `blog` (`blog`),
  ADD KEY `Post_ibfk_2` (`autore`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`id_utente`);

--
-- Indici per le tabelle `valutazione`
--
ALTER TABLE `valutazione`
  ADD PRIMARY KEY (`id_valutazione`),
  ADD KEY `Recensioni_ibfk_1` (`post`),
  ADD KEY `recensore` (`utente`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `blog`
--
ALTER TABLE `blog`
  MODIFY `id_blog` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT per la tabella `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=278;

--
-- AUTO_INCREMENT per la tabella `commento`
--
ALTER TABLE `commento`
  MODIFY `id_commento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT per la tabella `post`
--
ALTER TABLE `post`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `id_utente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT per la tabella `valutazione`
--
ALTER TABLE `valutazione`
  MODIFY `id_valutazione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `Blog_ibfk_2` FOREIGN KEY (`autore`) REFERENCES `utente` (`id_utente`) ON DELETE CASCADE,
  ADD CONSTRAINT `Blog_ibfk_6` FOREIGN KEY (`categoria`) REFERENCES `categoria` (`id_categoria`) ON DELETE CASCADE;

--
-- Limiti per la tabella `collaboratore`
--
ALTER TABLE `collaboratore`
  ADD CONSTRAINT `Collaboratore_ibfk_1` FOREIGN KEY (`blog`) REFERENCES `blog` (`id_blog`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Collaboratore_ibfk_2` FOREIGN KEY (`utente`) REFERENCES `utente` (`id_utente`);

--
-- Limiti per la tabella `commento`
--
ALTER TABLE `commento`
  ADD CONSTRAINT `post` FOREIGN KEY (`post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `utente` FOREIGN KEY (`utente`) REFERENCES `utente` (`id_utente`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `Post_ibfk_2` FOREIGN KEY (`autore`) REFERENCES `utente` (`id_utente`) ON DELETE CASCADE,
  ADD CONSTRAINT `Post_ibfk_4` FOREIGN KEY (`blog`) REFERENCES `blog` (`id_blog`) ON DELETE CASCADE;

--
-- Limiti per la tabella `valutazione`
--
ALTER TABLE `valutazione`
  ADD CONSTRAINT `Valutazione_ibfk_1` FOREIGN KEY (`post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Valutazione_ibfk_2` FOREIGN KEY (`utente`) REFERENCES `utente` (`id_utente`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
