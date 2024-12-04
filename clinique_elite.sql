-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 21 sep. 2024 à 02:23
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `clinique_elite`
--

-- --------------------------------------------------------

--
-- Structure de la table `actes`
--

CREATE TABLE `actes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `actes`
--

INSERT INTO `actes` (`id`, `nom`, `created_at`, `updated_at`) VALUES
(1, 'CONSULTATION', '2024-09-21 00:19:29', '2024-09-21 00:19:29'),
(2, 'RADIO', '2024-09-21 00:19:29', '2024-09-21 00:19:29'),
(3, 'ANALYSE', '2024-09-21 00:19:29', '2024-09-21 00:19:29'),
(4, 'EXAMEN', '2024-09-21 00:19:29', '2024-09-21 00:19:29');

-- --------------------------------------------------------

--
-- Structure de la table `assurances`
--

CREATE TABLE `assurances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tel` varchar(255) NOT NULL,
  `tel2` varchar(255) DEFAULT NULL,
  `fax` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `assureurs`
--

CREATE TABLE `assureurs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `chambres`
--

CREATE TABLE `chambres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `nbre_lit` varchar(255) NOT NULL,
  `prix` varchar(255) NOT NULL,
  `statut` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `consultations`
--

CREATE TABLE `consultations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `matricule_patient` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `facture_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `detailconsultations`
--

CREATE TABLE `detailconsultations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `part_assurance` varchar(255) DEFAULT NULL,
  `part_patient` varchar(255) DEFAULT NULL,
  `remise` varchar(255) DEFAULT NULL,
  `montant` varchar(255) NOT NULL,
  `motif` varchar(255) NOT NULL,
  `type_motif` varchar(255) NOT NULL,
  `libelle` text DEFAULT NULL,
  `periode` varchar(255) NOT NULL,
  `appliq_remise` varchar(255) NOT NULL,
  `typeacte_id` bigint(20) UNSIGNED NOT NULL,
  `consultation_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

CREATE TABLE `factures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `statut` varchar(255) NOT NULL,
  `montant_verser` varchar(255) DEFAULT NULL,
  `montant_remis` varchar(255) DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `date_payer` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lits`
--

CREATE TABLE `lits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `statut` varchar(255) NOT NULL,
  `chambre_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_00_000000_create_role_models_table', 1),
(2, '0001_01_01_000000_create_users_table', 1),
(3, '0001_01_01_000001_create_cache_table', 1),
(4, '0001_01_01_000002_create_jobs_table', 1),
(5, '2024_09_09_164319_create_personal_access_tokens_table', 1),
(6, '2024_09_10_144253_create_assurance_models_table', 1),
(7, '2024_09_10_144306_create_assureur_models_table', 1),
(8, '2024_09_10_144401_create_produit_assurance_models_table', 1),
(9, '2024_09_10_144447_create_societe_assurance_models_table', 1),
(10, '2024_09_11_024940_create_chambres_table', 1),
(11, '2024_09_11_024947_create_lits_table', 1),
(12, '2024_09_11_131619_create_tauxes_table', 1),
(13, '2024_09_11_142953_create_type_produits_table', 1),
(14, '2024_09_13_142059_create_patients_table', 1),
(15, '2024_09_14_22346_create_actes_table', 1),
(16, '2024_09_14_224831_create_typeactes_table', 1),
(17, '2024_09_14_224832_create_type_medecins_table', 1),
(18, '2024_09_16_205453_create_factures_table', 1),
(19, '2024_09_16_205454_create_consultations_table', 1),
(20, '2024_09_16_210123_create_detailconsultations_table', 1);

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `patients`
--

CREATE TABLE `patients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `np` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `tel` varchar(255) NOT NULL,
  `tel2` varchar(255) DEFAULT NULL,
  `assurer` varchar(255) NOT NULL,
  `matricule` varchar(255) NOT NULL,
  `datenais` varchar(255) NOT NULL,
  `sexe` varchar(255) NOT NULL,
  `filiation` varchar(255) NOT NULL,
  `matricule_assurance` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `assurance_id` bigint(20) UNSIGNED DEFAULT NULL,
  `taux_id` bigint(20) UNSIGNED DEFAULT NULL,
  `societe_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `nom`, `created_at`, `updated_at`) VALUES
(1, 'MEDECIN', '2024-09-21 00:19:28', '2024-09-21 00:19:28'),
(2, 'ADMINISTRATEUR', '2024-09-21 00:19:28', '2024-09-21 00:19:28'),
(3, 'RECEPTIONNISTE', '2024-09-21 00:19:28', '2024-09-21 00:19:28'),
(4, 'LABORANTIN', '2024-09-21 00:19:28', '2024-09-21 00:19:28'),
(5, 'CAISSIER', '2024-09-21 00:19:28', '2024-09-21 00:19:28'),
(6, 'PHARMACIEN', '2024-09-21 00:19:28', '2024-09-21 00:19:28'),
(7, 'INFIRMIER', '2024-09-21 00:19:28', '2024-09-21 00:19:28'),
(8, 'DIRECTEUR MEDICAL', '2024-09-21 00:19:28', '2024-09-21 00:19:28'),
(9, 'COMPTABLE', '2024-09-21 00:19:28', '2024-09-21 00:19:28'),
(10, 'ARCHIVISTE', '2024-09-21 00:19:28', '2024-09-21 00:19:28');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `societes`
--

CREATE TABLE `societes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tauxes`
--

CREATE TABLE `tauxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `taux` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tauxes`
--

INSERT INTO `tauxes` (`id`, `taux`, `created_at`, `updated_at`) VALUES
(1, '5', '2024-09-21 00:19:30', '2024-09-21 00:19:30'),
(2, '10', '2024-09-21 00:19:30', '2024-09-21 00:19:30'),
(3, '15', '2024-09-21 00:19:30', '2024-09-21 00:19:30'),
(4, '20', '2024-09-21 00:19:30', '2024-09-21 00:19:30'),
(5, '25', '2024-09-21 00:19:30', '2024-09-21 00:19:30'),
(6, '30', '2024-09-21 00:19:30', '2024-09-21 00:19:30'),
(7, '35', '2024-09-21 00:19:30', '2024-09-21 00:19:30'),
(8, '40', '2024-09-21 00:19:30', '2024-09-21 00:19:30'),
(9, '45', '2024-09-21 00:19:30', '2024-09-21 00:19:30'),
(10, '50', '2024-09-21 00:19:30', '2024-09-21 00:19:30'),
(11, '55', '2024-09-21 00:19:30', '2024-09-21 00:19:30'),
(12, '60', '2024-09-21 00:19:30', '2024-09-21 00:19:30'),
(13, '65', '2024-09-21 00:19:30', '2024-09-21 00:19:30'),
(14, '70', '2024-09-21 00:19:30', '2024-09-21 00:19:30'),
(15, '75', '2024-09-21 00:19:30', '2024-09-21 00:19:30'),
(16, '80', '2024-09-21 00:19:30', '2024-09-21 00:19:30'),
(17, '85', '2024-09-21 00:19:30', '2024-09-21 00:19:30'),
(18, '90', '2024-09-21 00:19:30', '2024-09-21 00:19:30'),
(19, '95', '2024-09-21 00:19:30', '2024-09-21 00:19:30'),
(20, '100', '2024-09-21 00:19:30', '2024-09-21 00:19:30');

-- --------------------------------------------------------

--
-- Structure de la table `typeactes`
--

CREATE TABLE `typeactes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prix` varchar(255) NOT NULL,
  `acte_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `typeactes`
