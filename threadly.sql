-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : ven. 10 avr. 2026 à 14:50
-- Version du serveur : 11.5.2-MariaDB
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `threadly`
--

-- --------------------------------------------------------

--
-- Structure de la table `droit`
--

DROP TABLE IF EXISTS `droit`;
CREATE TABLE IF NOT EXISTS `droit` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `role` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_droit_user_role` (`user_id`,`role`),
  KEY `fk_droit_user_idx` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `droit`
--

INSERT INTO `droit` (`id`, `user_id`, `role`) VALUES
(1, 1, 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `tshirt`
--

DROP TABLE IF EXISTS `tshirt`;
CREATE TABLE IF NOT EXISTS `tshirt` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(80) DEFAULT NULL,
  `size_list` varchar(80) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `price_old` decimal(10,2) DEFAULT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT 0,
  `is_sale` tinyint(1) NOT NULL DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tshirt`
--

INSERT INTO `tshirt` (`id`, `name`, `description`, `color`, `size_list`, `price`, `price_old`, `is_new`, `is_sale`, `image_url`, `created_at`) VALUES
(1, 'Classic Violet', 'T-shirt doux en coton bio avec coupe moderne.', 'Lavande', 'S M L XL', 24.90, NULL, 1, 0, 'include/img/tshirts/frog_t-shirt.png', '2026-04-01 10:00:00'),
(2, 'Ocean Blue', 'Couleur bleu ciel avec finition anti-boulochage.', 'Bleu ciel', 'S M L', 23.90, 29.90, 0, 1, 'include/img/tshirts/bougibougi_t-shirt.png', '2026-04-02 12:00:00'),
(3, 'Stone Washed', 'Effet vintage, texture souple et respirante.', 'Gris minéral', 'M L XL', 22.90, NULL, 0, 0, 'include/img/tshirts/dog_t-shirt.png', '2026-04-03 14:00:00'),
(4, 'Stripe Soft', 'Rayure violette délicate pour un look raffiné.', 'Violet rayé', 'S M L XL XXL', 26.90, NULL, 1, 0, 'include/img/tshirts/alfa_t-shirt.png', '2026-04-04 16:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `login` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `mdp` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `crerLe` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `login`, `mdp`, `crerLe`) VALUES
(1, 'admin', '$2y$10$nmA.9Z4ecoSimzesRSMmyOq3XcWFHemKIRSsKz1AXfoTzBjIX98dW', '2026-04-10 00:00:00'),
(2, 'kylian', '$2y$10$tVNFe2qvwPCp3TKANkxnVeLpqRRH7CnFsbEwfXcGFfzzyl5mR9wzy', '2026-04-10 13:14:25'),
(3, '12742', '$2y$10$uUHxuj4T3jBhvT/UnZuO/uUGE02xAPkL.CiA6SUTliK.rHirTz7UG', '2026-04-10 13:48:54'),
(4, 'kylebigot@stpbb.org', '$2y$10$wGqQqaGjyJGxP4yve7NWluxx.0nnnrmycYPmYUMmmiJxxG5LKakhG', '2026-04-10 14:03:28');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `droit`
--
ALTER TABLE `droit`
  ADD CONSTRAINT `fk_droit_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
