CREATE TABLE IF NOT EXISTS `sys_db_update` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant',
  `description` varchar(254) NOT NULL COMMENT 'Description maj',
  `sql_text` text NOT NULL COMMENT 'SQl',
  `version` varchar(16) NOT NULL COMMENT '1.1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='SQL resuqest for update';

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

CREATE TABLE IF NOT EXISTS `sys_menu_rights` (
  `idMenu` int(11) NOT NULL,
  `idRights` int(11) NOT NULL,
  UNIQUE KEY `Uni_primary` (`idMenu`,`idRights`),
  KEY `fk_right` (`idRights`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Lien entre sys_menu et sys_right';

CREATE TABLE IF NOT EXISTS `sys_parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `value` varchar(32) NOT NULL,
  `type` varchar(32) NOT NULL,
  `link` varchar(32) NOT NULL,
  `description` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `sys_right` (
  `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant',
  `Label` varchar(32) NOT NULL,
  `Description` varchar(254) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

CREATE TABLE IF NOT EXISTS `sys_user_rights` (
  `idUser` int(11) NOT NULL,
  `idRights` int(11) NOT NULL,
  UNIQUE KEY `Idx_User_Rights` (`idUser`,`idRights`),
  KEY `fk_rights` (`idRights`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Lien entre sys-user et les droits ';

ALTER TABLE `sys_menu_rights`
  ADD CONSTRAINT `fk_menu` FOREIGN KEY (`idMenu`) REFERENCES `sys_menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_right` FOREIGN KEY (`idRights`) REFERENCES `sys_right` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `sys_user_rights`
  ADD CONSTRAINT `fk_rights` FOREIGN KEY (`idRights`) REFERENCES `sys_right` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`idUser`) REFERENCES `sys_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

