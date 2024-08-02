-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: 31 lug, 2012 at 07:48 PM
-- Versione MySQL: 5.1.36
-- Versione PHP: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mev`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `admin_pwd`
--

CREATE TABLE IF NOT EXISTS `admin_pwd` (
  `id_admin_pwd` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pwd` varchar(32) NOT NULL,
  PRIMARY KEY (`id_admin_pwd`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `admin_pwd`
--

INSERT INTO `admin_pwd` (`id_admin_pwd`, `pwd`) VALUES
(1, '5f4dcc3b5aa765d61d8327deb882cf99');

-- --------------------------------------------------------

--
-- Struttura della tabella `aliquote_iva`
--

CREATE TABLE IF NOT EXISTS `aliquote_iva` (
  `id_aliquota_iva` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `aliquota` float NOT NULL,
  PRIMARY KEY (`id_aliquota_iva`),
  UNIQUE KEY `nome_UNIQUE` (`nome`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `aliquote_iva`
--

INSERT INTO `aliquote_iva` (`id_aliquota_iva`, `nome`, `aliquota`) VALUES
(1, 'Standard', 0.21);

-- --------------------------------------------------------

--
-- Struttura della tabella `assoc_marchi_fornitori`
--

CREATE TABLE IF NOT EXISTS `assoc_marchi_fornitori` (
  `id_marchio` int(10) unsigned NOT NULL,
  `id_fornitore` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_marchio`,`id_fornitore`),
  KEY `fk_table1_fornitori1` (`id_fornitore`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `assoc_marchi_fornitori`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `attributi`
--

CREATE TABLE IF NOT EXISTS `attributi` (
  `id_attributo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_prodotto` int(10) unsigned NOT NULL,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`id_attributo`),
  KEY `fk_attributi_prodotti1` (`id_prodotto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `attributi`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `categorie`
--

CREATE TABLE IF NOT EXISTS `categorie` (
  `id_categoria` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) DEFAULT NULL,
  `descrizione` text,
  `id_categoria_padre` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_categoria`),
  KEY `fk_categorie_categorie1` (`id_categoria_padre`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `categorie`
--

INSERT INTO `categorie` (`id_categoria`, `nome`, `descrizione`, `id_categoria_padre`) VALUES
(1, 'Panche', 'Panche in legno e rame......', NULL),
(2, 'Ringhiere', 'Ringhiere in rame rosso...', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `costo_corriere_exp`
--

CREATE TABLE IF NOT EXISTS `costo_corriere_exp` (
  `id_costo_corriere_exp` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `provincia` varchar(45) NOT NULL,
  `da_peso` float NOT NULL,
  `a_peso` float NOT NULL,
  `costo` float NOT NULL,
  PRIMARY KEY (`id_costo_corriere_exp`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dump dei dati per la tabella `costo_corriere_exp`
--

INSERT INTO `costo_corriere_exp` (`id_costo_corriere_exp`, `provincia`, `da_peso`, `a_peso`, `costo`) VALUES
(1, 'SALERNO', 0, 22, 5),
(2, 'TUTTE', 0, 22, 5),
(3, 'TUTTE', 22, 50, 5),
(4, 'TUTTE', 50, 200, 10);

-- --------------------------------------------------------

--
-- Struttura della tabella `fatture`
--

CREATE TABLE IF NOT EXISTS `fatture` (
  `num_fattura` int(10) unsigned NOT NULL,
  `anno` int(4) NOT NULL,
  `data` date NOT NULL,
  `intestazione` text NOT NULL,
  `id_ordine` int(10) unsigned NOT NULL,
  PRIMARY KEY (`num_fattura`,`anno`),
  UNIQUE KEY `ordini_id_ordine_UNIQUE` (`id_ordine`),
  KEY `fk_fatture_ordini1` (`id_ordine`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `fatture`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `fornitori`
--

CREATE TABLE IF NOT EXISTS `fornitori` (
  `id_fornitore` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `denominazione` varchar(250) NOT NULL,
  `piva` varchar(11) NOT NULL,
  `provincia` varchar(250) NOT NULL,
  `citta` varchar(250) NOT NULL,
  `email` varchar(250) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `fax` varchar(45) DEFAULT NULL,
  `nome_banca` varchar(200) DEFAULT NULL,
  `iban` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_fornitore`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `fornitori`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `indirizzi_spedizione`
--

CREATE TABLE IF NOT EXISTS `indirizzi_spedizione` (
  `id_indirizzo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_utente` int(10) unsigned NOT NULL,
  `nome` varchar(45) NOT NULL,
  `cognome` varchar(45) NOT NULL,
  `indirizzo` varchar(150) NOT NULL,
  `citta` varchar(45) NOT NULL,
  `cap` varchar(5) NOT NULL,
  `provincia` varchar(45) NOT NULL,
  `ragione_sociale` varchar(150) DEFAULT NULL,
  `predefinito` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_indirizzo`),
  KEY `fk_indirizzi_spedizione_utenti` (`id_utente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `indirizzi_spedizione`
--

INSERT INTO `indirizzi_spedizione` (`id_indirizzo`, `id_utente`, `nome`, `cognome`, `indirizzo`, `citta`, `cap`, `provincia`, `ragione_sociale`, `predefinito`) VALUES
(1, 1, 'michele', 'dfnkjds', 'fdkjshfvndskjl', 'battipaglia', '84091', 'salerno', '', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `magazzino`
--

CREATE TABLE IF NOT EXISTS `magazzino` (
  `id_prodotto` int(10) unsigned NOT NULL,
  `attributi` varchar(45) NOT NULL,
  `qta` float NOT NULL,
  PRIMARY KEY (`id_prodotto`,`attributi`),
  KEY `fk_magazzino_prodotti1` (`id_prodotto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `magazzino`
--

INSERT INTO `magazzino` (`id_prodotto`, `attributi`, `qta`) VALUES
(1, '', 6);

-- --------------------------------------------------------

--
-- Struttura della tabella `marchi`
--

CREATE TABLE IF NOT EXISTS `marchi` (
  `id_marchio` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`id_marchio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `marchi`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id_news` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `titolo` varchar(250) NOT NULL,
  `descrizione` text NOT NULL,
  PRIMARY KEY (`id_news`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `news`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `ordini`
--

CREATE TABLE IF NOT EXISTS `ordini` (
  `id_ordine` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_utente` int(10) unsigned NOT NULL,
  `data` datetime NOT NULL,
  `codice_spedizione` varchar(20) NOT NULL,
  `indirizzo_spedizione` text NOT NULL,
  `costo_spedizione` float NOT NULL,
  `codice_pagamento` varchar(20) NOT NULL,
  `costo_pagamento` float NOT NULL,
  `indirizzo_ip` varchar(20) NOT NULL,
  `note` text,
  `note_admin` text,
  `data_pagamento` date DEFAULT NULL,
  `data_spedizione` date DEFAULT NULL,
  `traking_number` varchar(250) DEFAULT NULL,
  `stato_ordine` varchar(15) NOT NULL,
  PRIMARY KEY (`id_ordine`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `ordini`
--

INSERT INTO `ordini` (`id_ordine`, `id_utente`, `data`, `codice_spedizione`, `indirizzo_spedizione`, `costo_spedizione`, `codice_pagamento`, `costo_pagamento`, `indirizzo_ip`, `note`, `note_admin`, `data_pagamento`, `data_spedizione`, `traking_number`, `stato_ordine`) VALUES
(1, 1, '2012-06-21 20:33:00', 'express', 'fe am -  - Battipaglia 84091 (SALERNO) - via spiox', 0, 'C', 5, '127.0.0.1', '', '', '2012-10-09', NULL, '', 'IN_ATTESA'),
(2, 1, '2012-07-18 17:51:00', 'ritiro', 'dfnkjds michele -  - battipaglia 84091 (salerno) - fdkjshfvndskjl', 0, 'BB', 0, '127.0.0.1', '', '', NULL, NULL, '', 'IN ATTESA'),
(3, 1, '2012-07-31 19:41:00', 'ritiro', 'dfnkjds michele -  - battipaglia 84091 (salerno) - fdkjshfvndskjl', 0, 'BB', 0, '127.0.0.1', NULL, NULL, NULL, NULL, NULL, 'IN ATTESA');

-- --------------------------------------------------------

--
-- Struttura della tabella `ordini_items`
--

CREATE TABLE IF NOT EXISTS `ordini_items` (
  `id_ordine_item` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_ordine` int(10) unsigned NOT NULL,
  `id_prodotto` int(10) unsigned NOT NULL,
  `nome_prodotto` varchar(150) DEFAULT NULL,
  `descrizione_breve` text,
  `attributi` text,
  `prezzo_unitario` float DEFAULT NULL,
  `aliquota_iva` float DEFAULT NULL,
  `qta` float DEFAULT NULL,
  `prezzo_totale` float DEFAULT NULL,
  `peso` float DEFAULT NULL,
  `attivo` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_ordine_item`),
  KEY `fk_ordini_items_ordini1` (`id_ordine`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dump dei dati per la tabella `ordini_items`
--

INSERT INTO `ordini_items` (`id_ordine_item`, `id_ordine`, `id_prodotto`, `nome_prodotto`, `descrizione_breve`, `attributi`, `prezzo_unitario`, `aliquota_iva`, `qta`, `prezzo_totale`, `peso`, `attivo`) VALUES
(1, 1, 1, 'Panchina 1', 'Panchina in rame', '', 240.25, 0.21, 1, 290.703, 81, 1),
(2, 2, 1, 'Panchina 1', 'Panchina in rame', '', 240.25, 0.21, 1, 290.703, 81, 1),
(3, 2, 1, 'Panchina 1', 'Panchina in rame', '', 240.25, 0.21, 1, 290.703, 81, 1),
(4, 3, 1, 'Panchina 1', 'Panchina in rame', '', 240.25, 0.21, 1, 290.703, 81, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `prodotti`
--

CREATE TABLE IF NOT EXISTS `prodotti` (
  `id_prodotto` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `id_categoria` int(10) unsigned NOT NULL,
  `prezzo_netto` float NOT NULL,
  `id_aliquota_iva` int(10) unsigned NOT NULL,
  `descrizione_breve` text,
  `descrizione` text,
  `tipo_quantita` varchar(45) DEFAULT NULL,
  `quantita_select_inizio` int(11) DEFAULT NULL,
  `quantita_select_fine` int(11) DEFAULT NULL,
  `quantita_select_incremento` int(11) DEFAULT NULL,
  `tempo_disponibilita` varchar(45) DEFAULT NULL,
  `visualizzazione_qta` int(1) DEFAULT '1',
  `iva_inclusa` varchar(2) DEFAULT 'SI',
  `promo` int(1) DEFAULT '0',
  `sconto_promo` float DEFAULT '0',
  `peso` float DEFAULT NULL,
  `note` text,
  PRIMARY KEY (`id_prodotto`),
  KEY `fk_prodotti_categorie1` (`id_categoria`),
  KEY `fk_prodotti_aliquote_iva1` (`id_aliquota_iva`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `prodotti`
--

INSERT INTO `prodotti` (`id_prodotto`, `nome`, `id_categoria`, `prezzo_netto`, `id_aliquota_iva`, `descrizione_breve`, `descrizione`, `tipo_quantita`, `quantita_select_inizio`, `quantita_select_fine`, `quantita_select_incremento`, `tempo_disponibilita`, `visualizzazione_qta`, `iva_inclusa`, `promo`, `sconto_promo`, `peso`, `note`) VALUES
(1, 'Panchina 1', 1, 240.25, 1, 'Panchina in rame', 'Versatile panca in legno e rame\r\nOttima per esterni e interni', 'T', 0, 0, 0, '10 gg lavorativi', 1, 'SI', 0, 0, 81, 'Noteeeeeeee\r\neeeee'),
(2, 'panchina 1', 1, 250.5, 1, 'panchina in rame', 'versatile panca in legno e rame\r\nottima per esterni e interni', 'T', 0, 0, 0, '5gg lavorativi', 1, 'SI', 0, 0, 25, 'Noteeeeeeee\r\neeeee');

-- --------------------------------------------------------

--
-- Struttura della tabella `proprieta`
--

CREATE TABLE IF NOT EXISTS `proprieta` (
  `id_proprieta` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_attributo` int(10) unsigned NOT NULL,
  `valore` varchar(45) DEFAULT NULL,
  `variazione_prezzo` float DEFAULT NULL,
  PRIMARY KEY (`id_proprieta`),
  KEY `fk_proprieta_attributi1` (`id_attributo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `proprieta`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE IF NOT EXISTS `utenti` (
  `id_utente` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(32) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `cognome` varchar(45) NOT NULL,
  `codice_fiscale` varchar(16) NOT NULL,
  `piva` varchar(11) DEFAULT NULL,
  `indirizzo` varchar(150) NOT NULL,
  `citta` varchar(45) NOT NULL,
  `cap` varchar(5) NOT NULL,
  `provincia` varchar(45) NOT NULL,
  `ragione_sociale` varchar(150) DEFAULT NULL,
  `fax` varchar(45) DEFAULT NULL,
  `domanda` varchar(250) NOT NULL,
  `risposta` varchar(250) NOT NULL,
  PRIMARY KEY (`id_utente`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id_utente`, `email`, `username`, `password`, `nome`, `cognome`, `codice_fiscale`, `piva`, `indirizzo`, `citta`, `cap`, `provincia`, `ragione_sociale`, `fax`, `domanda`, `risposta`) VALUES
(1, 'a@a.it', 'arun', '722279e9e630b3e731464b69968ea4b4', 'am', 'fe', 'fr', '', 'via spiox', 'Battipaglia', '84091', 'SALERNO', '', '', 'domanda', 'risposta'),
(2, 'caizz', 'asa', '457391c9c82bfdcbb4947278c0401e41', 'a', 'b', 'c', '', 'viax', 'ba', '84091', 'sa', '', '', 'd', 'r');

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `assoc_marchi_fornitori`
--
ALTER TABLE `assoc_marchi_fornitori`
  ADD CONSTRAINT `fk_table1_fornitori1` FOREIGN KEY (`id_fornitore`) REFERENCES `fornitori` (`id_fornitore`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_table1_marchi1` FOREIGN KEY (`id_marchio`) REFERENCES `marchi` (`id_marchio`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `attributi`
--
ALTER TABLE `attributi`
  ADD CONSTRAINT `fk_attributi_prodotti1` FOREIGN KEY (`id_prodotto`) REFERENCES `prodotti` (`id_prodotto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `categorie`
--
ALTER TABLE `categorie`
  ADD CONSTRAINT `fk_categorie_categorie1` FOREIGN KEY (`id_categoria_padre`) REFERENCES `categorie` (`id_categoria`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `fatture`
--
ALTER TABLE `fatture`
  ADD CONSTRAINT `fk_fatture_ordini1` FOREIGN KEY (`id_ordine`) REFERENCES `ordini` (`id_ordine`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `indirizzi_spedizione`
--
ALTER TABLE `indirizzi_spedizione`
  ADD CONSTRAINT `fk_indirizzi_spedizione_utenti` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id_utente`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `magazzino`
--
ALTER TABLE `magazzino`
  ADD CONSTRAINT `fk_magazzino_prodotti1` FOREIGN KEY (`id_prodotto`) REFERENCES `prodotti` (`id_prodotto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `ordini_items`
--
ALTER TABLE `ordini_items`
  ADD CONSTRAINT `fk_ordini_items_ordini1` FOREIGN KEY (`id_ordine`) REFERENCES `ordini` (`id_ordine`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `prodotti`
--
ALTER TABLE `prodotti`
  ADD CONSTRAINT `fk_prodotti_aliquote_iva1` FOREIGN KEY (`id_aliquota_iva`) REFERENCES `aliquote_iva` (`id_aliquota_iva`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_prodotti_categorie1` FOREIGN KEY (`id_categoria`) REFERENCES `categorie` (`id_categoria`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `proprieta`
--
ALTER TABLE `proprieta`
  ADD CONSTRAINT `fk_proprieta_attributi1` FOREIGN KEY (`id_attributo`) REFERENCES `attributi` (`id_attributo`) ON DELETE CASCADE ON UPDATE CASCADE;
