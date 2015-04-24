-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 19 Février 2015 à 18:57
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `bsup_stable_3010`
--

-- --------------------------------------------------------

--
-- Structure de la table `tavailability`
--

CREATE TABLE IF NOT EXISTS `tavailability` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `category` int(5) NOT NULL,
  `subcat` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tavailability_dep`
--

CREATE TABLE IF NOT EXISTS `tavailability_dep` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `category` int(5) NOT NULL,
  `subcat` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tcategory`
--

CREATE TABLE IF NOT EXISTS `tcategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `tcategory`
--

INSERT INTO `tcategory` (`id`, `name`) VALUES
(1, 'Application'),
(2, 'Materiel');

-- --------------------------------------------------------

--
-- Structure de la table `tcompany`
--

CREATE TABLE IF NOT EXISTS `tcompany` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `city` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tcriticality`
--

CREATE TABLE IF NOT EXISTS `tcriticality` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `number` int(2) NOT NULL,
  `name` varchar(50) NOT NULL,
  `color` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `tcriticality`
--

INSERT INTO `tcriticality` (`id`, `number`, `name`, `color`) VALUES
(1, 0, 'Critique', '#d15b47'),
(2, 1, 'Grave', '#f89406'),
(3, 2, 'Moyenne', '#f8c806'),
(4, 3, 'Basse', '#82af6f');

-- --------------------------------------------------------

--
-- Structure de la table `tevents`
--

CREATE TABLE IF NOT EXISTS `tevents` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `technician` int(10) NOT NULL,
  `incident` int(10) NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `type` int(1) NOT NULL,
  `disable` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tgroups`
--

CREATE TABLE IF NOT EXISTS `tgroups` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` int(1) NOT NULL,
  `disable` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Structure de la table `tgroups_assoc`
--

CREATE TABLE IF NOT EXISTS `tgroups_assoc` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `group` int(5) NOT NULL,
  `user` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tincidents`
--

CREATE TABLE IF NOT EXISTS `tincidents` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `type` int(1) NOT NULL DEFAULT '0',
  `technician` int(5) NOT NULL,
  `t_group` int(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` mediumtext NOT NULL,
  `user` varchar(20) NOT NULL,
  `u_group` int(5) NOT NULL,
  `u_service` int(5) NOT NULL,
  `date_create` datetime NOT NULL,
  `date_hope` date NOT NULL,
  `date_res` datetime NOT NULL,
  `date_modif` datetime NOT NULL,
  `state` int(1) NOT NULL,
  `priority` int(2) NOT NULL,
  `criticality` int(2) NOT NULL,
  `img1` varchar(500) NOT NULL,
  `img2` varchar(500) NOT NULL,
  `img3` varchar(500) NOT NULL,
  `img4` varchar(500) NOT NULL,
  `img5` varchar(500) NOT NULL,
  `time` int(10) NOT NULL,
  `time_hope` int(10) NOT NULL,
  `creator` int(3) NOT NULL,
  `category` int(3) NOT NULL,
  `subcat` int(3) NOT NULL,
  `techread` int(1) NOT NULL DEFAULT '1',
  `template` int(1) NOT NULL,
  `disable` int(1) NOT NULL,
  `notify` int(1) NOT NULL,
  `place` int(5) NOT NULL,
  `start_availability` datetime NOT NULL,
  `end_availability` datetime NOT NULL,
  `availability_planned` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tmails`
--

CREATE TABLE IF NOT EXISTS `tmails` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `incident` int(10) NOT NULL,
  `open` int(1) NOT NULL,
  `close` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tparameters`
--