--

INSERT INTO `typeactes` (`id`, `nom`, `prix`, `acte_id`, `created_at`, `updated_at`) VALUES
(1, 'GENERALISTE', '10.000', 1, '2024-09-21 00:19:29', '2024-09-21 00:19:29'),
(2, 'PEDIATRE', '2.000', 1, '2024-09-21 00:19:29', '2024-09-21 00:19:29'),
(3, 'CARDIOLOGUE', '5.000', 1, '2024-09-21 00:19:29', '2024-09-21 00:19:29'),
(4, 'DENTISTE', '7.000', 1, '2024-09-21 00:19:29', '2024-09-21 00:19:29'),
(5, 'X-RAY', '20.000', 2, '2024-09-21 00:19:29', '2024-09-21 00:19:29'),
(6, 'ULTRASOUND', '30.000', 2, '2024-09-21 00:19:29', '2024-09-21 00:19:29'),
(7, 'BLOOD TEST', '15.000', 3, '2024-09-21 00:19:29', '2024-09-21 00:19:29'),
(8, 'URINE TEST', '10.000', 3, '2024-09-21 00:19:29', '2024-09-21 00:19:29'),
(9, 'ECG', '25.000', 4, '2024-09-21 00:19:29', '2024-09-21 00:19:29'),
(10, 'ECHO', '35.000', 4, '2024-09-21 00:19:30', '2024-09-21 00:19:30');

