-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 28 Août 2016 à 14:02
-- Version du serveur :  5.7.9
-- Version de PHP :  5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `zarpos2`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_at` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `categorie_expences`
--

DROP TABLE IF EXISTS `categorie_expences`;
CREATE TABLE IF NOT EXISTS `categorie_expences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `combo_items`
--

DROP TABLE IF EXISTS `combo_items`;
CREATE TABLE IF NOT EXISTS `combo_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `discount` varchar(5) DEFAULT NULL,
  `created_at` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `expences`
--

DROP TABLE IF EXISTS `expences`;
CREATE TABLE IF NOT EXISTS `expences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `reference` varchar(150) NOT NULL,
  `note` text,
  `amount` float NOT NULL,
  `attachment` varchar(200) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `category_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `holds`
--

DROP TABLE IF EXISTS `holds`;
CREATE TABLE IF NOT EXISTS `holds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` int(11) NOT NULL,
  `time` varchar(10) NOT NULL,
  `register_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=190 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `payements`
--

DROP TABLE IF EXISTS `payements`;
CREATE TABLE IF NOT EXISTS `payements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `paid` float NOT NULL,
  `paidmethod` varchar(300) CHARACTER SET latin1 NOT NULL,
  `created_by` varchar(60) CHARACTER SET latin1 NOT NULL,
  `register_id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `posales`
--

DROP TABLE IF EXISTS `posales`;
CREATE TABLE IF NOT EXISTS `posales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `price` float NOT NULL,
  `qt` int(6) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `register_id` int(11) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1428 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) CHARACTER SET latin1 NOT NULL,
  `name` varchar(25) CHARACTER SET latin1 NOT NULL,
  `category` varchar(20) CHARACTER SET latin1 NOT NULL,
  `cost` float NOT NULL,
  `tax` varchar(11) CHARACTER SET latin1 DEFAULT NULL,
  `description` text CHARACTER SET latin1,
  `price` float NOT NULL,
  `photo` varchar(200) CHARACTER SET latin1 NOT NULL,
  `photothumb` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `color` varchar(10) CHARACTER SET latin1 NOT NULL,
  `created_at` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `modified_at` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `alertqt` int(10) DEFAULT NULL,
  `supplier` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `unit` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `taxmethod` tinyint(4) DEFAULT NULL,
  `h_stores` varchar(300) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=140 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `purchases`
--

DROP TABLE IF EXISTS `purchases`;
CREATE TABLE IF NOT EXISTS `purchases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref` varchar(11) CHARACTER SET latin1 NOT NULL,
  `date` date NOT NULL,
  `total` float DEFAULT NULL,
  `attachement` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `store_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `note` text CHARACTER SET latin1,
  `modified_at` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `purchase_items`
--

DROP TABLE IF EXISTS `purchase_items`;
CREATE TABLE IF NOT EXISTS `purchase_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qt` int(10) NOT NULL,
  `cost` float NOT NULL,
  `subtot` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `registers`
--

DROP TABLE IF EXISTS `registers`;
CREATE TABLE IF NOT EXISTS `registers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cash_total` float DEFAULT NULL,
  `cash_sub` float DEFAULT NULL,
  `cc_total` float DEFAULT NULL,
  `cc_sub` float DEFAULT NULL,
  `cheque_total` float DEFAULT NULL,
  `cheque_sub` float DEFAULT NULL,
  `cash_inhand` float DEFAULT NULL,
  `note` text,
  `closed_at` varchar(150) DEFAULT NULL,
  `closed_by` int(11) DEFAULT NULL,
  `store_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `sales`
--

DROP TABLE IF EXISTS `sales`;
CREATE TABLE IF NOT EXISTS `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `clientname` varchar(50) NOT NULL,
  `tax` varchar(5) DEFAULT NULL,
  `discount` varchar(5) DEFAULT NULL,
  `subtotal` varchar(15) NOT NULL,
  `total` float NOT NULL,
  `created_at` date NOT NULL,
  `modified_at` varchar(150) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `totalitems` int(20) NOT NULL,
  `paid` varchar(15) DEFAULT NULL,
  `paidmethod` varchar(700) DEFAULT NULL,
  `taxamount` float DEFAULT NULL,
  `discountamount` float DEFAULT NULL,
  `register_id` int(11) DEFAULT NULL,
  `firstpayement` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `sale_items`