CREATE TABLE IF NOT EXISTS `tparameters` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `company` varchar(50) DEFAULT NULL,
  `server_url` varchar(200) DEFAULT NULL,
  `version` varchar(8) NOT NULL,
  `update_channel` varchar(10) NOT NULL,
  `maxline` int(4) NOT NULL,
  `mail` int(1) NOT NULL,
  `mail_smtp` varchar(100) DEFAULT NULL,
  `mail_port` int(4) NOT NULL,
  `mail_auth` varchar(10) DEFAULT NULL,
  `mail_secure` varchar(10) DEFAULT NULL,
  `mail_username` varchar(150) DEFAULT NULL,
  `mail_password` varchar(150) DEFAULT NULL,
  `mail_txt` varchar(300) NOT NULL,
  `mail_cc` varchar(50) NOT NULL,
  `mail_from_name` varchar(60) NOT NULL,
  `mail_from_adr` varchar(50) NOT NULL,
  `mail_auto` int(1) NOT NULL,
  `mail_newticket` int(1) NOT NULL,
  `mail_newticket_address` varchar(200) NOT NULL,
  `mail_color_title` varchar(6) NOT NULL,
  `mail_color_bg` varchar(6) NOT NULL,
  `mail_color_text` varchar(6) NOT NULL,
  `mail_link` int(1) NOT NULL,
  `logo` varchar(50) NOT NULL,
  `user_advanced` int(1) NOT NULL,
  `user_register` int(1) NOT NULL,
  `lign_yellow` varchar(50) NOT NULL,
  `lign_orange` varchar(50) NOT NULL,
  `time_display_msg` int(5) NOT NULL,
  `auto_refresh` int(5) NOT NULL,
  `notify` int(1) NOT NULL,
  `ldap` int(1) NOT NULL,
  `ldap_auth` int(1) NOT NULL,
  `ldap_type` int(1) NOT NULL,
  `ldap_server` varchar(100) NOT NULL,
  `ldap_port` int(5) NOT NULL,
  `ldap_domain` varchar(200) NOT NULL,
  `ldap_url` varchar(200) NOT NULL,
  `ldap_user` varchar(100) NOT NULL,
  `ldap_password` varchar(100) NOT NULL,
  `planning` int(1) NOT NULL,
  `debug` int(1) NOT NULL,
  `imap` int(1) NOT NULL,
  `imap_server` varchar(50) NOT NULL,
  `imap_port` varchar(50) NOT NULL,
  `imap_user` varchar(50) NOT NULL,
  `imap_password` varchar(50) NOT NULL,
  `imap_inbox` varchar(20) NOT NULL,
  `order` varchar(100) NOT NULL,
  `procedure` int(1) NOT NULL,
  `ticket_places` int(1) NOT NULL,
  `ticket_type` int(1) NOT NULL,
  `availability` int(1) NOT NULL,
  `availability_all_cat` int(1) NOT NULL,
  `availability_dep` int(1) NOT NULL,
  `availability_condition_type` varchar(20) NOT NULL,
  `availability_condition_value` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `tparameters`
--

INSERT INTO `tparameters` (`id`, `company`, `server_url`, `version`, `update_channel`, `maxline`, `mail`, `mail_smtp`, `mail_port`, `mail_auth`, `mail_secure`, `mail_username`, `mail_password`, `mail_txt`, `mail_cc`, `mail_from_name`, `mail_from_adr`, `mail_auto`, `mail_newticket`, `mail_newticket_address`, `mail_color_title`, `mail_color_bg`, `mail_color_text`, `mail_link`, `logo`, `user_advanced`, `user_register`, `lign_yellow`, `lign_orange`, `time_display_msg`, `auto_refresh`, `notify`, `ldap`, `ldap_auth`, `ldap_type`, `ldap_server`, `ldap_port`, `ldap_domain`, `ldap_url`, `ldap_user`, `ldap_password`, `planning`, `debug`, `imap`, `imap_server`, `imap_port`, `imap_user`, `imap_password`, `imap_inbox`, `order`, `procedure`, `ticket_places`, `ticket_type`, `availability`, `availability_all_cat`, `availability_dep`, `availability_condition_type`, `availability_condition_value`) VALUES
(1, 'Societe', 'http://gestsup', '3.0.10', 'stable', 14, 1, '', 25, '0', '0', '', '', 'Bonjour, <br />Vous avez fait la demande suivante auprès du support:', 'support@exemple.fr', 'Support exemple', '', 0, 0, 'admin@exemple.fr', '438eb9', 'f5f5f5', '438eb9', 1, 'logo.png', 0, 0, '30', '45', 500, 0, 0, 0, 0, 0, 'localhost', 389, 'exemple.fr', 'cn=users', '', '', 1, 0, 0, '', '', '', '', 'INBOX', 'tstates.number, tincidents.priority, tincidents.criticality, tincidents.date_create', 0, 0, 0, 0, 1, 0, '', 0);

