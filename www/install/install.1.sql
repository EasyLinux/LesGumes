
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données : `Amap`
--

-- --------------------------------------------------------

--
-- Structure de la table `sys_db_update`
--

CREATE TABLE IF NOT EXISTS `sys_db_update` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant',
  `description` varchar(254) NOT NULL COMMENT 'Description maj',
  `sql_text` text NOT NULL COMMENT 'SQl',
  `version` varchar(16) NOT NULL COMMENT '1.1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='SQL resuqest for update';


--
-- Structure de la table `sys_menu`
--

CREATE TABLE IF NOT EXISTS `sys_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant',
  `parent` int(11) NOT NULL DEFAULT 0 COMMENT 'Menu parent',
  `ordre` int(11) NOT NULL,
  `droits` varchar(8) NOT NULL COMMENT 'Accès publique/prive',
  `name` varchar(16) NOT NULL COMMENT 'Nom du menu',
  `label` varchar(32) NOT NULL COMMENT 'Label à afficher',
  `link` varchar(64) NOT NULL COMMENT 'Lien à activer',
  `type` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Menu du bandeau';

--
-- Déchargement des données de la table `sys_menu`
--

INSERT INTO `sys_menu` (`id`, `parent`, `ordre`, `droits`, `name`, `label`, `link`, `type`) VALUES
(1, 0, 35, 'public', 'webmaster', 'Administration', 'Admin', 'up'),
(2, 0, 5, 'public', 'home', 'Accueil', 'Main', 'up'),
(3, 0, 10, 'public', 'about', 'De quoi s\'agit-il ?', 'DeQuoiIlSagit.php', 'up'),
(4, 0, 15, 'public', 'joinus', 'Nous rejoindre', 'nous_rejoindre.php', 'up'),
(5, 0, 20, 'public', 'producteurs', 'Nos producteurs', 'nos_producteurs.php', 'up'),
(6, 0, 25, 'public', 'recipes', 'Nos recettes', 'nos_recettes.php', 'up'),
(7, 0, 30, 'public', 'contact', 'Contacts', 'contacts.php', 'up'),
(8, 12, 55, 'public', 'livraisons', 'Planning livraisons', 'planningH.pdf', 'gauche'),
(9, 12, 60, 'public', 'rgpd', 'RGPD', 'includes/RGPD.pdf', 'gauche'),
(10, 12, 65, 'public', 'docs', 'Documents', 'telechargements.php', 'gauche'),
(11, 0, 50, 'public', 'infos', 'Espace INFOs', '#', 'gauche'),
(12, 11, 0, 'public', 'download', 'Téléchargements', '#', 'gauche'),
(13, 42, 85, 'public', 'producers', 'Accès Producteurs', 'acces_producteurs.php', 'gauche'),
(14, 42, 0, 'public', 'contracts', 'Informations contrats', '#', 'gauche'),
(15, 14, 100, 'public', 'agneau', 'Agneaux', 'info_amap.php?amap=amap_agneaux', 'gauche'),
(16, 14, 105, 'public', 'agrumes', 'Agrumes', 'info_amap.php?amap=amap_agrumes', 'gauche'),
(19, 14, 110, 'public', 'biere', 'Bières', 'documentation/bieres/contrat.pdf', 'gauche'),
(20, 14, 115, 'public', 'champignons', 'Champignons', 'info_amap.php?amap=amap_champignons', 'gauche'),
(21, 14, 0, 'public', 'chevre', 'Fromage de chèvre', 'info_amap.php?amap=amap_chevre', 'gauche'),
(22, 14, 0, 'public', 'kiwis', 'Kiwis', 'info_amap.php?amap=amap_kiwis', 'gauche'),
(23, 14, 0, 'public', 'legumes', 'Légumes', 'info_amap.php?amap=amap_legumes', 'gauche'),
(24, 14, 0, 'public', 'miel', 'Miel', 'info_amap.php?amap=amap_miel', 'gauche'),
(25, 14, 0, 'public', 'millet', 'Millet &amp; Lentilles', 'info_amap.php?amap=amap_millet', 'gauche'),
(26, 14, 0, 'public', 'oeufs', 'Oeufs et Poulets', 'info_amap.php?amap=amap_oeufs', 'gauche'),
(27, 14, 0, 'public', 'pain', 'Pain', 'info_amap.php?amap=amap_pain', 'gauche'),
(28, 14, 0, 'public', 'pates', 'Pâtes', 'info_amap.php?amap=amap_pates', 'gauche'),
(29, 14, 0, 'public', 'poissons', 'Poissons', 'documentation/poissons/contrat.pdf', 'gauche'),
(30, 14, 0, 'public', 'pommes', 'Pommes Poires Jus', 'info_amap.php?amap=amap_pommes', 'gauche'),
(31, 14, 0, 'public', 'viandes', 'Porc Boeuf Veau', 'info_amap.php?amap=amap_viandes', 'gauche'),
(32, 14, 0, 'public', 'lait', 'Produits laitiers', 'info_amap.php?amap=amap_produits_laitiers', 'gauche'),
(33, 14, 0, 'public', 'tisannes', 'Tisanes', 'documentation/tisanes/contrat.pdf', 'gauche'),
(34, 14, 0, 'public', 'tommes', 'Tommes', 'info_amap.php?amap=amap_tommes', 'gauche'),
(35, 42, 0, 'public', 'permanence', 'Permanences', '#', 'gauche'),
(36, 42, 0, 'public', 'commandes', 'Mes commandes', '#', 'gauche'),
(37, 35, 0, 'public', 'champignons', 'Champignons', 'permanences.php?amap=amap_champignons', 'gauche'),
(38, 35, 0, 'public', 'legumes2', 'Légumes', 'permanences.php?amap=amap_legumes', 'gauche'),
(39, 35, 0, 'public', 'lait2', 'Produits laitiers', 'permanences.php?amap=amap_produits_laitiers', 'gauche'),
(40, 35, 0, 'public', 'pommes', 'Pommes Poires Jus', 'permanences.php?amap=amap_pommes', 'gauche'),
(41, 36, 0, 'public', 'lait3', 'Produits laitiers', 'ma_cde_lait.php', 'gauche'),
(42, 0, 80, 'public', 'amap', 'Espace AMAP', '#', 'gauche'),
(43, 0, 35, 'public', 'adminRights.js', 'Droits', 'editRights.js', 'up');


--
-- Structure de la table `sys_menu_rights`
--

CREATE TABLE IF NOT EXISTS `sys_menu_rights` (
  `idMenu` int(11) NOT NULL,
  `idRights` int(11) NOT NULL,
  UNIQUE KEY `Uni_primary` (`idMenu`,`idRights`),
  KEY `fk_right` (`idRights`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Lien entre sys_menu et sys_right';


--
-- Structure de la table `sys_parameter`
--

CREATE TABLE IF NOT EXISTS `sys_parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `value` varchar(32) NOT NULL,
  `type` varchar(32) NOT NULL,
  `link` varchar(32) NOT NULL,
  `description` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `sys_right`
--