-- --------------------------------------------------------

--
-- Structure de la table `typemedecins`
--

CREATE TABLE `typemedecins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `typeacte_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typeproduits`
--

CREATE TABLE `typeproduits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `produit_id` bigint(20) UNSIGNED NOT NULL,
  `taux_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `tel` varchar(255) NOT NULL,
  `tel2` varchar(255) DEFAULT NULL,
  `sexe` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `matricule` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `tel`, `tel2`, `sexe`, `adresse`, `matricule`, `role`, `role_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test', 'test@gmail.com', NULL, '$2y$12$NsOEDk0r/3GzApKEpTQGBOWJ2/coZRWRYRN2aAcC03oT2CMfrhycy', '0757803650', NULL, 'Mr', 'adresse', 'C1223450', 'ADMINISTRATEUR', 2, NULL, '2024-09-21 00:19:31', '2024-09-21 00:19:31');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `actes`
--
ALTER TABLE `actes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `actes_nom_unique` (`nom`);

--
-- Index pour la table `assurances`
--
ALTER TABLE `assurances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `assurances_nom_unique` (`nom`),
  ADD UNIQUE KEY `assurances_email_unique` (`email`),
  ADD UNIQUE KEY `assurances_tel_unique` (`tel`),
  ADD UNIQUE KEY `assurances_fax_unique` (`fax`);