-- --------------------------------------------------------

--
-- Structure de la table `tplaces`
--

CREATE TABLE IF NOT EXISTS `tplaces` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tpriority`
--

CREATE TABLE IF NOT EXISTS `tpriority` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `number` int(2) NOT NULL,
  `name` varchar(50) NOT NULL,
  `color` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `tpriority`
--

INSERT INTO `tpriority` (`id`, `number`, `name`, `color`) VALUES
(1, 0, 'Urgent', '#d15b47'),
(2, 1, 'Très haute', '#f89406'),
(3, 2, 'Haute', '#f8c806'),
(4, 3, 'Moyenne', '#e7ef20'),
(5, 4, 'Basse', '#c2c921'),
(6, 5, 'Très basse', '#82af6f');

-- --------------------------------------------------------

--
-- Structure de la table `tprocedures`
--

CREATE TABLE IF NOT EXISTS `tprocedures` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `category` int(5) NOT NULL,
  `subcat` int(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `text` mediumtext NOT NULL,
  `file1` varchar(30) NOT NULL,
  `disable` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tprofiles`
--

CREATE TABLE IF NOT EXISTS `tprofiles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `level` int(10) NOT NULL,
  `img` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `tprofiles`
--

INSERT INTO `tprofiles` (`id`, `name`, `level`, `img`) VALUES
(1, 'technicien', 0, 'technician.png'),
(2, 'utilisateur avec pouvoir', 1, 'poweruser.png'),
(3, 'utilisateur', 2, 'user.png'),
(4, 'superviseur', 3, 'supervisor.png'),
(5, 'administrateur', 4, 'admin.png');

-- --------------------------------------------------------

--
-- Structure de la table `trights`
--

CREATE TABLE IF NOT EXISTS `trights` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `profile` int(5) NOT NULL,
  `search` int(1) NOT NULL COMMENT 'Affiche le champ de recherche',
  `task_checkbox` int(1) NOT NULL COMMENT 'Autorise les actions sur en selectionnant plusieurs tickets dans les tâches',
  `procedure` int(1) NOT NULL COMMENT 'Affiche le menu procédure',
  `stat` int(1) NOT NULL COMMENT 'Affiche le menu Statistiques',
  `planning` int(1) NOT NULL COMMENT 'Affiche le menu Planning',
  `availability` int(1) NOT NULL COMMENT 'Affiche le menu Disponibilité',
  `admin` int(1) NOT NULL COMMENT 'Affiche le menu Administration',
  `admin_user_profile` int(1) NOT NULL COMMENT 'Droit de modification de profile des utilisateurs',
  `admin_user_view` int(1) NOT NULL COMMENT 'Droit de modification des vues des utilisateurs',
  `userbar` int(1) NOT NULL COMMENT 'Affiche les propriétés étendue de la barre utilisateur',
  `side` int(1) NOT NULL COMMENT 'Affiche la colonne de gauche',
  `side_open_ticket` int(1) NOT NULL COMMENT 'Affiche le bouton Ouvrir un nouveau ticket',
  `side_your` int(1) NOT NULL COMMENT 'Affiche la section vos demandes',
  `side_your_not_read` int(1) NOT NULL COMMENT 'Affiche vos demande non lu',
  `side_your_not_attribute` int(1) NOT NULL COMMENT 'Affiche vos demande non attribué',
  `side_all` int(1) NOT NULL COMMENT 'Affiche la section toutes les demandes',
  `side_all_wait` int(1) NOT NULL COMMENT 'Affiche la vue Nouvelles demandes dans toutes les demandes',
  `side_view` int(1) NOT NULL COMMENT 'Affiche les vues personelles',
  `ticket_next` int(1) NOT NULL COMMENT 'Affiche les flèches ticket suivant et prècedent',
  `ticket_print` int(1) NOT NULL COMMENT 'Impression des tickets',
  `ticket_template` int(1) NOT NULL COMMENT 'Affiche le bouton modèle de ticket',
  `ticket_calendar` int(1) NOT NULL COMMENT 'Planifier un ticket',
  `ticket_event` int(1) NOT NULL COMMENT 'Créer un rappel de ticket',
  `ticket_save` int(1) NOT NULL COMMENT 'Sauvegarde de ticket',
  `ticket_type` int(1) NOT NULL COMMENT 'Modification du type dans le ticket',
  `ticket_type_disp` int(1) NOT NULL COMMENT 'Affiche le champ type dans le ticket',
  `ticket_user` int(1) NOT NULL COMMENT 'Modfication du demandeur',
  `ticket_user_disp` int(1) NOT NULL COMMENT 'Affiche le champ utilisateur dans le ticket',
  `ticket_user_actions` int(1) NOT NULL COMMENT 'Affiche les boutons actions pour le demandeur',
  `ticket_tech` int(1) NOT NULL COMMENT 'Modification du technicien',
  `ticket_tech_disp` int(1) NOT NULL COMMENT 'Affiche le champ technicien dans le ticket',
  `ticket_cat` int(1) NOT NULL COMMENT 'Modification des catégories',
  `ticket_cat_disp` int(1) NOT NULL COMMENT 'Affiche le champ catégorie dans le ticket',
  `ticket_cat_actions` int(1) NOT NULL COMMENT 'Affiche les boutons actions pour les catégories',
  `ticket_title` int(1) NOT NULL COMMENT 'Modification du titre dans le ticket',
  `ticket_title_disp` int(1) NOT NULL COMMENT 'Affiche le champ titre dans le ticket',
  `ticket_description` int(1) NOT NULL COMMENT 'Modification de la description',
  `ticket_description_disp` int(1) NOT NULL COMMENT 'Affiche le champ description dans le ticket',
  `ticket_resolution_disp` int(1) NOT NULL COMMENT 'Affiche le champ resolution dans le ticket',
  `ticket_attachment` int(1) NOT NULL COMMENT 'Ajouter des pièces jointes',
  `ticket_date_create` int(1) NOT NULL COMMENT 'Modification de la date de création',
  `ticket_date_create_disp` int(1) NOT NULL COMMENT 'Affiche le champ date de création dans le ticket',
  `ticket_date_hope` int(1) NOT NULL COMMENT 'Modification de la date de résolution estimée',
  `ticket_date_hope_disp` int(1) NOT NULL COMMENT 'Affiche le champ date de de résolution estimé dans le ticket',
  `ticket_date_res` int(1) NOT NULL COMMENT 'Modification de la date de résolution dans le ticket',
  `ticket_date_res_disp` int(1) NOT NULL COMMENT 'Affiche le champ date de résolution dans le ticket',
  `ticket_time` int(1) NOT NULL COMMENT 'Modification du temps passé par ticket',
  `ticket_time_disp` int(1) NOT NULL COMMENT 'Affiche le champ temps passé dans le ticket',
  `ticket_time_hope` int(1) NOT NULL COMMENT 'Modification du temps estimÃ© passÃ© par ticket',
  `ticket_time_hope_disp` int(1) NOT NULL COMMENT 'Affiche le champ temps estimé dans le ticket',
  `ticket_priority` int(1) NOT NULL COMMENT 'Modification de la priorité dans le ticket',
  `ticket_priority_disp` int(1) NOT NULL COMMENT 'Affiche le champ priorité dans le ticket',
  `ticket_criticality` int(1) NOT NULL COMMENT 'Modification de la criticité dans le ticket',
  `ticket_criticality_disp` int(1) NOT NULL COMMENT 'Affiche le champ criticité dans le ticket',
  `ticket_state` int(1) NOT NULL COMMENT 'Modification du champ etat dans le ticket',
  `ticket_state_disp` int(1) NOT NULL COMMENT 'Affiche le champ état dans le ticket',
  `ticket_availability` int(1) NOT NULL COMMENT 'Modifiction de la partie disponibilité',
  `ticket_availability_disp` int(1) NOT NULL COMMENT 'Affiche la partie disponibilité',
  `ticket_delete` int(1) NOT NULL COMMENT 'Droit de suppression de tickets',
  `ticket_close` int(1) NOT NULL COMMENT 'Affiche le bouton de cloture dans le ticket',
  `ticket_thread_add` int(1) NOT NULL COMMENT 'Ajouter des réponses',
  `ticket_thread_delete` int(1) NOT NULL COMMENT 'Suppression d''une résolution',
  `ticket_thread_edit` int(1) NOT NULL COMMENT 'Modification de ses résolutions',
  `ticket_thread_edit_all` int(1) NOT NULL COMMENT 'Modification de toutes les résolutions',
  `ticket_thread_post` int(1) NOT NULL COMMENT 'Droit de répondre dans les tickets',
  `ticket_save_close` int(1) NOT NULL COMMENT 'Affiche le bouton enregistrer et fermer dans le ticket',
  `ticket_send_mail` int(1) NOT NULL COMMENT 'Affiche le bouton envoyer un mail dans le ticket',
  `ticket_cancel` int(1) NOT NULL COMMENT 'Affiche le bouton annuler dans le ticket',
  `ticket_new_type` int(1) NOT NULL COMMENT 'Modification du type pour les nouveaux tickets',
  `ticket_new_type_disp` int(1) NOT NULL COMMENT 'Affiche le champ type pour les nouveaux tickets',
  `ticket_new_user` int(1) NOT NULL COMMENT 'Modification du demandeur pour les nouveaux tickets',
  `ticket_new_user_disp` int(1) NOT NULL COMMENT 'Affiche le champ demandeur pour les nouveaux tickets',
  `ticket_new_tech_disp` int(1) NOT NULL COMMENT 'Affiche le champ technicien pour les nouveaux tickets',
  `ticket_new_cat` int(1) NOT NULL COMMENT 'Modification de la catégorie pour les nouveaux tickets',
  `ticket_new_cat_disp` int(1) NOT NULL COMMENT 'Affiche le champ catégorie pour les nouveaux tickets',
  `ticket_new_resolution_disp` int(1) NOT NULL COMMENT 'Affiche le champ résolution pour les nouveaux tickets',
  `ticket_new_send` int(1) NOT NULL COMMENT 'Affiche le bouton envoyer pour les nouveaux tickets',
  `ticket_new_save` int(1) NOT NULL COMMENT 'Affiche le bouton sauvegarder sur les nouveaux tickets',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `trights`
--

INSERT INTO `trights` (`id`, `profile`, `search`, `task_checkbox`, `procedure`, `stat`, `planning`, `availability`, `admin`, `admin_user_profile`, `admin_user_view`, `userbar`, `side`, `side_open_ticket`, `side_your`, `side_your_not_read`, `side_your_not_attribute`, `side_all`, `side_all_wait`, `side_view`, `ticket_next`, `ticket_print`, `ticket_template`, `ticket_calendar`, `ticket_event`, `ticket_save`, `ticket_type`, `ticket_type_disp`, `ticket_user`, `ticket_user_disp`, `ticket_user_actions`, `ticket_tech`, `ticket_tech_disp`, `ticket_cat`, `ticket_cat_disp`, `ticket_cat_actions`, `ticket_title`, `ticket_title_disp`, `ticket_description`, `ticket_description_disp`, `ticket_resolution_disp`, `ticket_attachment`, `ticket_date_create`, `ticket_date_create_disp`, `ticket_date_hope`, `ticket_date_hope_disp`, `ticket_date_res`, `ticket_date_res_disp`, `ticket_time`, `ticket_time_disp`, `ticket_time_hope`, `ticket_time_hope_disp`, `ticket_priority`, `ticket_priority_disp`, `ticket_criticality`, `ticket_criticality_disp`, `ticket_state`, `ticket_state_disp`, `ticket_availability`, `ticket_availability_disp`, `ticket_delete`, `ticket_close`, `ticket_thread_add`, `ticket_thread_delete`, `ticket_thread_edit`, `ticket_thread_edit_all`, `ticket_thread_post`, `ticket_save_close`, `ticket_send_mail`, `ticket_cancel`, `ticket_new_type`, `ticket_new_type_disp`, `ticket_new_user`, `ticket_new_user_disp`, `ticket_new_tech_disp`, `ticket_new_cat`, `ticket_new_cat_disp`, `ticket_new_resolution_disp`, `ticket_new_send`, `ticket_new_save`) VALUES
(1, 0, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 0, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 0, 2),
(2, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 2, 2, 0, 0, 0, 2, 0, 2, 2, 0, 0, 0, 2, 0, 0, 0, 2, 0, 0, 2, 0, 2, 0, 0, 2, 0, 2, 2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 2, 2, 0, 2, 0, 2, 0, 0, 2, 0, 0, 0, 0, 0, 2, 2, 0, 2, 0),
(3, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 0, 2, 0, 0, 0, 0, 0, 2, 2, 0, 0, 0, 2, 0, 0, 0, 2, 0, 0, 2, 0, 2, 0, 0, 2, 0, 2, 2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 2, 2, 0, 0, 0, 2, 0, 0, 2, 0, 0, 0, 0, 0, 2, 2, 0, 2, 0),
(4, 3, 2, 2, 2, 2, 2, 2, 0, 0, 2, 0, 2, 2, 2, 0, 0, 2, 0, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 0, 2, 0, 2, 0, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 0, 2),
(5, 4, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 0, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 0, 2);

-- --------------------------------------------------------

--
-- Structure de la table `tservices`
--

CREATE TABLE IF NOT EXISTS `tservices` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tstates`
--