--

DROP TABLE IF EXISTS `sale_items`;
CREATE TABLE IF NOT EXISTS `sale_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `price` float NOT NULL,
  `qt` int(6) NOT NULL,
  `subtotal` varchar(20) NOT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=977 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companyname` varchar(100) NOT NULL,
  `logo` varchar(200) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `currency` varchar(10) DEFAULT NULL,
  `keyboard` tinyint(1) NOT NULL,
  `receiptheader` text,
  `receiptfooter` text NOT NULL,
  `theme` varchar(20) NOT NULL,
  `discount` varchar(5) DEFAULT NULL,
  `tax` varchar(5) DEFAULT NULL,
  `timezone` varchar(400) DEFAULT NULL,
  `language` varchar(30) DEFAULT NULL,
  `stripe` tinyint(4) DEFAULT NULL,
  `stripe_secret_key` varchar(150) DEFAULT NULL,
  `stripe_publishable_key` varchar(150) DEFAULT NULL,
  `decimals` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
CREATE TABLE IF NOT EXISTS `stocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `quantity` int(10) DEFAULT NULL,
  `price` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `stores`
--

DROP TABLE IF EXISTS `stores`;
CREATE TABLE IF NOT EXISTS `stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `email` varchar(40) DEFAULT NULL,
  `phone` varchar(40) DEFAULT NULL,
  `adresse` varchar(400) DEFAULT NULL,
  `footer_text` varchar(400) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `country` varchar(20) DEFAULT NULL,
  `created_at` varchar(200) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `stores`
--

INSERT INTO `stores` (`id`, `name`, `email`, `phone`, `adresse`, `footer_text`, `city`, `country`, `created_at`, `status`) VALUES
(1, 'Dar el web', 'default@store.com', '+12345678', 'adresse', 'Custome Footer for dal web store', 'zarzis', 'tunisia', '2016-05-10 12:44:33', 0);

-- --------------------------------------------------------

--
-- Structure de la table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET latin1 NOT NULL,
  `phone` varchar(15) CHARACTER SET latin1 DEFAULT NULL,
  `email` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
  `note` text CHARACTER SET latin1,
  `created_at` varchar(150) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `hashed_password` varchar(128) NOT NULL,
  `email` varchar(60) DEFAULT NULL,
  `role` varchar(20) NOT NULL,
  `last_active` varchar(50) DEFAULT NULL,
  `avatar` varchar(200) DEFAULT NULL,
  `created_at` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `username`, `firstname`, `lastname`, `hashed_password`, `email`, `role`, `last_active`, `avatar`, `created_at`) VALUES
(1, 'admin', 'admin', 'Doe', 'ede719c5ff6172692b6d811363e011f686229344f55662cb5493b4300403c237ad7b80fdd6a392e467850b6e338a523ea752f102d04968c8594efde2757b1f79', 'admin@dar-elweb.com', 'admin', '2016-08-28 15:01:26', '9fff9cc26e539214e9a5fd3b6a10cde9.jpg', '2016-08-28 15:01:23');

-- --------------------------------------------------------

--
-- Structure de la table `variations`
--

DROP TABLE IF EXISTS `variations`;
CREATE TABLE IF NOT EXISTS `variations` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `price` float DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `warehouses`
--

DROP TABLE IF EXISTS `warehouses`;
CREATE TABLE IF NOT EXISTS `warehouses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `phone` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `email` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `adresse` varchar(400) CHARACTER SET latin1 DEFAULT NULL,
  `created_at` varchar(200) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