--
-- Index pour la table `assureurs`
--
ALTER TABLE `assureurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `assureurs_nom_unique` (`nom`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `chambres`
--
ALTER TABLE `chambres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chambres_code_index` (`code`),
  ADD KEY `chambres_nbre_lit_index` (`nbre_lit`),
  ADD KEY `chambres_prix_index` (`prix`),
  ADD KEY `chambres_statut_index` (`statut`);

--
-- Index pour la table `consultations`
--
ALTER TABLE `consultations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consultations_patient_id_foreign` (`patient_id`),
  ADD KEY `consultations_user_id_foreign` (`user_id`),
  ADD KEY `consultations_facture_id_foreign` (`facture_id`),
  ADD KEY `consultations_matricule_patient_index` (`matricule_patient`),
  ADD KEY `consultations_code_index` (`code`);

--
-- Index pour la table `detailconsultations`
--
ALTER TABLE `detailconsultations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detailconsultations_typeacte_id_foreign` (`typeacte_id`),
  ADD KEY `detailconsultations_consultation_id_foreign` (`consultation_id`);

--
-- Index pour la table `factures`
--
ALTER TABLE `factures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `factures_code_unique` (`code`),
  ADD KEY `factures_statut_index` (`statut`),
  ADD KEY `factures_date_payer_index` (`date_payer`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `lits`
--
ALTER TABLE `lits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lits_chambre_id_foreign` (`chambre_id`),
  ADD KEY `lits_code_index` (`code`),
  ADD KEY `lits_type_index` (`type`),
  ADD KEY `lits_statut_index` (`statut`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patients_tel_unique` (`tel`),
  ADD UNIQUE KEY `patients_matricule_unique` (`matricule`),
  ADD UNIQUE KEY `patients_email_unique` (`email`),
  ADD UNIQUE KEY `patients_tel2_unique` (`tel2`),
  ADD KEY `patients_assurance_id_foreign` (`assurance_id`),
  ADD KEY `patients_taux_id_foreign` (`taux_id`),
  ADD KEY `patients_societe_id_foreign` (`societe_id`),
  ADD KEY `patients_np_index` (`np`),
  ADD KEY `patients_assurer_index` (`assurer`),
  ADD KEY `patients_filiation_index` (`filiation`),
  ADD KEY `patients_matricule_assurance_index` (`matricule_assurance`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `produits_nom_unique` (`nom`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_nom_unique` (`nom`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `societes`
--
ALTER TABLE `societes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `societes_nom_unique` (`nom`);

--
-- Index pour la table `tauxes`
--
ALTER TABLE `tauxes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tauxes_taux_unique` (`taux`);

--
-- Index pour la table `typeactes`
--
ALTER TABLE `typeactes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `typeactes_acte_id_foreign` (`acte_id`);

--
-- Index pour la table `typemedecins`
--
ALTER TABLE `typemedecins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `typemedecins_typeacte_id_foreign` (`typeacte_id`),
  ADD KEY `typemedecins_user_id_foreign` (`user_id`);

--
-- Index pour la table `typeproduits`
--
ALTER TABLE `typeproduits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `typeproduits_nom_unique` (`nom`),
  ADD KEY `typeproduits_produit_id_foreign` (`produit_id`),
  ADD KEY `typeproduits_taux_id_foreign` (`taux_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_tel_unique` (`tel`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_name_index` (`name`),
  ADD KEY `users_sexe_index` (`sexe`),
  ADD KEY `users_role_index` (`role`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `actes`
--
ALTER TABLE `actes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `assurances`
--
ALTER TABLE `assurances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `assureurs`
--
ALTER TABLE `assureurs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `chambres`
--
ALTER TABLE `chambres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `consultations`
--
ALTER TABLE `consultations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `detailconsultations`
--
ALTER TABLE `detailconsultations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `factures`
--
ALTER TABLE `factures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `lits`
--
ALTER TABLE `lits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `societes`
--
ALTER TABLE `societes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tauxes`
--
ALTER TABLE `tauxes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `typeactes`
--
ALTER TABLE `typeactes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `typemedecins`
--
ALTER TABLE `typemedecins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `typeproduits`
--
ALTER TABLE `typeproduits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `consultations`
--
ALTER TABLE `consultations`
  ADD CONSTRAINT `consultations_facture_id_foreign` FOREIGN KEY (`facture_id`) REFERENCES `factures` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `consultations_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `consultations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `detailconsultations`
--
ALTER TABLE `detailconsultations`
  ADD CONSTRAINT `detailconsultations_consultation_id_foreign` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detailconsultations_typeacte_id_foreign` FOREIGN KEY (`typeacte_id`) REFERENCES `typeactes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `lits`
--
ALTER TABLE `lits`
  ADD CONSTRAINT `lits_chambre_id_foreign` FOREIGN KEY (`chambre_id`) REFERENCES `chambres` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_assurance_id_foreign` FOREIGN KEY (`assurance_id`) REFERENCES `assurances` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patients_societe_id_foreign` FOREIGN KEY (`societe_id`) REFERENCES `societes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patients_taux_id_foreign` FOREIGN KEY (`taux_id`) REFERENCES `tauxes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `typeactes`
--
ALTER TABLE `typeactes`
  ADD CONSTRAINT `typeactes_acte_id_foreign` FOREIGN KEY (`acte_id`) REFERENCES `actes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `typemedecins`
--
ALTER TABLE `typemedecins`
  ADD CONSTRAINT `typemedecins_typeacte_id_foreign` FOREIGN KEY (`typeacte_id`) REFERENCES `typeactes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `typemedecins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `typeproduits`
--
ALTER TABLE `typeproduits`
  ADD CONSTRAINT `typeproduits_produit_id_foreign` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `typeproduits_taux_id_foreign` FOREIGN KEY (`taux_id`) REFERENCES `tauxes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
