-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

-- Base de données : `covoit_mvc`
-- Schéma principal


DROP DATABASE IF EXISTS covoit_mvc;
CREATE DATABASE covoit_mvc
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE covoit_mvc;


-- 1) Agences (villes)

CREATE TABLE `agencies` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- 2) Utilisateurs

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(80) NOT NULL,
  `last_name` varchar(80) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `email` varchar(190) NOT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- 3) Trajets

CREATE TABLE `trips` (
  `id` int(10) UNSIGNED NOT NULL,
  `departure_agency_id` int(10) UNSIGNED NOT NULL,
  `departure_at` datetime NOT NULL,
  `arrival_agency_id` int(10) UNSIGNED NOT NULL,
  `arrival_at` datetime NOT NULL,
  `seats_total` tinyint(3) UNSIGNED NOT NULL,
  `seats_available` tinyint(3) UNSIGNED NOT NULL,
  `created_by_user_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- Index utiles
-- Index pour les tables déchargées
--

--
-- Index pour la table `agencies`
--
ALTER TABLE `agencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_trips_creator` (`created_by_user_id`),
  ADD KEY `idx_trips_dep_at` (`departure_at`),
  ADD KEY `idx_trips_dep_agency` (`departure_agency_id`),
  ADD KEY `idx_trips_arr_agency` (`arrival_agency_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `agencies`
--
ALTER TABLE `agencies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `fk_trips_arr` FOREIGN KEY (`arrival_agency_id`) REFERENCES `agencies` (`id`),
  ADD CONSTRAINT `fk_trips_creator` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_trips_dep` FOREIGN KEY (`departure_agency_id`) REFERENCES `agencies` (`id`);
COMMIT;