CREATE TABLE IF NOT EXISTS `sys_right` (
  `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant',
  `Label` varchar(32) NOT NULL,
  `Description` varchar(254) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `sys_right`
--

INSERT INTO `sys_right` (`Id`, `Label`, `Description`) VALUES
(1, 'Amapien', 'Utilisateur de l\'Amap'),
(2, 'Producteur', 'Producteur lié à l\'Amap'),
(3, 'Référent', 'Gestionnaire de l\'Amap'),
(4, 'Administrateur', 'Gestion du site intégrale'),
(5, 'Public', 'Accès publique');

-- --------------------------------------------------------

--
-- Structure de la table `sys_user`
--

CREATE TABLE IF NOT EXISTS `sys_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(32) NOT NULL,
  `Prenom` varchar(32) NOT NULL,
  `e_mail` varchar(64) NOT NULL,
  `Adresse` varchar(64) DEFAULT NULL,
  `Code_postal` char(5) NOT NULL,
  `Ville` varchar(64) NOT NULL DEFAULT '',
  `Telephone` varchar(14) DEFAULT NULL,
  `Tel_portable` varchar(14) DEFAULT NULL,
  `Date_inscription` datetime DEFAULT NULL,
  `Paiement` int(1) DEFAULT 0,
  `Date_paiement` date DEFAULT NULL,
  `Login` varchar(64) DEFAULT NULL,
  `Mot_passe` varchar(64) DEFAULT NULL,
  `Etat_asso` enum('SANS_CONTRAT','ACTIF','HONNEUR','BENEVOLE','LISTE_ATTENTE') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNI_N_PRN` (`Nom`,`Prenom`),
  UNIQUE KEY `UNI_MAIL` (`e_mail`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `sys_user`
--

INSERT INTO `sys_user` (`id`, `Nom`, `Prenom`, `e_mail`, `Adresse`, `Code_postal`, `Ville`, `Telephone`, `Tel_portable`, `Date_inscription`, `Paiement`, `Date_paiement`, `Login`, `Mot_passe`, `Etat_asso`) VALUES
(8, 'LEROY', 'Myriam', 'lamyriam.leroy@free.fr', '25, rue Alexandre Fourny', '44230', 'SAINT SEBASTIEN/LOIRE', '02 51 71 26 50', '06 42 12 05 14', '2008-08-22 00:00:00', 1, '2019-09-15', 'myriam.mairym', '18308c5e98b24365b0dc70f0efb8c7bb3db89e77', 'ACTIF'),
(44, 'ETEVE-GUISNEL', 'Alain-Carole', 'eteve.guisnel@wanadoo.fr ', '16 rue de l\'abbaye ', '44115', 'BASSE-GOULAINE', '02 40 06 20 82', '06 71 05 30 38', '2012-03-09 10:21:03', 1, '2019-09-15', 'eteve.guisnel@wanadoo.fr', 'c6a62d14d6dfc1d5c9dff4a19657c8bdad3a4730', 'ACTIF'),
(50, 'ARCHIDOIT', 'Claude et Tatiana', 'c.archidoit@orange.fr', '116 Croix des Fosses', '44115', 'BASSE-GOULAINE', '02 40 03 58 90', '07 82 10 17 72', '2008-08-22 00:00:00', 1, '2019-09-15', 'c.archidoit', '0b9c2625dc21ef05f6ad4ddf47c5f203837aa32c', 'ACTIF'),
(54, 'NICOLAS', 'Jean-Noël', 'jnnicolas@orange.fr', '3, bis rue des Plantes, St Séb ', '44230', 'SAINT SEBASTIEN/LOIRE', '06 73 39 21 86', '07 81 88 75 65', '2008-08-22 00:00:00', 1, '2019-09-15', 'jnnicolas', '61e97c978aa9f10b3a8ef8f1a7f0f5f663d83354', 'ACTIF'),
(55, 'PAYEN', 'Michèle', 'michele.payen@neuf.fr', '7 avenue des Maraicher', '44115', 'BASSE-GOULAINE', '02 40 80 70 67', '06 01 77 48 88', '2008-08-22 00:00:00', 1, '2019-09-15', 'michele.payen@neuf.fr', 'e1f2a544f52454bd64d57c648fbbfc8ed3d4de47', 'ACTIF'),
(62, 'CHAUSSEPIED', 'Elisabeth', 'chaussepiedge@orange.fr', '75, rue Maurice Daniel, St Séb', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 97 78 32', '06 12 38 74 15', '2008-08-22 00:00:00', 0, NULL, 'chaussepiedge@orange.fr', 'ae4457798a2ce2f3f907218e2867cf1435409143', 'ACTIF'),
(63, 'DANIBERT', 'Madeleine', 'madeleine.danibert@dbmail.com', '15 rue de Quebec, 44300 Saint sebastien', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 80 01 88', NULL, '2008-08-22 00:00:00', 1, '2019-09-15', 'madeleine.danibert', 'd5fc6bfcf27ff002fb45725331ab6652b8ce7277', 'ACTIF'),
(65, 'GRUAND', 'Anne', 'agruand@club-internet.fr', '3 rue Hendaye, ', '44200', 'NANTES\r\n', '02 40 03 09 28', '06 38 74 10 85', '2008-08-22 00:00:00', 0, '2018-10-02', 'annegruand', '45c8586a626ddabd233951066138d0efa7f4eb9d', 'ACTIF'),
(69, 'GUILLET', 'Jean Yves', 'sylvianeguillet@hotmail.fr', '5 rue azay le rideau', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 80 51 01', '06 42 85 47 08', '2008-08-22 00:00:00', 1, '2019-12-01', 'sylvianeguillet@hotmail.fr', 'f293f3fd169cb38b8f15695c28ac48bcbb5affba', 'ACTIF'),
(70, 'FILLAUD', 'Béatrice', 'menanteau-fillaud@orange.fr', NULL, '44230', 'SAINT SEBASTIEN/LOIRE', '', NULL, '0000-00-00 00:00:00', 0, NULL, 'bfillaud', '9e3230fdeb6c7cab0154356bc0e7294ccbea2831', 'ACTIF'),
(71, 'CHOLET', 'Guillaume', 'gcholet@yahoo.fr', '3 allée de serrant Saint seb', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 75 50 90 74', '2008-08-22 00:00:00', 1, '2019-09-15', 'gcholet', '05eb52d81134df5783b5fbec5f98fcce3c874ffe', 'ACTIF'),
(78, 'FOUILLOUX-GLASER', 'Agnès', 'joel.agneslsc@orange.fr', '23 rue de la Métairie', '44230', 'SAINT SEBASTIEN/LOIRE', '02 28 21 04 88', '06 56 83 45 12', '2008-08-22 00:00:00', 1, '2019-09-15', 'joel.agneslsc@orange.fr', 'c5c0c646594018a2b9bce579941109e9a0f5a34b', 'ACTIF'),
(79, 'NAULLEAU-POTREL', 'Laetitia', 'laetitia.guy@free.fr', NULL, '44230', 'SAINT SEBASTIEN/LOIRE', '', NULL, '2008-08-22 00:00:00', 0, NULL, 'laetitia', 'cd7f6d727b4f9ea465d517ca229e5798a21327b2', 'SANS_CONTRAT'),
(81, 'VONARX', 'Véronique', 'v.vonarx@yahoo.fr', '14 allée de la Savoie', '44230', 'SAINT SEBASTIEN/LOIRE', '02 51 71 27 85', '06 32 93 40 23', '2008-08-22 00:00:00', 1, '2019-09-15', 'v.vonarx@yahoo.fr', 'b67dc27afee4ff1ceadba14950417ef09061d8f3', 'ACTIF'),
(90, 'VINCENT', 'Anne Marie et Philippe', 'pam.vincent@laposte.net', '6 rue des prisonniers, saint seb ', '44230', 'SAINT SEBASTIEN/LOIRE', '02 51 79 00 80', '06 07 46 53 10', '2008-08-22 00:00:00', 1, '2019-09-15', 'pamvincent', 'c06ab205f2dcaee5779760276f77e3d95939de82', 'ACTIF'),
(100, 'TREGUIER-BRUMENT', 'Gwénaëlle', 'g.brument@wanadoo.fr', '15, rue du Mesnil, St Sébastien ', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 86 64 63', '06 20 15 03 96', '2008-12-01 00:00:00', 0, '2018-09-15', 'g.brument@wanadoo.fr', '5ada3b104bb5ce2be54ba288013b0982db497bfb', 'ACTIF'),
(125, 'FAVRIAU-PINON', 'Carole et Hervé', 'carole.favriau@laposte.net', '22 rue de la libération', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 63 91 32 66', '2009-08-04 21:08:16', 1, '2019-09-15', 'carole.pinon@laposte.net', '1299f5cc25bb5bbc27c60c743376d684bc91c73c', 'ACTIF'),
(126, 'ZUKOWSKA', 'Adrianna', 'zukowskaa@yahoo.fr', '3 bis, rue du Clos Rivière, St Seb ', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 80 23 79 73', '2009-08-06 20:34:46', 0, NULL, 'adrianna', '6a80eba8ec04ef53767dfbeb12d13c340c7a02f9', 'ACTIF'),
(153, 'ROGER', 'Philippe', 'famille.roger@orange.fr', '32 rue du Clos Torreau', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 33 27 44', NULL, '2012-04-27 11:27:41', 1, '2019-09-15', 'famille.roger@orange.fr', 'aef5549ddc5a767e365906475597631e50d60859', 'ACTIF'),
(156, 'GAUTIER', 'Frédérique', 'fredgotie@gmail.com', '5 rue des Palombes', '44230', 'SAINT SEBASTIEN/LOIRE', '02 28 24 92 08', '06 22 98 44 00', '2009-10-16 20:30:45', 0, '0000-00-00', 'fredgotie@gmail.com', 'fff9ec82a77456ed43a6c239eb5aab2681beb4d8', 'ACTIF'),
(162, 'LECLAIR', 'Blandine', 'blandine.leclair@free.fr', '6 impasse d\'Island', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 33 20 83', '06 74 54 35 77', '2010-01-30 00:41:52', 1, '2019-09-15', 'blandine.leclair@free.fr', '3731fb0256631688fc48871234140c96597302bc', 'ACTIF'),
(166, 'COMPAIN-PELLISSIER', 'Caroline', 'stephcaro@yahoo.fr', '5, allée Beau-Rivage', '44200', 'NANTES', '06 62 79 55 07', '06 61 17 89 40', '2010-04-14 17:40:37', 1, '2019-09-15', 'stephcaro@yahoo.fr', 'ecc0c8838711f1e45064913171ba4b74cbd3127b', 'ACTIF'),
(168, 'MONTIGNE', 'Annie', 'anniemontigne@wanadoo.fr', '46, rue des Mortiers, St Séb ', '44230', 'SAINT SEBASTIEN/LOIRE', '02 28 21 17 31', '06 33 22 03 40', '2010-05-16 19:28:49', 1, '2019-09-15', 'montigne', '4a6bfd1995bc16264467895891165bd2fc50fe95', 'ACTIF'),
(169, 'PRUVOST-GUIAVARCH', 'Rebecca', 'rebecca.pruvost@gmail.com', '7 rue Élisa Mercoeur; 44230 St Sébastien sur Loire ', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 03 33 96', '06 62 45 38 57', '2010-06-10 22:09:40', 0, NULL, 'rebe', '80395fdf6448afddc45123d18199677d05ff1d2b', 'ACTIF'),
(173, 'GRITLI-MARAIS', 'Michel', 'dreffeacnews@wanadoo.fr', '4 avenue des ondes ', '44200', 'NANTES', NULL, '06 64 75 37 99', '2010-08-07 14:00:56', 1, '2019-09-15', 'dreffeacnews@wanadoo.fr', '36c23df9f6338b86efe3dd3f76f62ad3fb55d1dc', 'ACTIF'),
(174, 'MOREAU', 'Didier', 'sodimoreau@free.fr', '1 impasse du Chatelier, Basse Goulaine', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 06 27 55', '06 49 95 86 42', '2010-08-11 19:40:33', 1, '2019-09-15', 'sodimoreau', '8be9a33d191430b8dc67bf8cf163bcec6bda4fce', 'ACTIF'),
(182, 'GUILLORE-VIAUD', 'Patrick et Cecile', 'patrick.guillore@wanadoo.fr;cecile.viaud44@orange.fr', '6 rue terre neuve', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 34 35 02', NULL, '2010-09-15 23:14:31', 1, '2019-09-15', 'GUILLORE_VIAUD', 'e2a1c8d95dedf7a0ac676958eaf6378e5aa197d3', 'ACTIF'),
(187, 'GUIMARD', 'Stephane et Hélène', 'bchsguimard@sfr.fr', '28 rue des fougères', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 56 00 69', NULL, '2010-09-19 23:08:33', 0, NULL, 'bchsguimard@sfr.fr', '223276bb2fbe43fe95e6934d348ec9e48867e708', 'SANS_CONTRAT'),
(198, 'PEAUDEAU', 'Rene', 'bretteurs@orange.fr', 'La Charrié ', '44650', 'LEGE', '', ' 06 22 82 45 6', '2010-10-09 17:24:39', -1, NULL, 'rene', 'fad9a0a6f25df623a055091fe7e403534c7e9536', 'HONNEUR'),
(199, 'BOILEAU', 'Famille', 'lafermederuble@orange.fr', 'Ruble', '44310', 'SAINT COLOMBAN', NULL, NULL, '2010-10-09 17:27:55', -1, NULL, 'lafermederuble@orange.fr', '2ecbaf8c1a5bea7d21ed7a13678d23236482aa3e', 'SANS_CONTRAT'),
(200, 'CLOTEAU', 'Christine et Bernard', 'christinecloteau@sfr.fr', '16 allée de Montrichard', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 03 41 11', '06 02 66 70 85', '2010-10-22 22:07:39', 1, '2019-09-15', 'christinecloteau@sfr.fr', '544774d796c03eb80d12ae69666e82e3ebe04a8e', 'ACTIF'),
(203, 'BRANCHEREAU', 'Marie', 'branchereaup2@wanadoo.fr', '38, rue de la Plume, St Séb', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 33 46 49', '06 02 50 67 50', '2010-11-05 20:58:04', 1, '2019-09-15', 'branchereaup2@wanadoo.fr', '0851d66fb9ff63c2dd67818f75b0bd4bd899ded3', 'ACTIF'),
(209, 'BRETHE', 'Pierre et Nicole', 'npbrethe@gmail.com', '40 rue des Bignons', '44230', 'SAINT SEBASTIEN/LOIRE', '02 51 79 04 75', '06 64 50 18 24', '2011-01-28 10:34:47', 1, '2019-09-15', 'nbrethe', '8ae66cd3d848b149928b6f6ff2dfc1166d2235f7', 'ACTIF'),
(212, 'CAROF', 'Yves', 'yves.carof@gmail.com', '5 rue des salicornes', '44200', 'NANTES', NULL, '06 83 02 77 81', '2011-05-03 23:09:08', 1, '2019-09-15', 'yvescarof', 'b98fdb4c717435a061a65ce469ae6efd02064b09', 'ACTIF'),
(213, 'GADET-MOREAU', 'Sandrine', 'gadet.sandrine@orange.fr', '64 rue henri barbusse', '44400', 'REZE', '09 53 42 82 35', '06 17 46 57 91', '2011-05-08 22:07:32', 1, '2019-09-15', 'gadet.sandrine@orange.fr', '409e35ea656c3933b6abc57a9707d95e53927d58', 'ACTIF'),
(218, 'LANSAC-CONNART', 'Fred', 'fredjfele@free.fr', '14 rue du bois des michées', '44230', 'SAINT SEBASTIEN/LOIRE', '09 54 97 98 18', '06 37 87 76 95', '2011-05-25 10:58:27', 1, '2019-09-15', 'fredjfele@free.fr', '9c05d513dbf02fa768db3e40d646b6ce416f3afe', 'ACTIF'),
(221, 'CHAUVIN-ROUSSEAU', 'Claire', 'cc.20@free.fr', '5 rue René Cassin', '44230', 'SAINT SEBASTIEN/LOIRE', '09 52 15 20 09', '06 01 63 31 55', '2011-06-27 00:33:17', 0, '2018-09-15', 'clairec20', 'a9bbf2cf47e321f2d011543019ea680308a2776f', 'ACTIF'),
(223, 'GIULIANI', 'Delphine', 'giulianidelphine@gmail.com', '70 rue du Gnl De Gaulle', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 61 83 25 59', '2012-03-02 18:09:39', 1, '2019-09-15', 'giulianidelphine', '11f7deb40b9ced5f06fe479853ff121a7e0badec', 'ACTIF'),
(227, 'MAYEUR', 'Catherine', 'cathmayeur44@orange.fr', '21 rue Georges Brassens, saint seb', '44230', 'SAINT SEBASTIEN/LOIRE', '02 51 71 22 67', '06 08 99 64 78', '2011-08-11 16:48:56', 1, '2019-09-15', 'cathmayeur@orange.fr', 'e440c9b8b4a43ed189988be81eadb1a4519f03ac', 'ACTIF'),
(229, 'AUGRY', 'Stephane', 'stephane.augry@gmail.com', '3 impasse du port au blé', '44400', 'REZE', '02 40 34 35 50', NULL, '2011-08-21 23:10:20', 1, '2019-09-15', 'stephaneaugry', '1d464a1e2aac38ca6dc4b0f635b2b53d871e157f', 'ACTIF'),
(233, 'PERROCHEAU-PESQUEUX', 'Ludivine', 'ludivine.pesqueux@hotmail.fr ', '4 rue de la comète, saint seb ', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 31 51 45', '06 20 32 56 78', '2011-09-01 11:07:51', 1, '2019-09-15', 'ludivine', '0d359deb804f3049e679a12e744ea9b5349e4109', 'ACTIF'),
(235, 'MEZIERE', 'Marie-José', 'mariejo.meziere@wanadoo.fr ', '22, rue JB & H Tendron', '44400', 'REZE', NULL, '06 87 01 50 05', NULL, 1, '2019-09-15', 'mariejo.meziere@wanadoo.fr', 'c997dac74457a1da7a4ed8d1662b7a1e54e90fd9', 'ACTIF'),
(237, 'DE CANTELOUBE', 'Hélène', 'h.violette@free.fr', '62 rue des écobuts, Saint seb', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 06 49 93', '06 89 12 42 76', '2011-09-09 18:24:08', 0, '2018-09-15', 'h.violette@free.fr', 'b73bdecbf018a6f6cae8f6a933e534b1c0c1fcc1', 'ACTIF'),
(249, 'AVENANT', 'Guylaine', 'guylaine@avenant.fr', '13 allée de la Savoie Saint Sebastien/loire', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 63 74 01 34', '2017-12-08 13:58:23', 0, NULL, 'guylaine@avenant.fr', '944dadb0719c1fcab151ebe435e75c9ca8670fec', 'ACTIF'),
(251, 'GUILLAUME', 'Nicolas', 'leslillois@wanadoo.fr', '8, impasse des roses de Noël', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 05 23 06', '06 71 08 04 39', NULL, 1, '2019-09-15', 'AING', 'dac868cb6003c642e6665959ef0d859c8c72f6fc', 'ACTIF'),
(252, 'RICHARD', 'Nathalie', 'nathalie.richard44@free.fr ', '4 allée de Montrichard 44230 Saint Sébastien sur loire  ', '44230', 'SAINT SEBASTIEN/LOIRE', '02 28 21 02 78', '06 82 63 07 60', NULL, 0, NULL, 'JUSTINE', 'b7abb56619ab7275722058edda857ae0f095fdc5', 'SANS_CONTRAT'),
(253, 'BABARIT', 'Annick', 'annick.babarit@free.fr', ' 5 rue du Jasmin , Saint Sébastien sur loire', '44230', 'SAINT SEBASTIEN/LOIRE', '02.40.03.39.23', '07 82 00 91 90', '2011-11-25 23:33:15', 1, '2019-10-18', 'annick-babarit@orange.fr', 'e128e7946aa731c04ca1db6dc85ca6c5519e0f49', 'ACTIF'),
(256, 'PISANI-MINGANT', 'Nolwenn', 'vpisani44@gmail.com', '67 rue des écobuts, 44230 Saint Sébastien sur Loire', '44230', 'SAINT SEBASTIEN/LOIRE', '09 52 86 22 81', '06 47 71 00 46', '2012-03-09 15:58:21', 1, '2019-09-15', 'vpisani44@gmail.com', '6149985bd5271ac00720fa04640ab85dc595e60e', 'ACTIF'),
(257, 'GEFFROY ', 'Claudine ', 'marine.lenaig@cegetel.net', '6 rue des Châtaigniers, Saint Seb', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 67 61 66 07', '2012-06-22 13:53:58', 0, NULL, 'marine.lenaig@cegetel.net', '849089cf47e0b588573715cb8e9d9ca8938fcc0b', 'SANS_CONTRAT'),
(261, 'TESSIER', 'Yannig', 'y_tessier@orange.fr', 'app A28, 22 rue du Moulin Soline', '44115', 'BASSE-GOULAINE', NULL, '06 83 11 79 18', '2012-03-02 22:51:43', 1, '2019-09-15', 'yannigtessier@orange.fr', 'b62b31a4feffa39f39eb986b5c08b0d3f372c136', 'ACTIF'),
(270, 'CHEVALLIER', 'Rachel', 'pascaletrachel@me.com ', '137 rue de la libération, Saint seb', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 34 23 81', '06 78 83 49 17', '2012-09-21 18:07:49', 0, NULL, 'rachel.cm@me.com', '17119dea0416f2f1d9460955a3a673c78187db6d', 'SANS_CONTRAT'),
(273, 'COLOC BANANE', 'Gautret Solène ', 'solene.gautret@gmail.com', '126 rue du général de gaulle 44230 saint sébastien sur loire ', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 02 07 65 60', '2014-09-13 12:08:43', 0, '2018-09-15', 'banane', '93ef5dde44b5cb1d8f3795982ee918c64b7114f6', 'SANS_CONTRAT'),
(274, 'BOULINGUEZ-LEGRAND', 'Anne', 'a.boulinguez@free.fr', '22 rue Elisa Merceour, Saint Seb', '44230', 'SAINT SEBASTIEN/LOIRE', '09 51 10 54 09', '06 51 29 10 28', '2012-10-05 16:13:59', 0, NULL, 'Boulinguez', '22ea1e43b85942f76849c8a63c558aced5f2987e', 'SANS_CONTRAT'),
(279, 'VOLLAND-DELAVAUD', 'Raphaëlle', 'rdelavaud@yahoo.fr ', '138 rue des déportés 44300 Saint Sebastien', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 09 76 41 33', '2013-02-02 15:51:50', 1, '2019-09-15', 'rdelavaud@yahoo.fr', '74ec9e0d2959dd826238f5f7d314cb545e415fc9', 'ACTIF'),
(280, 'BOULAIS', 'Stéphanie', 'calijaya@free.fr', '10 rue des Rogets 44230 Saint Sébastien', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 80 56 03', NULL, '2013-02-02 15:50:37', 0, NULL, 'jayadeva', '62b1cb3efe77e18f4a92b72cba7c4c40080f046e', 'SANS_CONTRAT'),
(285, 'CHAUVET', 'Frédéric', 'frederic.chauvet44230@gmail.com', '46 rue de la Baugerie, Saint Seb', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 38 36 82 12', '2013-03-08 20:20:10', 1, '2019-09-15', 'Fred44', '174586c8548aec1dcac05957c6085323f5c9ca8a', 'ACTIF'),
(288, 'NICOLLEAU', 'Karine', 'karine.nicolleau@neuf.fr', '36 rue de la Baugerie, 44230 Saint Seb', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 22 14 34 29', '2013-03-29 19:16:57', 0, NULL, 'karine.nicolleau@neuf.fr', 'b3016248f7c4cdce190a42d971820b74664d5d39', 'SANS_CONTRAT'),
(289, 'DOUILLARD', 'Serge', 'douillard.serge@wanadoo.fr', '17 rue de la fuinelle, 44230 Saint Seb', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 03 09 08', '06 85 79 23 74', '2013-03-29 19:19:22', 1, '2019-09-15', 'douillard.serge@wanadoo.fr', 'a2a94934f7dc5bc80032f19de311a288685f09fd', 'ACTIF'),
(293, 'COURTEMANCHE', 'Florence ', 'flo.courtemanche@voila.fr', '31 rue du Quebec Saint Seb', '44230', 'SAINT SEBASTIEN/LOIRE', '02 51 79 15 70', '06 51 15 50 14', '2013-04-12 00:00:00', 0, NULL, 'florence.courtem@gmail.com', '0aa51a4345da9774cb26c6b0779d06e01cc3ce53', 'SANS_CONTRAT'),
(295, 'JOBERT', 'Aurore', 'jobert.aurore@gmail.com', NULL, '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 64 88 61 85', NULL, 0, NULL, 'Aurore', '52a74fe928e1aef2eb55b6bddccb54ad4d10bc8c', 'SANS_CONTRAT'),
(296, 'LARRAZET-CHEPTOU', 'Aude', 'audelarrazet@hotmail.fr', '35 rue des mortiers', '44230', 'SAINT SEBASTIEN/LOIRE', '06 87 03 68 86', '06 88 72 31 21', '2013-05-24 12:36:14', 1, '2019-09-15', 'audelarrazet@orange.fr', 'ec02aa4867d7760d534c4a7834adbc96d7499779', 'ACTIF'),
(297, 'BOISSEL-JUGEAU', 'Annie', 'djugeau@free.fr', '231 rue de la Jarnigarnière', '44115', 'BASSE-GOULAINE', '02 40 06 02 47', '06 37 77 21 34', '2013-05-21 22:22:59', 0, '2018-09-15', 'djugeau@free.fr', '93b4732013e6865bb931ebf3ace1869ada6e3718', 'SANS_CONTRAT'),
(298, 'LAMOTTE', 'Fanny', 'fanny.lamotte@free.fr', '3 rue du Limousin', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 87 32 34 28', '2013-06-14 12:38:31', 0, NULL, 'ptipingoo', 'f1df1b6a6a9d10138eb0993b46d8c5479ea7174c', 'SANS_CONTRAT'),
(299, 'COUTAUD', 'Mélanie', 'melcoutaud@hotmail.fr', '1 bd Alexandre Milrand', '44200', 'NANTES', NULL, '06 72 90 03 83', '2013-06-14 12:40:55', 0, NULL, 'melcoutaud@hotmail.fr', 'ac1f6993376068df20768432ed870ae9d01ff475', 'SANS_CONTRAT'),
(301, 'DURAND', 'Tony', 'durandtony@free.fr', '63 Bis rue du douet', '44230', 'SAINT SEBASTIEN/LOIRE', '09 50 57 47 19', '06 52 18 28 71', '2013-06-14 19:32:59', 1, '2019-09-15', 'durand.tony@libertysurf.fr', '7d64c31aee7ef929d0250d7f206a53d13202c62b', 'ACTIF'),
(302, 'ROUSSEL', 'Charline', 'rousselcharline@orange.fr', '46 ter rue de la Baugerie 44230 Saint Seb ', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 04 13 85', NULL, '2013-06-28 17:42:07', 0, NULL, 'charline', 'd1b7acf0cbb531f983554cdc8532c6a0cb255f48', 'SANS_CONTRAT'),
(304, 'GREFFARD', 'Lydia', 'lydia@famille-greffard.fr', '15, rue Georges Brassens - 44230 Saint Sébastien sur Loire', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 80 67 46 62', '2013-09-21 15:21:12', 0, NULL, 'lydia@famille-greffard.fr', '15ca69a8eb8818f4c6d61d876394c060ccefaca1', 'SANS_CONTRAT'),
(305, 'LE TERRIEN', 'Sandrine', 'sandrineleterrien@gmail.com', '20, rue Pierre Bérégovoy', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 01 91 53 26', '2013-09-21 15:24:33', 0, NULL, 'leterrien', '3b8546d20649b2f576347e94ed5d9b5e59c15cfd', 'SANS_CONTRAT'),
(311, 'LE MOAL', 'Valérie', 'lemoal.valerie@bbox.fr', '123 rue de la libération', '44230', 'SAINT SEBASTIEN/LOIRE', '02 28 21 17 47', '06 26 97 37 65', '2014-02-26 15:50:41', 0, NULL, 'lemoal.valerie@bbox.fr', '7a8ed83488292020eb48fda89571ae7c1be4fca2', 'SANS_CONTRAT'),
(312, 'GOUIL-LUCAS', 'Isabelle', 'isabelle.gouil@gmail.com', '16 rue de la Gibraye', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 23 90 48 87', '2014-02-22 19:33:47', 1, '2019-09-15', 'Gouil', '4a36f92e6a46c76f6f0c4b0b7e9c76fa5bf1fe5c', 'ACTIF'),
(314, 'PAROIS-FAUQUEMBERGUE', 'Dominique', 'domfauque@gmail.com', '20 rue de la mutualité, Saint Seb', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 03 11 93', NULL, '2014-02-22 20:02:02', 0, NULL, 'domfauque@gmail.com', 'ff41cbe1200348f67b27ea05a9be3712683a0cf4', 'SANS_CONTRAT'),
(315, 'ROUAUX', 'Françoise', 'pascal.rouaux@wanadoo.fr', '19, rue des Saules Saint seb', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 34 32 39', '06 23 09 73 58', '2014-03-28 11:59:05', 1, '2019-09-15', 'pascal.rouaux@wanadoo.fr', 'f7927745265daaaa4e89eff37e5d719d666d463a', 'ACTIF'),
(316, 'DAGIER', 'Laetitia', 'laetitia.dagier@icloud.com', '42 rue des fresches', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 44 75 96 97', '2014-04-11 15:10:46', 1, '2019-09-15', 'laetitia.dagier@live.fr', '2634ced1b664568fa458a8e67dd6bcacf50f2992', 'ACTIF'),
(319, 'BOUCHEZ', 'Emilie', 'bouchezemilie@gmail.com', '62 rue du lieutenant Auge', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 04 82 81', '06 31 28 21 33', '2014-06-13 12:40:38', 1, '2019-09-15', 'bouchezemilie@hotmail.com', '22f768eadfa3f3b009233907f37dffe7da70888c', 'ACTIF'),
(321, 'BONNEAU', 'Brigitte ', 'bmbonneau@orange.fr', '31 rue de la Frémonière', '44115', 'HAUTE-GOULAINE', NULL, '06 15 40 55 33', '2014-06-20 18:04:29', 1, '2019-09-15', 'bmbonneau@orange.fr', 'd43e1993585fafc8ff096318a4403c8705af0195', 'ACTIF'),
(325, 'LOISON-TREVEDY', 'Catherine', 'loison.trevedy@laposte.net', '13 impasse Auguste Renoir', '44115', 'HAUTE-GOULAINE', '06 82 92 64 59', '06 73 40 90 35', '2014-09-26 10:06:40', 1, '2019-09-15', 'loison.trevedy@laposte.net', '707a62b12254c3790c0e3e5843091958a16aaabf', 'ACTIF'),
(326, 'BOHUON', 'Eléonore et Yannick', 'eleonore.bohuon@orange.fr', '12 rue des déportés à Saint Sébastien sur Loire', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 80 73 25', '06 08 31 89 41', '2015-09-04 19:41:17', 0, NULL, 'Eleonore.bohuon@orange.fr', '69278b2cfb7c410cb5037fdba6288765b9115e04', 'SANS_CONTRAT'),
(328, 'GUINNESS-DESCOMBES', 'Aurélie ', 'aureliedescombes1@gmail.com', '4, avenue Artémis', '44200', 'NANTES', '', '06 24 73 69 40', '2014-09-13 21:12:53', 0, NULL, 'aureliedescombes', 'c62e5cd3b1fc814396d4490492080bc7f5524b2c', 'SANS_CONTRAT'),
(329, 'SINQUIN', 'Nicolas', 'sinquingouret@gmail.com', '95 rue Jean Mermoz, Saint Seb', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 10 47 79 49', '2014-09-13 20:55:02', 1, '2019-09-15', 'nicosandrine', '31bb88d2a6fe1c27d80633cf2a09f10eae8b73a8', 'ACTIF'),
(334, 'GUILBAUD', 'Julien et Emeline', 'coignet.emeline@neuf.fr', '29 rue de la libération', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 58 68 43 61', '2015-02-20 12:42:41', 0, NULL, 'emejul', '40b4efaaa821a3b5b1282b8c60e1b82c3b05baae', 'SANS_CONTRAT'),
(335, 'LAUPIN', 'Solène et John', 'solene.laupin@gmail.com', '11 rue Alexandre Fourny', '44230', 'SAINT SEBASTIEN/LOIRE', '06 66 61 87 32', '06 66 61 94 29', '2015-01-31 12:58:29', 1, '2019-09-15', 'solene.laupin@gmail.com', 'f47cf31d7b2d55568215394982f43e7c1e503cf5', 'ACTIF'),
(336, 'CHARTIER', 'Edith', 'edith.tournier.chartier@gmail.com', '23 rue de la gendarmerie', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 80 34 67 06', '2015-02-27 16:11:07', 1, '2019-09-15', 'edith.tournier.chartier@gmail.com', '5ec70294440715269b3ccf91718518e5ab29be1a', 'ACTIF'),
(337, 'ALLIOUX', 'Nelly', 'nelallioux@gmail.com', '4 chemin des Goizieres', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 82 97 94 93', '2015-03-06 10:47:17', 1, '2019-09-15', 'Nelallioux', '71c1bb52cfc9f7dcc9e6e7a6aea594cadcfa4ad1', 'ACTIF'),
(338, 'TEIGNE', 'Christèle', 'christeleteigne@live.fr', '32 rue du Roussillon 44230 Saint Seb', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 75 68 60 92', '2015-03-20 22:20:30', 1, '2019-09-15', 'christeleteigne@live.fr', 'b37addb2ce5b2884e371a3514a2be1cd4e445620', 'ACTIF'),
(339, 'VIGOU', 'Mélanie', 'vigou.melanie@gmail.com', '11 rue de la Greneraie (bat 14°, Saint Seb', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 45 50 64 55', '2015-06-26 18:24:58', 0, NULL, 'melanie', 'cfb1e07ae91d78811f4cfef0d5fbda5f011e9507', 'SANS_CONTRAT'),
(341, 'BECK', 'Edwige', 'edwigebeck@hotmail.com', '38 rue de la libération Saint Seb', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 70 22 67 78', '2015-09-19 14:33:03', 0, NULL, 'ebeck', 'b0ef6d83fcabc12df10801ff731a0ef4f8491094', 'SANS_CONTRAT'),
(342, 'JAUFFRIT ', 'Bénédicte', 'benedictejauffrit@gmail.com', '', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 74 20 87 00', '2015-09-19 14:17:15', 0, NULL, 'benedictejauffrit@gmail.com', '508f9d771c99e06c0fc2e8fb32f51bafef300980', 'SANS_CONTRAT'),
(348, 'VRAND', 'Marie-Laure', 'mlvrand44@gmail.com', '9 allée des îles', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 73 25 84 88', '2015-08-28 15:37:45', 1, '2019-09-15', 'mlvrand@gmail.com', 'ee9dd1e9df1ce713a7e089da87105a22d34e7295', 'ACTIF'),
(353, 'PRADIER', 'Stéphanie', 'pradier.stephanie@free.fr', '14,rue de la Buissonnerie, St Seb', '44230', 'SAINT SEBASTIEN/LOIRE', '', '07 81 32 31 86', '2015-09-19 14:26:36', 1, '2019-09-15', 'CASSANDRA', '4318073dcdc8f26e59b593b82742153e71248c96', 'ACTIF'),
(355, 'DUPONT-BAZUREAU', 'Michel et Sabrina ', 'bazureaudupont@gmail.com', '47 rue de la fontaine', '44230', 'SAINT SEBASTIEN/LOIRE', '06 74 15 52 18', '06 98 18 91 32', '2015-09-18 12:05:51', 1, '2019-09-15', 'bazureaudupont@gmail.com', '0dc8ac442e6c38f9df6f2807df96f5e45d4147d5', 'ACTIF'),
(356, 'HERY', 'Gilbert', 'gilbert.hery@gmail.com', '9 rue d\'Acadie', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 33 17 98', '06 27 64 74 70', '2015-09-25 13:30:42', 1, '2019-10-05', 'gilbert.hery@gmail.com', '5ea34077c891725c1d2dbdf46be538a32eda5e63', 'ACTIF'),
(357, 'HANDFUS-PAULIN', 'Eléonore et Patrice', 'patricepaulin@sfr.fr', '10 rue des Jonquilles', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 18 57 90 17', '2015-10-02 15:31:41', 1, '2019-09-15', 'Eleonore', 'e2914a1dd3106de8f17bedea88172c8853040a26', 'ACTIF'),
(364, 'BARBET', 'Camille', 'camille.barbet@free.fr', '117 rue de la jaunaie; Saint Seb', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 83 21 37 27', '2015-10-30 16:53:22', 0, NULL, 'camille.barbet@free.fr', 'c7dce0ae6e8315fb397140741e5c18e459e42b22', 'SANS_CONTRAT'),
(379, 'FABRE', 'Sylvain', 'sylvainfabre@orange.fr', '60, rue Alexandre Fourny', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 77 40 61 96', '2016-03-05 00:00:00', 1, '2019-09-15', 'sylvainfabre@orange.fr', '1d8d4b2b6e5264f2d1e593e68b5169daa1ce5d03', 'ACTIF'),
(382, 'SAHNOUNE ', 'Karine', 'sahnounekarine44@gmail.com', '17 rue Henri Mainguet 44230 SAint Seb', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 60 32 99 59', '2016-10-14 13:26:13', 1, '2019-09-15', 'sahnounekarine44@gmail.com', 'd63b3db600099e0b8dc9de831410dbea7ca8d453', 'ACTIF'),
(383, 'PALACIO', 'Jessica', 'jessica.palacio@laposte.net', '33 bis rue JB Robert Saint Seb', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 13 03 74 19', '2016-10-14 13:28:42', 1, '2019-09-15', 'jessica.palacio@laposte.net', '0706573d84fa341306d0be80511737c8c4b1df29', 'ACTIF'),
(385, 'KERVALET ', 'Anne-Gaelle', 'annegaelle.kervalet@gmail.com', '18 rue de la source', '44115', 'BASSE-GOULAINE', '', '06 25 69 32 41', '2016-09-16 13:10:27', 0, NULL, 'annegaelle.kervalet@gmail.com', '63fa4a2f4e7063c2bcff579c2989efad09901306', 'SANS_CONTRAT'),
(387, 'SAHRAOUI PENNUEN', 'Laetitia', 'pennuen.laetitia@hotmail.fr', '77 rue des plantes', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 40 91 40 70', '2016-10-14 13:26:31', 1, '2019-09-15', 'laetitia_nazim', '33ea8956379c4d7e0a7b361b029a1761e40f81ab', 'ACTIF'),
(388, 'STANKOVIC', 'Evelyne ', 'evelyne.stankovic@gmail.com', '8 bis rue du Jasmin 44230 Saint Sebastien sur Loire', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 17 15 67 75', '2016-09-09 22:37:00', 1, '2019-09-15', 'D0472189i0', '28c7ca086acad506ebd02f9c751fc5c81c61bece', 'ACTIF'),
(391, 'LAMBOUR', 'Julie', 'judolambour@gmail.com', '36 rue du sergent thierry', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 44 18 39 29', '2016-10-14 13:29:31', 1, '2019-09-15', 'judolambour@gmail.com', 'e5cca8dd8417fef84494fa9a278068636ed8d4f8', 'ACTIF'),
(395, 'BERTHEREAU', 'Soline', 'soline.berthereau@gmail.com', '', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 47 89 72 82', NULL, 0, NULL, 'soline.berthereau@gmail.com', '22e843cf9b0369974a8bed245c740f5acf31ccc6', 'LISTE_ATTENTE'),
(396, 'PEAUDEAU', 'Laurent et Vanessa', 'vpeaudeau@free.fr', '12 avenue A. Duez 44230 Saint Seb', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 07 82 05 36', '2016-10-09 20:11:50', 1, '2019-09-15', 'vpeaudeau@free.fr', '1019f41bb2231d3b09135dbf6035e8f23e4361e9', 'ACTIF'),
(399, 'REMY-SUMYUEN', 'Nadine', 'trarieux.remy@wanadoo.fr', '15 rue du Docteur Paul Michaux 44230 Saint Seb', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 85 87 37 64', '2016-10-14 13:29:12', 0, NULL, 'NADREM', 'de02accf2da54e48b56efc16cf0504e6415ec00c', 'SANS_CONTRAT'),
(400, 'MARIOT', 'Delphine', 'mariotfamily@free.fr', '40 avenue MOZART', '44115', 'BASSE-GOULAINE', '02 28 21 94 50', '06 26 91 85 53', '2016-10-15 23:18:20', 0, NULL, 'mariotfamily@free.fr', 'df2392183c3b009d46bdc9873a67eb33f54867df', 'ACTIF'),
(401, 'MARTINEAU', 'Christophe et Nathalie', 'christof.priv@gmail.com', '10 rue de Bretagne, 44230 ST SEB', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 47 37 41 97', '2016-10-15 23:22:53', 1, '2019-09-15', 'martichler', '500cffea9bdf03dcf07c912e8b3396f64cf679ff', 'ACTIF'),
(402, 'FRAPPIER', 'Patricia', 'patricia.frappier@wanadoo.fr', '3 rue des rouleaux', '44115', 'BASSE-GOULAINE', '06 89 77 35 55', '06 78 71 40 96', '2016-10-14 18:12:08', 1, '2019-09-15', 'frappierp', '7bb3cc9cd1d41a244a0e9e00cf7430bff6c4b5c8', 'ACTIF'),
(403, 'DELBOS', 'Laurence', 'lauthi14@hotmail.fr', '51 bis, rue du Largeau 44230 Saint Sébastien sur Loire', '44230', 'SAINT SEBASTIEN/LOIRE', '09 53 81 27 08', '06 10 67 28 44', '2016-10-28 23:53:20', 1, '2019-09-15', 'lauthi14', '6a7b6cf726773281ea5df74f6128d4a99349c046', 'ACTIF'),
(406, 'BEZIER', 'Annabelle', 'a_holigner@hotmail.com', '4 rue Thomas Maison Neuve Saint Seb', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 81 60 52 85', '2017-01-13 22:04:53', 0, '2018-10-05', 'a_holigner@hotmail.com', 'fd61014402965ea8bc2498db70523cc055950cd9', 'SANS_CONTRAT'),
(407, 'BASTOGY-MALHERBE', 'Lorette', 'lorettemalherbe@yahoo.fr', '45 rue de la comète 44230 SAint Sebastien sur loire', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 17 84 55 15', '2017-03-17 21:57:32', 0, NULL, 'lorettemalherbe@yahoo.fr', '9be0dbf60e56bd0372ef11852ce4be57681c1e85', 'SANS_CONTRAT'),
(408, 'ISNARD', 'Frédérique', 'feuraid@hotmail.fr', '5 rue de la comete', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 71 11 32 48', '2017-03-24 17:35:04', 1, '2019-09-15', 'feuraid', '329a03c9a02d0f5da100feed82e2ef5909606719', 'ACTIF'),
(409, 'GAREL', 'Sylvie et Philippe', 'sylvie.garel@free.fr', '1 rue des Rouges Gorges', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 03 28 30', '06 95 87 37 65', '2017-05-12 11:15:53', 0, '2018-10-05', 'sylvie.garel@free.fr', 'c2e9886543dfe0986ce1e9c0bb3553be7c2150ab', 'ACTIF'),
(410, 'FOUILLET-CHOLLET', 'Simon', 'simon.fouillet@gmail.com;chollet.mathilde@gmail.com', '3 place des muses', '44230', 'SAINT SEBASTIEN/LOIRE', '06 47 59 81 91', '06 71 74 78 43', '2017-06-02 22:22:11', 1, '2019-09-15', 'simon.fouillet@gmail.com', 'c86514b0e04d247a4acdedd5d72c879fdde2638d', 'ACTIF'),
(411, 'BENABED-MARTINEAU', 'Sylvanie ', 'sylvanie.martineau@gmail.com', '27 rue de la gibraye, 44230 SAINT SEBASTIEN S/LOIRE', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 83 30 80 72', '2017-05-19 23:31:53', 1, '2019-09-15', 'sissimoi', 'c74395bd12c521d0291b0df17da390f92f455115', 'ACTIF'),
(413, 'COTEL', 'Sylviane', 'sylviane.cotel@orange.fr', '10 rue des chateigniers St Seb', '44230', 'SAINT SEBASTIEN/LOIRE', '', '', '2017-09-16 19:29:06', 1, '2019-09-15', 'sylviane.cotel@orange.fr', 'ab68827e09fe7c64e477ef4976e8a4f196a6977a', 'ACTIF'),
(415, 'BOUGEARD', 'Blandine', 'blmarty@yahoo.fr', '18 rue des Martins', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 61 46 36 28', '2017-09-16 19:28:49', 1, '2019-09-15', 'blmarty', 'df936cfc62e0786386aa43bf36df29f34b826ae9', 'ACTIF'),
(416, 'JOUANNY', 'Michel', 'jouanny.michel@wanadoo.fr', '21 rue du petit clos', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 40 18 21 05', '2017-09-16 19:28:05', 1, '2019-09-15', 'mijou44', '429c291f711ecb67d35e5a0ca5d7f79bb90bc78f', 'ACTIF'),
(417, 'BOUCARD-BAUDRY', 'Stéphanie', 'stephanie.bb@free.fr', '14 rue des chênes', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 34 01 87', NULL, '2017-08-04 17:19:18', 1, '2019-09-15', 'stephanie.bb', '23918efe108c817398b303505d23fdca62d55c91', 'ACTIF'),
(418, 'BONNAT', 'Géraldine', 'bonnat.geraldine@gmail.com', '&\' bd de l\'Europe, saint Seb', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 61 24 43 90', '2017-09-16 19:27:47', 0, '2018-09-15', 'bonnat', '45c8586a626ddabd233951066138d0efa7f4eb9d', 'SANS_CONTRAT'),
(419, 'ALLARD', 'Marie-Françoise', 'mfallard@free.fr', 'Bat A 40 bis rue Lt Marty', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 80 01 88', '06 44 02 95 45', '2016-09-16 18:23:35', 1, '2019-09-15', 'mfallard@free.fr', '83c321ff87aef5deaee69d5b6d951cbe5504e731', 'ACTIF'),
(420, 'LIVET', 'Hélène', 'h_livet@yahoo.fr', '2 rue du Morvan 44230 St Seb', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 40 39 92 15', '2017-09-16 19:27:28', 1, '2019-09-15', 'h_livet@yahoo.fr', 'd62d52acfb19876269216260c32e5e53e1f82650', 'ACTIF'),
(421, 'DUVAL', 'Chantal ', 'chantal.duval1@free.fr', '5 rue de la Caillerie 44230 St Seb', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 64 03 30 85', '2017-09-16 18:51:46', 0, NULL, 'chantal-duval1@free.fr', '7b2ac0faee2346d99a5d9a7c4e652482039ab5e9', 'ACTIF'),
(422, 'LOISEAU', 'Sophie', 'sophieloiseau1@gmail.com', '6 rue du Cher 44230 Saint Seb', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 23 40 65 53', '2017-09-16 19:20:04', 1, '2019-09-15', 'loiseau', '24328dbe4eccad53a36cce52965cb99c9b4b2555', 'ACTIF'),
(423, 'LORIEUL', 'Sylvie', 'sylvie.lorieul@laposte.net', '7 avenue des Gobelets ', '44200', 'NANTES', NULL, '06 31 08 73 13', '2017-09-16 19:21:06', 1, '2019-09-15', 'sylvie.lorieul@laposte.net', 'bac4c9e36be477c0ae0c799079654fc30361a79a', 'ACTIF'),
(424, 'LAMPRIERE-RABILLER', 'Hélène', ' helene.lampriere@gmail.com', NULL, '44230', 'SAINT SEBASTIEN/LOIRE', NULL, NULL, NULL, 0, NULL, ' helene.lampriere@gmail.com', '7c3ed83614284279b9afcc69a1944fc7ebc335bd', 'SANS_CONTRAT'),
(425, 'COLIN', 'Christine', 'cricol44@gmail.com', '6 rue des grands Noëls', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 71 74 74 96', '2017-09-16 19:27:54', 1, '2019-09-15', 'cricol44gmail.com', 'c876b8552b9ea7514cfdd8f5e9d666e7785c793a', 'ACTIF'),
(426, 'VIGIER', 'Valérie', 'valerie.magvigier@gmail.com', '21 rue des Harengs 44230 St Seb', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 31 90 35 29', '2017-09-16 19:29:51', 1, '2019-09-15', 'valerie.vigier-mag@wanadoo.fr', 'b9627a6122973e1daa1c1bb213c073e05ef70c51', 'ACTIF'),
(427, 'DHERUEZ', 'Gwénaëlle', '56gwen@laposte.net', '11 avenue Armand Duez 44230 St Seb', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, NULL, '2017-09-16 22:08:10', 0, NULL, '56gwen', 'f16d470929ad88cc780c76850d0bd88747cd713a', 'SANS_CONTRAT'),
(428, 'PERROCHEAU', 'Berti', 'berti.perrocheau@gmail.com', '5 rue d\'Euterpe 44230 St Seb', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 04 90 22', '06 07 75 55 46', '2017-09-16 22:14:45', 1, '2019-09-15', 'berti.perrocheau@gmail.com', '30abb9f007def0e506275242d9a7a2f873c8bafd', 'ACTIF'),
(429, 'BOISRAME', 'Emilie', 'emilie.boisrame44@gmail.com', NULL, '44230', 'SAINT SEBASTIEN/LOIRE', '06 07 75 55 46', NULL, '2017-09-16 22:16:33', 1, '2019-09-15', 'emilie.boisrane44@gmail.com', 'fb5e17f41f117a2e934832e4b07e9349ef6bf431', 'ACTIF'),
(430, 'BAYON', 'Jean-Paul et Elisabeth', 'babette.bayon@orange.fr', '27 rue du largeau', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 31 46 60', '06 33 14 26 73', '2017-09-16 22:29:17', 1, '2019-09-15', 'jpbbayon', '220253ac20151d7a35febfaaa82b2cd26770546a', 'ACTIF'),
(431, 'AUCLERC-MORIN', 'Aurélie et Cédric', 'aauclerc@yahoo.fr', '5 rue de la greneraie', '44230', 'SAINT SEBASTIEN/LOIRE', '06 78 29 67 41', '06 74 03 67 10', '2018-09-15 18:30:02', 1, '2019-09-15', 'aauclerc@yahoo.fr', '76c48dd65414980b420648be740c0925e38d4547', 'ACTIF'),
(432, 'MENAGER-LANNUZEL', 'Marianne', 'mamenager@yahoo.fr', '20 rue de la Gibraye', '44230', 'SAINT SEBASTIEN/LOIRE', '06 24 49 53 43', '06 60 82 83 62', '2017-09-29 15:56:33', 1, '2019-09-15', 'mamenager@yahoo.fr', 'ed6f05c2b8eed845b947d084affef5ee4163d69a', 'ACTIF'),
(434, 'HORNN - KAGAN', 'Marie-Pierre et Claude', 'mphornn@gmail.com', '63 rue Maurice Daniel', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 95 69 17 50', '2017-10-13 22:32:12', 1, '2019-09-15', 'mphornn@gmail.com', '95265630c9430fd01d2ba7baafd7b63387793c9b', 'ACTIF'),
(435, 'JEANNEAU', 'Maurice et Maryvonne', 'mauricejeanneau@gmail.com', '20, rue des palombes', '44230', 'SAINT SEBASTIEN/LOIRE', '02 28 00 47 73', '06 79 26 96 61', '2017-10-20 12:10:32', 1, '2019-09-15', 'mauricejeanneau@gmail.com', 'a4035e7b00b7d66bb8e14428d9e96b21e82cb249', 'ACTIF'),
(436, 'BUGEAUD-HERVE', 'Cécile et Emmanuel', 'ccilebugeaud@gmail.com;emmanuelherve52@gmail.com', '5 rue de la Mayenne', '44230', 'SAINT SEBASTIEN/LOIRE', '06 83 52 05 31', '06 66 74 39 05', '2016-10-07 19:42:46', 0, NULL, 'Cecilemanu', '7ac17c9bcb2066e0788386f24cf9b345aebc0495', 'ACTIF'),
(437, 'GUILBAUD-NOBILET', 'Emmanuel et Camille', 'emmanuel.guilbaud44300@gmail.com', '56 rue du Général de Gaulle', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 23 28 21 73', '2017-11-24 15:14:49', 1, '2019-09-15', 'camilleetemmanuel', '145dbdc5aee16b3f5d87773eefe385faa7053294', 'ACTIF'),
(438, 'LE PECHOUX', 'Hélène ', 'slepechoux@gmail.com', '9, rue des Prés Naux, St Séb', '44230', 'SAINT SEBASTIEN/LOIRE', '06 51 39 78 10', '06 77 63 72 72', '2018-02-23 22:19:15', 1, '2019-09-15', 'slepechoux@gmail.com', '3dfcdfb1805c2d702892c64d1c2470cd7c67739c', 'ACTIF'),
(439, 'CORRE', 'Adélina', 'adelina.corre@gmail.com', '19 rue des Harengs', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 15 49 23 35', '2018-03-02 18:49:26', 1, '2019-09-15', 'adelina.corre@gmail.com', '3c8ab2bb4056f00c89912d90ed8963ce2af36bcc', 'ACTIF'),
(440, 'COCHIN', 'Benjamin', 'bcochin993@gmail.com', '22 Allée de Clermont', '44850', ' LE CELLIER', NULL, '07.68.46.48.00', '2018-03-23 19:55:48', -1, NULL, 'bcochin993@gmail.com', '845bf6a500ac41e3869905224037b08878c16b2f', 'HONNEUR'),
(441, 'CASTERS', 'Lucie', 'luciecasters@hotmail.fr', '3 rue Paul Edouard Lynch', '44115', 'BASSE-GOULAINE', NULL, '06 66 54 63 86', '2018-04-20 14:52:45', 0, NULL, 'Casters', '56ac7b60cd389470dc4b02efe9f49dbcfef32844', 'SANS_CONTRAT'),
(442, 'VEAU', 'Florence', 'flovea@orange.fr', '25 rue de la Métairie 44230 ST SEBASTIEN SUR LOIRE.', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 26 86 13 60', '2018-05-07 18:04:34', 1, '2019-09-15', 'flovea@orange.fr', '1f7877402ad902e51d3e1b87cad8bfa0851e3305', 'ACTIF'),
(443, 'GUERIN-DAVID', 'Marion et Francki', 'marionnetteguerin@hotmail.fr', '34 rue jean baptiste Lulli', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 20 56 07 65', '2018-05-18 18:30:02', 1, '2019-09-15', 'marionnetteguerin@hotmail.fr', '405270e978bfe03f0f9a70632ae5614be0fa8e15', 'ACTIF'),
(444, 'TOSONI-BOUDAUD', 'Delphine', 'delphine.tosoni@free.fr', '19 passage Launay Sillay', '44115', 'BASSE-GOULAINE', '', '07 81 35 09 94', NULL, 1, '2019-09-15', 'delphine.tosoni@free.fr', '95d93b60fc10667ffb1b5c37d740206a3e617f6c', 'ACTIF'),
(445, 'SERIGNAC', 'Anna', 'anna.serignac@wanadoo.fr', 'saint Seb', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 10 48 39 52', NULL, 1, '2019-09-15', 'anna.serignac@wanadoo.fr', 'dfc0ed43819fef1baa2fb93b4013f8da40d1776c', 'ACTIF'),
(446, 'GILLOIS', 'Lydie', '44glydie@gmail.com', 'App A01 - 60 rue de la profondine', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 60 71 43 05', NULL, 1, '2019-09-15', 'gidie@orange.fr', '022e6f79291d91e36b576cd2c08e80784ecd796f', 'ACTIF'),
(452, 'LASSALLE', 'Christine', 'christinelassalle57@orange.fr', '12 Bd de l\'Europe', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 87 64 59 22', '2018-09-15 19:05:39', 1, '2019-09-15', 'christinelassalle57@orange.fr', 'b0bb83a61d30a17a0e3d2173ddcbad40578667b9', 'ACTIF'),
(456, 'LACOTE', 'Lydie et Eric', 'eric.lydie44@gmail.com', '12 rue Baptiste Marcel', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 72 70 23 32', NULL, 1, '2019-09-15', 'eric.lydie44@gmail.com', '10f3e8041a36702b3c9b2d136cc1fd4126c53e2b', 'ACTIF'),
(457, 'DUMONT', 'François et Aurore', 'wearedumont@gmail.com', '7 rue de la métairie St Seb', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 70 49 78 25', '2018-09-15 18:58:00', 0, '2018-09-15', 'wearedumont@gmail.com', '8ac50e6454e39f702b00b3edfc4eeeee84bb1f49', 'SANS_CONTRAT'),
(458, 'CHILLOUX', 'David et Mélissa', 'public@davius.net', '9 rue de la Chaponnerie', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 72 36 25 62', NULL, 1, '2019-09-15', 'public@davius.net', 'd27df621751b2cedff3291a53f7d343e40f30f1f', 'ACTIF'),
(459, 'MARTINEAU-VERSILLER', 'Christine', 'cc.martineau@sfr.fr', '17 rue de la Jarnillerie', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 95 60 18 29', '2018-09-07 00:23:58', 1, '2019-09-15', 'cc.martineau@sfr.fr', 'cf9a0189c908bcfdbc2314ac80337855c87e6fe9', 'ACTIF'),
(461, 'BOUREAU ', 'Elise', 'eliseboo@free.fr', '2 bis rue des robardières 44230 St Seb', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 82 94 41 20', '2018-08-24 00:00:00', 0, '2018-09-15', 'eliseboo@free.fr', '39f6b922bdf8466f7e13b2a75bc23664a3ae171b', 'SANS_CONTRAT'),
(462, 'MAZALEYRAT', 'Véronique ', 're.ve2@orange.fr', '10 rue jean Baptiste Robert 44230 St Seb', '44230', 'SAINT SEBASTIEN/LOIRE', '02 40 03 16 76', '06 09 82 35 29', NULL, 1, '2019-09-15', 're.ve2@orange.fr', '7f783b4e0108abdc73536261331f9f997a7bed94', 'ACTIF'),
(463, 'GENOIST', 'Adeline et Xavier', 'adelineetxavier2016@gmail.com', '5 rue des Ajoncs', '44230', 'SAINT SEBASTIEN/LOIRE', '06 89 99 14 11', '06 67 69 47 91', NULL, 1, '2019-09-15', 'adelinexavier2016@gmail.com', 'acb7a7e22a5e78d03cac6a52b016f3257e5989a8', 'ACTIF'),
(464, 'CHAUVEAU', 'Margot', 'margotchauveau@hotmail.com', '', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 65 05 05 67', NULL, 0, NULL, 'margotchauveau@hotmail.com', 'e351e59d1b039948d31d08ce56256ce05b0213cc', 'ACTIF'),
(465, 'ARNOULT', 'Carole', 'carole.arnoult4@gmail.com', '15 rue de la croix Sourdeau St Seb', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 62 25 45 92', '2018-09-15 18:27:40', 0, '2018-09-15', 'carole.arnoult', '0edd13a463043904664d033f9df93bcc834084ec', 'ACTIF'),
(466, 'CHAUVEAU', 'Nathalie et Olivier', 'nachauveau@hotmail.fr', '21 rue du bois Praud', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 19 18 00 04', '2018-09-15 00:00:00', 0, '2018-09-15', 'nath-chauveau@hotmail.fr', '02ff53eb94b25da097b29a3b77818f519991ee04', 'ACTIF'),
(467, 'FILY', 'Laurence', 'laurence.fily@wanadoo.fr', '15 rue de la fuinelle ', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 06 44 93 28', '2018-09-15 00:00:00', 0, '2018-09-14', 'laurence.fily@wanadoo.fr', '429b6b21d94ec1d674dfd7c0427a4cc65b457f12', 'SANS_CONTRAT'),
(468, 'JOLY', 'Frédéric et Orlane', 'orfi2@free.fr', '27 rue de la croix des fosses', '44115', 'BASSE-GOULAINE', '', '', '2018-09-15 00:00:00', 0, '2018-09-15', 'orfi2@free.fr', 'dc15c373aae27c6c14422821383064c09dcfda93', 'SANS_CONTRAT'),
(470, 'ARNAUD', 'Marion', 'marion_casters@orange.fr', '4 impasse des Ibères', '44840', 'LES SORINIERES', '', '06 70 86 56 84', '2018-09-16 00:00:00', 0, NULL, 'marion_casters@orange.fr', 'e5f57dd899ea2a2e4a38000a4f1ee0b530d1fafe', 'SANS_CONTRAT'),
(471, 'POITEVIN', 'Claire', 'claire.poitevin@outlook.com', '', '44230', 'SAINT SEBASTIEN/LOIRE', '', '', NULL, 0, NULL, 'claire.poitevin@outlook.com', '4a617ce2c644cb4147fb03eaf52abc6c0a631917', 'LISTE_ATTENTE'),
(472, 'VEILLON', 'Catherine', 'catherine.veillon31@gmail.com', '', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 18 54 06 07', '2018-10-02 00:00:00', 0, '0000-00-00', 'catherine@veillon31@gmail.com', 'f994587cb3fcd3c163f76c810da106f8870a9e95', 'SANS_CONTRAT'),
(473, 'LE ROY', 'Véronique ', 'verojplr@gmail.com', '6, rue Marcel Paul', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 69 92 75 01', '2018-10-07 00:00:00', 1, '2019-09-15', 'verojplr@gmail.com', 'd69c505b1dd9640924c281e90f3a6b88fca6f97d', 'ACTIF'),
(474, 'SIMON', 'Danielle', 'daniellesimon86@orange.fr', '', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 70 32 68 22', NULL, 0, NULL, 'daniellesimon86@orange.fr', 'c951b364c943202a535301ae7f68acd2e8518075', 'LISTE_ATTENTE'),
(475, 'MEUCCI', 'Francesca ', 'francesca.meucci@gmail.com', '10, rue des ailes - 44230 Saint Sébastien sur Loire', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 98 25 05 65', '2018-10-12 21:48:29', 1, '2019-09-15', 'francesca.meucci@gmail.com', 'a183afd195e08fb0949da55708ec0b33401e6862', 'ACTIF'),
(476, 'LEMOINE', 'Christelle', 'christellecaty@orange.fr', NULL, '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06.87.61.25.44', '2018-10-19 17:57:35', 0, NULL, 'christellecaty@orange.fr', 'b3855e533292d2d66e437f53e3f0520d111a6607', 'SANS_CONTRAT'),
(478, 'CATEAU', 'Julie', 'juliecateau@yahoo.fr', '61 bis rue de la malnoue', '44230', 'SAINT SEBASTIEN/LOIRE', '06 14 22 30 45', '06 58 65 92 91', '2019-09-20 16:45:52', 0, NULL, 'juliecateau@yahoo.fr', 'af9ffd9697eef5ec526dc82ce9c44957a7d673bc', 'ACTIF'),
(479, 'BERLAGUTS', 'COLOC ', 'gauguingang@gmail.com', '9 rue des berlagutes', '44230', 'SAINT SEBASTIEN/LOIRE', '', '07 81 81 60 79', '2019-09-20 16:55:12', 0, '0000-00-00', 'lucie-berthelot@live.fr', 'fd623d811c03a6650cb247bc72c26674cca68f87', 'ACTIF'),
(480, 'BERAUT', 'Quentin', 'admin@tetenbas.com', '57 rue sergent Thierry', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '07 88 45 12 06', '2019-07-04 17:28:34', 1, '2019-09-15', 'admin@tetenbas.com', 'f379f036b201d78af1139244006fd1ab848f2502', 'ACTIF'),
(481, 'LEROUX-FERRER', 'Elodie et David', 'elodie.le.roux@outlook.com', '', '44230', 'SAINT SEBASTIEN/LOIRE', '06 71 12 65 85', '06 42 09 41 95', '2019-06-02 00:00:00', 0, NULL, 'elodie.le.roux@outlook.com', '3b541a5b28f0ed30a78b4bbeacbce9aecb23a0bf', 'SANS_CONTRAT'),
(482, 'COULOIGNER', 'Cyrille', 'cyrille.couloigner@hotmail.com', '13 du Sergent Thierry 44230 Saint Sébastien sur Loire', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 37 26 75 08', NULL, 0, NULL, ' brunelle commune', '23c656d7f8b5481086a15488665fbb698542090e', 'LISTE_ATTENTE'),
(485, 'SPAROSVICH-SIMON ', 'Pablo', 'pablo_72@hotmail.fr', '16 rue de la loire', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 45 15 18 68', '2019-07-05 17:51:29', 1, '2019-09-15', 'pablo_72@hotmail.fr', '0fe740c5587a38dd149c73a953e297a710d12ca3', 'ACTIF'),
(487, 'MAFAYON', 'Claire', 'claire.mafayon@gmail.com', '', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 46 28 73 24', NULL, 0, NULL, 'claire.mafayon@gmail.com', '837f334391dd16b67fcd84381ec2f5f9449f53d8', 'LISTE_ATTENTE'),
(489, 'GICQUEAU', 'Christine et Ludovic', 'christine.gicqueau@laposte.net', '43 rue de la fontaine', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 60 56 70 14', '2019-09-20 00:47:58', 1, '2019-09-15', 'christine.gicqueau@laposte.net', '6806ce539a801b2e908695581d6f74ae65196b6e', 'ACTIF'),
(490, 'CAILLET- MARCEL', 'Antonin et Julie', 'julieantonin44@gmail.com', '46 rue du sergent Thierry  44230 Saint-Sébastien-sur-Loire ', '44230', 'SAINT SEBASTIEN/LOIRE', '06 73 16 91 84', '06 73 16 91 84', '2020-01-31 14:17:36', 1, '2020-02-01', 'antonin.caillet@gmail.com', '76ce2de5d4e51f5e4647f9d46f4b92cb4165a6f1', 'ACTIF'),
(491, 'REUX', 'Carole', 'carole.reux@sfr.fr', '32 ter rue du sergent Thierry, 44230 st Sébastien sur loire', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 98 98 32 39', '2019-09-03 22:26:01', 1, '2019-09-15', 'carole.reux@sfr.fr', 'e795642fed3767446ad7b7e17eaa75e39b9d9ad5', 'ACTIF'),
(492, 'DENIS', 'Audrey', 'denis.audrey@hotmail.fr', '7 rue du lieutenant Marty 44230 saint Sébastien sur Loire', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 19 14 39 31', NULL, 0, NULL, 'denis.audrey@hotmail.fr', '635a9f47c25c0cc2bc61c014fbc1330c57970d9d', 'LISTE_ATTENTE'),
(493, 'BASTOS LOPES', 'Qays ', 'qays.ammsr@hotmail.fr', '1 rue de Villandry, 44230 St Seb', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 09 83 42 96', NULL, 0, NULL, 'qays.ammsr@hotmail.fr', '2cf762b26ac4f96a2df963093ac0b2e9eb136c84', 'LISTE_ATTENTE'),
(494, 'PEIGNE', 'Myriam', 'myriam.peigne@yahoo.fr', '', '44230', 'SAINT SEBASTIEN/LOIRE', '', '', NULL, 0, NULL, 'myriam.peigne@yahoo.fr', '36569546f0e371a544ff52f2c63314fcfb4e99ca', 'LISTE_ATTENTE'),
(495, 'LEVALTIER', 'Audrey', 'audrey-levaltier@hotmail.com', '', '44230', 'SAINT SEBASTIEN/LOIRE', '', '', NULL, 0, NULL, 'audrey-levaltier@hotmail.com', '8c7a927c8ea82fef47950270fa6dbea9d9e80a4e', 'LISTE_ATTENTE'),
(496, 'UGUEN-PITON', 'Laurianne', 'laurianne.uguen@gmail.com', '9 bis rue du 1er Mai', '44120', 'VERTOU', '', '06 26 94 12 67', '2019-10-04 16:19:41', 1, '2019-09-15', 'laurianne.uguen@gmail.com', '108ce059f2e2fb0ea9ebf27284a937713fc4088c', 'ACTIF'),
(497, 'CHEDET', 'Isabelle', 'isamails@orange.fr', '136 rue de la Jaunaie', '44230', 'SAINT SEBASTIEN/LOIRE', '', '06 68 84 01 70', NULL, 0, NULL, 'isamails@orange.fr', '5b535bed24e64f2722415c19efa10aba6c1ee020', 'LISTE_ATTENTE'),
(498, 'JANSSENS', 'Carole', 'carole.janssens@free.fr', '31, rue du Corleveau', '44115', 'BASSE-GOULAINE', '', '06 31 50 28 06', '2019-09-21 00:40:40', 1, '2019-09-15', 'carole.janssens@free.fr', '95a01ae263fc48a73d3c0268c739c0cf13d5c6d0', 'ACTIF'),
(499, 'HENAFF-VAN GHELE', 'Pauline et Gauvin', 'p.henaff@hotmail.fr', '8 rue de la libération', '44230', 'SAINT SEBASTIEN/LOIRE', '06 63 61 56 54', '06 72 67 61 16', NULL, 0, NULL, 'p.henaff@hotmail.fr', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', 'LISTE_ATTENTE'),
(500, 'ROUSSEAU', 'Stéphane', ' rousseausteph@neuf.fr', '', '44230', 'SAINT SEBASTIEN/LOIRE', '', '', NULL, 0, NULL, ' rousseausteph@neuf.fr', '320ecb46de3ee62d7d7d82ba383029c9b0b497a2', 'LISTE_ATTENTE'),
(501, 'ROUSSIGNOL', 'Lorène', 'lorene_roussignol@hotmail.fr', '8 rue des Souches', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 22 15 68 28', '2019-10-03 20:49:05', 1, '2019-09-15', 'lorene_roussignol@hotmail.fr', 'bb8988a66122ea5850f70f6e7d1e1386a3d58c8a', 'ACTIF'),
(502, 'CROISE', 'Céline', 'celinecroise@hotmail.com', '78 rue de Beaugency', '44230', 'SAINT SEBASTIEN/LOIRE', NULL, '06 15 33 12 40', '2019-10-05 16:17:07', 1, '2019-12-01', 'celinecroise@hotmail.com', '702b54d3cc9969924385496a4351afc4e0e8409c', 'ACTIF'),
(507, 'HERVE', 'Emmanuel', 'emmanuelherve52@gmail.com', '', '0', '', '', '06 66 79 39 05', '2019-10-19 00:00:00', 1, '2019-09-15', 'emmanuelherve52@gmail.com', 'ea27d2b017bbcbe28bad94502224a0ceb6bdee0e', 'ACTIF'),
(509, 'ZION- WILLMS', 'Clément & Adriana', 'clement.zion@protonmail.com', '2 rue du Docteur Paul Michaux', '44230', 'Saint Sébastien sur loire', '06 32 66 13 90', '07 87 89 88 93', '2019-11-01 20:52:41', 1, '2019-11-01', 'clement.zion@protonmail.com', '91f8570c3c8f740a1f81188f1292aa9cb23ded66', 'ACTIF'),
(510, 'GAILIANN', 'Muriel', 'GAILIANN', '', '44230', 'Saint Sébastien sur loire', '', '07 83 05 67 24', NULL, 0, NULL, 'GAILIANN', 'a97511638a939b2a97009dccbf3017b841c5bf71', 'LISTE_ATTENTE'),
(511, 'CHATEL-CHENAVIER', 'Sylvain-Delphine', 'chenav23@yahoo.com', '5 rue des Homeaux', '44230', 'Saint Sébastien sur loire', '', '06 73 56 72 77', NULL, 0, NULL, 'chenav23@yahoo.com', 'ff5b8f9e0daa2a76f5dabd8ea3cb941e332bd927', 'LISTE_ATTENTE'),
(512, 'KEUNEBROEK', 'Laurent', 'laurent.keunebroek@orange.fr', '97 rue de la pyramide', '44230', 'Saint Séb', '', '06 83 07 61 53', '2020-01-11 00:00:00', 1, '2020-01-10', 'laurent.keunebroek@orange.fr', 'f2d8ae287d33b1fd903a2ca14a404be08315d0a2', 'ACTIF'),
(513, 'LEFEBVRE', 'Nicolas ', 'neotech.daf@wanadoo.fr', 'rue de la Baugerie', '44230', 'Saint Sébastien sur loire', '', '0621494457', NULL, 0, NULL, 'neotech.daf@wanadoo.fr', 'b33d112cfb993ca52c3fa22c20f7a9cf4a60117f', 'LISTE_ATTENTE');
INSERT INTO `sys_user` (`id`, `Nom`, `Prenom`, `e_mail`, `Adresse`, `Code_postal`, `Ville`, `Telephone`, `Tel_portable`, `Date_inscription`, `Paiement`, `Date_paiement`, `Login`, `Mot_passe`, `Etat_asso`) VALUES
(514, 'JEGAT', 'Amandine', 'Amandine', '', '0', '', '', '07.81.86.37.83', NULL, 0, NULL, 'Amandine', '16b6a9d544befd2d7c32bca075c0bdc103168d52', 'LISTE_ATTENTE'),
(515, 'Serge', 'NOEL', 'serge.noel@easylinux.fr', '12, dhfksdkfksd', '44230', 'SAINT SEBASTIEN SUR LOIRE', NULL, NULL, '2020-03-28 19:42:18', 0, NULL, 'serge.noel@easylinux.fr', '8be3c943b1609fffbfc51aad666d0a04adf83c9d', 'BENEVOLE');

-- --------------------------------------------------------

--
-- Structure de la table `sys_user_rights`
--

CREATE TABLE IF NOT EXISTS `sys_user_rights` (
  `idUser` int(11) NOT NULL,
  `idRights` int(11) NOT NULL,
  UNIQUE KEY `Idx_User_Rights` (`idUser`,`idRights`),
  KEY `fk_rights` (`idRights`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Lien entre sys-user et les droits ';



--
-- Contraintes pour la table `sys_menu_rights`
--
ALTER TABLE `sys_menu_rights`
  ADD CONSTRAINT `fk_menu` FOREIGN KEY (`idMenu`) REFERENCES `sys_menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_right` FOREIGN KEY (`idRights`) REFERENCES `sys_right` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `sys_user_rights`
--
ALTER TABLE `sys_user_rights`
  ADD CONSTRAINT `fk_rights` FOREIGN KEY (`idRights`) REFERENCES `sys_right` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`idUser`) REFERENCES `sys_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

