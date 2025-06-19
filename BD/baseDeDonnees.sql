
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 19 juin 2025 à 20:59
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `eclosia`
--

-- --------------------------------------------------------

--
-- Structure de la table `Personne`
--

CREATE TABLE `Personne` (
                            `id` int(11) NOT NULL,
                            `nom` varchar(255) NOT NULL,
                            `prénom` varchar(255) NOT NULL,
                            `email` varchar(255) NOT NULL,
                            `telephone` varchar(255) NOT NULL,
                            `mdp` varchar(255) NOT NULL,
                            `creer_a` date NOT NULL DEFAULT curdate(),
                            `etat` enum('actif','inactif','bloqué') DEFAULT 'inactif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Personne`
--

INSERT INTO `Personne` (`id`, `nom`, `prénom`, `email`, `telephone`, `mdp`, `creer_a`, `etat`) VALUES
                                                                                                   (1, 'Dupont', 'Jean', 'jean.dupont@example.com', '0601020304', 'motdepasse123', '2025-06-18', 'actif'),
                                                                                                   (2, 'Martin', 'Claire', 'claire.martin@example.com', '0605060708', 'motdepasse456', '2025-06-17', 'actif'),
                                                                                                   (3, 'Durand', 'Paul', 'paul.durand@example.com', '0611121314', 'motdepasse789', '2025-06-16', 'bloqué'),
                                                                                                   (4, 'united', 'bleu', 'united@bleu.com', '0102040506', '$2y$10$I2HgaTE/LZwup9FNe0kYUubwoc9fq54ZyNrZkEBQPOHrxIFg6LEZC', '2025-06-19', 'inactif'),
                                                                                                   (5, 'marie', 'fathi', 'marie@isep.com', '0102050201', '$2y$10$/cl741FIto.RQwPqm84Tx.LycCb02fFUR5LBK5vcTdUeWvBOTpH3W', '2025-06-19', 'inactif'),
                                                                                                   (6, 'test', 'test', 'test@test.com', '0105040201', '$2y$10$OfPRhyWDJ8bE6ndsNp0E/efTqEw/Bijw2WP6JrQNocKK.AJXvNWRW', '2025-06-19', 'inactif'),
                                                                                                   (7, 'halima', 'fathi', 'halima@fathi.com', '0105040000', '$2y$10$kOBKK8l69TQnr1PUF.V.gO54dsSahXPDMo0KmU50UEkMyhCMKBzf.', '2025-06-19', 'actif'),
                                                                                                   (8, 'halimaa', 'fathi', 'halimaa@fathi.com', '0105040001', '$2y$10$hw.Bx5TQCsXY2oOEFv1lEOma55E6WkUx.q0SaQ51q3n1GiOPphVvK', '2025-06-19', 'actif');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Personne`
--
ALTER TABLE `Personne`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Personne`
--
ALTER TABLE `Personne`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;











--bdd remote

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `actionneurs` (
                               `id` int NOT NULL,
                               `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `actionneurs`
--

INSERT INTO `actionneurs` (`id`, `nom`) VALUES
                                            (1, 'led'),
                                            (2, 'moteur');

-- --------------------------------------------------------

--
-- Structure de la table `capteurs`
--

CREATE TABLE `capteurs` (
                            `id` int NOT NULL,
                            `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
                            `unite` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
                            `is_actif` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `capteurs`
--

INSERT INTO `capteurs` (`id`, `nom`, `unite`, `is_actif`) VALUES
                                                              (1, 'luminosite', '%', 1),
                                                              (2, 'temperature', '°C', 1),
                                                              (3, 'humidite', '%', 1),
                                                              (4, 'bouton', NULL, 1),
                                                              (5, 'humidite_sol', '%', 1);

--

CREATE TABLE `etats_actionneurs` (
                                     `id` int NOT NULL,
                                     `actionneur_id` int NOT NULL,
                                     `date_heure` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                                     `etat` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `limites` (
                           `id` int NOT NULL,
                           `id_capteur` int NOT NULL,
                           `lim_min` float DEFAULT NULL,
                           `lim_max` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `limites`
--

INSERT INTO `limites` (`id`, `id_capteur`, `lim_min`, `lim_max`) VALUES
    (1, 1, 20, 100);

-- --------------------------------------------------------

--
-- Structure de la table `mesures`
--

CREATE TABLE `mesures` (
                           `id` bigint NOT NULL,
                           `capteur_id` int NOT NULL,
                           `valeur` float NOT NULL,
                           `date_heure` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

CREATE TABLE `role` (
                        `name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
                        `id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`name`, `id`) VALUES
                                      ('etudiant', 1),
                                      ('admin', 2);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
                        `id_user` int NOT NULL,
                        `mail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                        `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
                        `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
                        `token` char(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
                        `est_verifie` tinyint(1) NOT NULL DEFAULT '0',
                        `inactif_depuis` datetime DEFAULT NULL,
                        `role_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `mail`, `username`, `password`, `token`, `est_verifie`, `inactif_depuis`, `role_id`) VALUES
                                                                                                                        (2, 'victor.munerot@eleve.isep.fr', 'savacartonner', '$2y$10$wRTesud5oXCEyG8wOK6m2eDBn2r2N04snpkwdaf35Rti3cB63xx7u', NULL, 1, NULL, 1),
                                                                                                                        (5, 'guillaume.jacquet@eleve.isep.fr', 'GJ', '$2y$10$T9boBgNr8UMKedPDGSaHG.E.iP2tk39Rp7QyE2mSml.hMhiGV9Ifa', NULL, 0, NULL, 1),
                                                                                                                        (6, 'angel.segui@eleve.isep.fr', 'angle', '$2y$10$CESYzeYnVN6aUxwWDaMhNOaVoQB/KA4wmxfFfeYWSD9iNr5YKInFO', '52e294180be1731a984bebecd7b84a6b', 1, NULL, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `actionneurs`
--
ALTER TABLE `actionneurs`
    ADD PRIMARY KEY (`id`);

--
-- Index pour la table `capteurs`
--
ALTER TABLE `capteurs`
    ADD PRIMARY KEY (`id`);

--
-- Index pour la table `etats_actionneurs`
--
ALTER TABLE `etats_actionneurs`
    ADD PRIMARY KEY (`id`),
  ADD KEY `etats_actionneurs_actionneurs_FK` (`actionneur_id`);

--
-- Index pour la table `limites`
--
ALTER TABLE `limites`
    ADD PRIMARY KEY (`id`),
  ADD KEY `id_capteur` (`id_capteur`);

--
-- Index pour la table `mesures`
--
ALTER TABLE `mesures`
    ADD PRIMARY KEY (`id`),
  ADD KEY `mesures_capteurs_FK` (`capteur_id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
    ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
    ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `mails` (`mail`),
  ADD KEY `user_role_FK` (`role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `actionneurs`
--
ALTER TABLE `actionneurs`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `capteurs`
--
ALTER TABLE `capteurs`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `etats_actionneurs`
--
ALTER TABLE `etats_actionneurs`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=779;

--
-- AUTO_INCREMENT pour la table `limites`
--
ALTER TABLE `limites`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `mesures`
--
ALTER TABLE `mesures`
    MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10468;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
    MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `etats_actionneurs`
--
ALTER TABLE `etats_actionneurs`
    ADD CONSTRAINT `etats_actionneurs_actionneurs_FK` FOREIGN KEY (`actionneur_id`) REFERENCES `actionneurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `limites`
--
ALTER TABLE `limites`
    ADD CONSTRAINT `limites_ibfk_1` FOREIGN KEY (`id_capteur`) REFERENCES `capteurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `mesures`
--
ALTER TABLE `mesures`
    ADD CONSTRAINT `mesures_capteurs_FK` FOREIGN KEY (`capteur_id`) REFERENCES `capteurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
    ADD CONSTRAINT `user_role_FK` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);
COMMIT;