CREATE TABLE IF NOT EXISTS `tstates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` int(2) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  `mail_object` varchar(200) NOT NULL,
  `display` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `tstates`
--

INSERT INTO `tstates` (`id`, `number`, `name`, `description`, `mail_object`, `display`) VALUES
(1, 2, 'Attente PEC', 'tickets en attente de prise en charge', 'Notification d''ouverture', 'label label-sm label-info arrowed-in'),
(2, 3, 'En cours ', 'tickets en cours de traitement', 'Notification', 'label label-sm label-warning arrowed-in'),
(3, 5, 'Résolu', 'tickets résolus', 'Notification de clôture', 'label label-sm label-success arrowed arrowed-right arrowed-left'),
(4, 6, 'Rejeté', 'tickets rejetés', 'Notification de rejet', 'label label-sm label-inverse arrowed arrowed-right arrowed-left'),
(5, 1, 'Non attribué', 'tickets pas encore associés à un technicien', 'Notification de déclaration', 'label label-sm label-important arrowed-in arrowed-right rrowed-left'),
(6, 4, 'Attente Retour', 'tickets en attente d''éléments de la part du demandeur', 'Notification d''attente de retour ', 'label label-sm label-pink arrowed arrowed-right arrowed-left');

-- --------------------------------------------------------

