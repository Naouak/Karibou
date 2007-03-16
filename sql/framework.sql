-- phpMyAdmin SQL Dump
-- version 2.6.2-pl1
-- http://www.phpmyadmin.net
-- 
-- Serveur: localhost
-- Généré le : Dimanche 21 Août 2005 à 22:12
-- Version du serveur: 4.1.12
-- Version de PHP: 5.1.0b3
-- 
-- Base de données: `annuaire`
-- 

-- --------------------------------------------------------

-- 
-- Structure de la table `group_user`
-- 

DROP TABLE IF EXISTS `group_user`;
CREATE TABLE `group_user` (
  `user_id` mediumint(9) unsigned NOT NULL default '0',
  `group_id` tinyint(3) unsigned NOT NULL default '0',
  `role` enum('member','admin') NOT NULL default 'member',
  `visibility` enum('visible','hidden') NOT NULL default 'visible',
  PRIMARY KEY  (`user_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- 
-- Contenu de la table `group_user`
-- 

INSERT INTO `group_user` (`user_id`, `group_id`) VALUES (1, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `groups`
-- 

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(30) default '0',
  `url` varchar(100) default NULL,
  `ml` varchar(100) default NULL,
  `description` varchar(100) default NULL,
  `left` int(11) NOT NULL default '0',
  `right` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Contenu de la table `groups`
-- 

INSERT INTO `groups` (`id`, `name`, `url`, `ml`, `description`, `left`, `right`) VALUES 
(1, 'Admins', NULL, NULL, NULL, 1, 2);

-- --------------------------------------------------------

-- 
-- Structure de la table `profile`
-- 

DROP TABLE IF EXISTS `profile`;
CREATE TABLE `profile` (
  `id` int(11) NOT NULL auto_increment,
  `firstname` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `lastname` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `surname` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `birthday` date NOT NULL default '0000-00-00',
  `url` text collate utf8_unicode_ci NOT NULL,
  `note` text collate utf8_unicode_ci NOT NULL,
  `title` text collate utf8_unicode_ci NOT NULL,
  `role` text collate utf8_unicode_ci NOT NULL,
  `org_name` text collate utf8_unicode_ci NOT NULL,
  `org_unit` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Contenu de la table `profile`
-- 

INSERT INTO `profile` (`id`, `firstname`, `lastname`, `surname`, `birthday`, `url`, `note`, `title`, `role`, `org_name`, `org_unit`) 
VALUES (1, 'Admin', '', 'Admin', '0000-00-00', '', 'Get root!', '', '', '', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `profile_address`
-- 

DROP TABLE IF EXISTS `profile_address`;
CREATE TABLE `profile_address` (
  `id` int(11) NOT NULL auto_increment,
  `profile_id` int(11) NOT NULL default '0',
  `type`  varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `poaddress` text collate utf8_unicode_ci NOT NULL,
  `extaddress` text collate utf8_unicode_ci NOT NULL,
  `street` text collate utf8_unicode_ci NOT NULL,
  `city` text collate utf8_unicode_ci NOT NULL,
  `region` text collate utf8_unicode_ci NOT NULL,
  `postcode` varchar(10) collate utf8_unicode_ci NOT NULL default '',
  `country` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

-- 
-- Structure de la table `profile_email`
-- 

DROP TABLE IF EXISTS `profile_email`;
CREATE TABLE `profile_email` (
  `id` int(11) NOT NULL auto_increment,
  `profile_id` int(11) NOT NULL default '0',
  `type`  varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `email` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Structure de la table `profile_phone`
-- 

DROP TABLE IF EXISTS `profile_phone`;
CREATE TABLE `profile_phone` (
  `id` int(11) NOT NULL auto_increment,
  `profile_id` int(11) NOT NULL default '0',
  `type`  varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `number` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Structure de la table `users`
-- 

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `login` varchar(64) NOT NULL default '',
  `password` varchar(64) character set utf8 collate utf8_bin default NULL,
  `profile_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `loginEtudiant` (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Contenu de la table `users`
-- 

INSERT INTO `users` (`id`, `login`, `password`, `profile_id`) VALUES (1, 'admin', PASSWORD('admin') , 1);