--
-- Structure de la table `tsubcat`
--

CREATE TABLE IF NOT EXISTS `tsubcat` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cat` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `tsubcat`
--

INSERT INTO `tsubcat` (`id`, `cat`, `name`) VALUES
(1, 1, 'Office'),
(2, 2, 'PC');

-- --------------------------------------------------------

--
-- Structure de la table `ttemplates`
--

CREATE TABLE IF NOT EXISTS `ttemplates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `incident` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tthreads`
--

CREATE TABLE IF NOT EXISTS `tthreads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ticket` int(10) NOT NULL,
  `date` datetime NOT NULL,
  `author` int(10) NOT NULL,
  `text` mediumtext NOT NULL,
  `type` int(1) NOT NULL,
  `tech1` int(5) NOT NULL,
  `tech2` int(5) NOT NULL,
  `group1` int(5) NOT NULL,
  `group2` int(5) NOT NULL,
  `user` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `ttime`
--

CREATE TABLE IF NOT EXISTS `ttime` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `min` int(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `ttime`
--

INSERT INTO `ttime` (`id`, `min`, `name`) VALUES
(1, 1, '1m'),
(2, 5, '5m'),
(3, 10, '10m'),
(4, 30, '30m'),
(5, 60, '1h'),
(6, 180, '3h'),
(7, 300, '5h'),
(8, 480, '1j'),
(9, 960, '2j'),
(10, 2400, '1s');

-- --------------------------------------------------------

--
-- Structure de la table `ttypes`
--

CREATE TABLE IF NOT EXISTS `ttypes` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tusers`
--

CREATE TABLE IF NOT EXISTS `tusers` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `login` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `salt` varchar(50) NOT NULL,
  `firstname` varchar(40) NOT NULL,
  `lastname` varchar(40) NOT NULL,
  `profile` int(10) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `fax` varchar(20) NOT NULL,
  `function` varchar(50) NOT NULL,
  `service` int(5) NOT NULL,
  `company` int(5) NOT NULL,
  `address1` varchar(100) NOT NULL,
  `address2` varchar(100) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `city` varchar(100) NOT NULL,
  `custom1` varchar(100) NOT NULL,
  `custom2` varchar(100) NOT NULL,
  `disable` int(1) NOT NULL,
  `chgpwd` int(1) NOT NULL,
  `last_login` datetime NOT NULL,
  `skin` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `tusers`
--

INSERT INTO `tusers` (`id`, `login`, `password`, `salt`, `firstname`, `lastname`, `profile`, `mail`, `phone`, `fax`, `function`, `service`, `company`, `address1`, `address2`, `zip`, `city`, `custom1`, `custom2`, `disable`, `chgpwd`, `last_login`, `skin`) VALUES
(1, 'admin', 'admin', 'salt', 'admin', '', 4, 'admin@exemple.fr', '06 09 56 89 45', '0', '', 0, 0, '', '', '0', '', '', '', 0, 1, '0000-00-00 00:00:00', ''),
(2, 'user', 'user', 'salt', 'user', '', 2, 'user@exemple.fr', '', '0', '', 0, 0, '', '', '0', '', '', '', 0, 1, '0000-00-00 00:00:00', ''),
(3, 'poweruser', 'poweruser', 'salt', 'poweruser', '', 1, 'poweruser@exemple.fr', '', '0', '', 0, 0, '', '', '0', '', '', '', 0, 1, '0000-00-00 00:00:00', ''),
(4, 'super', 'super', 'salt', 'supervisor', '', 3, 'supervisor@exemple.fr', '', '0', '', 0, 0, '', '', '0', '', '', '', 0, 1, '0000-00-00 00:00:00', ''),
(5, 'tech', 'tech', 'salt', 'tech', '', 0, 'tech@exemple.fr', '', '0', '', 0, 0, '', '', '0', '', '', '', 0, 1, '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- Structure de la table `tusers_tech`
--

CREATE TABLE IF NOT EXISTS `tusers_tech` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `user` int(10) NOT NULL,
  `tech` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tviews`
--

CREATE TABLE IF NOT EXISTS `tviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` int(5) NOT NULL,
  `subcat` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
