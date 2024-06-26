-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 24 juin 2024 à 14:47
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
-- Base de données : `topographie2`
--

-- --------------------------------------------------------

--
-- Structure de la table `chantiers`
--

CREATE TABLE `chantiers` (
  `id` int(11) NOT NULL,
  `code` varchar(15) NOT NULL,
  `chantier` varchar(255) NOT NULL,
  `id_pays` int(11) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `creer_par` varchar(50) DEFAULT NULL,
  `observation` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `chantiers`
--

INSERT INTO `chantiers` (`id`, `code`, `chantier`, `id_pays`, `contact`, `active`, `creer_par`, `observation`) VALUES
(1, '11-1604', 'ILE A MORPHIL', 4, '0', 0, 'O Faye', ''),
(2, '11-1604_B', 'BABA GARAGE - MECKHE - FASS BOYE', 1, '', 0, 'O Faye', ''),
(3, '11-1604_C', 'DEMETH CAS CAS', 1, '', 0, 'O Faye', ''),
(4, '11-1605', 'ROUTE DES NIAYES', 1, '', 0, 'O Faye', ''),
(5, '11-1701', 'MOSQUEE CITE CSE', 1, '', 0, 'O Faye', ''),
(6, '11-1702', 'NDIOUM DEMETH LOT 4', 1, '', 0, 'O Faye', ''),
(7, '11-1703', 'MBEUBEUSS', 1, '', 0, 'O Faye', ''),
(8, '11-1704', 'MBEUBEUSS LOT2', 1, '', 0, 'O Faye', ''),
(9, '11-1705', 'ROUTE NATIONALE GOLERE THILOGNE', 1, '', 0, 'O Faye', ''),
(10, '11-1706', 'OCEAN VIEW', 1, '', 0, 'O Faye', ''),
(11, '11-1709', 'PROMOVILLES', 1, '', 0, 'O Faye', '');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `date_devis` date NOT NULL,
  `fournisseur` varchar(255) NOT NULL,
  `num_devis` varchar(50) NOT NULL,
  `montant_euro` float DEFAULT NULL,
  `montant_cfa` float NOT NULL,
  `chantier` varchar(255) NOT NULL,
  `num_bc` varchar(50) DEFAULT NULL,
  `date_bc` date DEFAULT NULL,
  `avance_montant` float DEFAULT NULL,
  `date_avance` date DEFAULT NULL,
  `date_paiement_solde` date DEFAULT NULL,
  `num_semaines` int(11) DEFAULT NULL,
  `date_livraison_prevue` date DEFAULT NULL,
  `delai_restant` int(11) DEFAULT NULL,
  `date_reception` date DEFAULT NULL,
  `conformite` varchar(50) DEFAULT NULL,
  `date_fin_garantie` date DEFAULT NULL,
  `fichier` varchar(255) DEFAULT NULL,
  `observation` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `demandes_rebut`
--

CREATE TABLE `demandes_rebut` (
  `id` int(11) NOT NULL,
  `id_materiel_topo` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `requested_by` int(11) DEFAULT NULL,
  `requested_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `familles_topo`
--

CREATE TABLE `familles_topo` (
  `id` int(11) NOT NULL,
  `materiel` varchar(250) DEFAULT NULL,
  `abv` varchar(8) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `observation` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fournisseurs`
--

CREATE TABLE `fournisseurs` (
  `id` int(11) NOT NULL,
  `fournisseur` varchar(255) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `creer_par` varchar(100) DEFAULT NULL,
  `observation` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `fournisseurs`
--

INSERT INTO `fournisseurs` (`id`, `fournisseur`, `code`, `contact`, `active`, `creer_par`, `observation`) VALUES
(1, 'Supplier A', 'SUPA', 'contact@suppliera.com', 1, 'admin', 'Main supplier of surveying instruments');

-- --------------------------------------------------------

--
-- Structure de la table `intervenants`
--

CREATE TABLE `intervenants` (
  `id` int(11) NOT NULL,
  `nom` varchar(250) DEFAULT NULL,
  `code_intervenant` varchar(25) DEFAULT NULL,
  `domaine_intervention` varchar(50) DEFAULT NULL,
  `date_entree_service` date DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `observation` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `interventions`
--

CREATE TABLE `interventions` (
  `id` int(11) NOT NULL,
  `type_intervention` varchar(25) NOT NULL,
  `id_materiel_topo` int(11) DEFAULT NULL,
  `date_intervention` date NOT NULL,
  `intervenant` varchar(50) DEFAULT NULL,
  `sous_traitant` varchar(50) DEFAULT NULL,
  `nature_intervention` varchar(50) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `tolerance` int(11) DEFAULT NULL,
  `duree_validite` int(11) DEFAULT NULL,
  `date_fin_validite` date DEFAULT NULL,
  `cout` float DEFAULT NULL,
  `fiche` varchar(255) DEFAULT NULL,
  `observation` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `journal`
--

CREATE TABLE `journal` (
  `id` int(11) NOT NULL,
  `module` varchar(100) DEFAULT NULL,
  `type_action` varchar(50) DEFAULT NULL,
  `actions` varchar(255) DEFAULT NULL,
  `effectue_par` varchar(100) DEFAULT NULL,
  `date_action` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `materiel_topo`
--

CREATE TABLE `materiel_topo` (
  `id` int(11) NOT NULL,
  `id_famille_topo` int(11) DEFAULT NULL,
  `code` varchar(25) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `marque` varchar(200) DEFAULT NULL,
  `num_serie` varchar(100) DEFAULT NULL,
  `date_acquisition` date DEFAULT NULL,
  `cout_acquisition` float DEFAULT NULL,
  `id_fournisseur` int(11) DEFAULT NULL,
  `num_bc` varchar(100) DEFAULT NULL,
  `fiche_bl` varchar(255) DEFAULT NULL,
  `date_mise_service` date DEFAULT NULL,
  `etat` varchar(25) DEFAULT NULL,
  `id_chantier` int(11) DEFAULT NULL,
  `date_affectation` date DEFAULT NULL,
  `creer_par` varchar(50) DEFAULT NULL,
  `observation` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pays`
--

CREATE TABLE `pays` (
  `id` int(11) NOT NULL,
  `pays` varchar(50) NOT NULL,
  `creer_par` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `pays`
--

INSERT INTO `pays` (`id`, `pays`, `creer_par`) VALUES
(1, 'France', 'admin'),
(2, 'Germany', 'admin'),
(4, 'Senegal', 'Abdou Aziz Daback Ba'),
(5, 'mali', 'Abdou Aziz Daback Ba');

-- --------------------------------------------------------

--
-- Structure de la table `reforme_materiel`
--

CREATE TABLE `reforme_materiel` (
  `id` int(11) NOT NULL,
  `id_materiel_topo` int(11) DEFAULT NULL,
  `date_reforme` date NOT NULL,
  `raison` varchar(255) DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `observation` text DEFAULT NULL,
  `creer_par` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `transfert_materiel`
--

CREATE TABLE `transfert_materiel` (
  `id` int(11) NOT NULL,
  `id_materiel_topo` int(11) DEFAULT NULL,
  `date_transfert` date NOT NULL,
  `id_provenance` int(11) DEFAULT NULL,
  `id_destination` int(11) DEFAULT NULL,
  `num_bt` int(11) DEFAULT NULL,
  `bon_transfert` varchar(255) DEFAULT NULL,
  `receptionner` tinyint(1) DEFAULT NULL,
  `date_reception` date DEFAULT NULL,
  `cout` float DEFAULT NULL,
  `creer_par` varchar(50) DEFAULT NULL,
  `observation` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nom_complet` varchar(100) NOT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `groupe` varchar(50) DEFAULT NULL,
  `privilege` varchar(15) DEFAULT NULL,
  `code_chantier` varchar(15) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `email`, `nom_complet`, `telephone`, `groupe`, `privilege`, `code_chantier`, `created_at`, `updated_at`, `password`) VALUES
(5, 'aa@gmail.com', 'Abdou Aziz Daback Ba', '781885480', 'labo', 'admin', '11-1604', '2024-06-20 16:05:29', '2024-06-20 16:05:29', 'e7247759c1633c0f9f1485f3690294a9'),
(6, 'v@gmail.com', 'vis', '781885480', 'topo', 'invite', '11-1705', '2024-06-20 16:38:43', '2024-06-21 11:05:35', 'e7247759c1633c0f9f1485f3690294a9'),
(7, 'king@gmail.com', 'king', '781885480', 'topo', 'utilisateur', '11-1704', '2024-06-21 11:32:19', '2024-06-21 11:32:19', 'e7247759c1633c0f9f1485f3690294a9');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `chantiers`
--
ALTER TABLE `chantiers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `id_pays` (`id_pays`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `demandes_rebut`
--
ALTER TABLE `demandes_rebut`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_materiel_topo` (`id_materiel_topo`),
  ADD KEY `requested_by` (`requested_by`);

--
-- Index pour la table `familles_topo`
--
ALTER TABLE `familles_topo`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `intervenants`
--
ALTER TABLE `intervenants`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `interventions`
--
ALTER TABLE `interventions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_materiel_topo` (`id_materiel_topo`);

--
-- Index pour la table `journal`
--
ALTER TABLE `journal`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `materiel_topo`
--
ALTER TABLE `materiel_topo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `id_famille_topo` (`id_famille_topo`),
  ADD KEY `id_fournisseur` (`id_fournisseur`),
  ADD KEY `id_chantier` (`id_chantier`);

--
-- Index pour la table `pays`
--
ALTER TABLE `pays`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reforme_materiel`
--
ALTER TABLE `reforme_materiel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_materiel_topo` (`id_materiel_topo`);

--
-- Index pour la table `transfert_materiel`
--
ALTER TABLE `transfert_materiel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_materiel_topo` (`id_materiel_topo`),
  ADD KEY `id_provenance` (`id_provenance`),
  ADD KEY `id_destination` (`id_destination`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `chantiers`
--
ALTER TABLE `chantiers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `demandes_rebut`
--
ALTER TABLE `demandes_rebut`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `familles_topo`
--
ALTER TABLE `familles_topo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `intervenants`
--
ALTER TABLE `intervenants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `interventions`
--
ALTER TABLE `interventions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `journal`
--
ALTER TABLE `journal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `materiel_topo`
--
ALTER TABLE `materiel_topo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `pays`
--
ALTER TABLE `pays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `reforme_materiel`
--
ALTER TABLE `reforme_materiel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `transfert_materiel`
--
ALTER TABLE `transfert_materiel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `chantiers`
--
ALTER TABLE `chantiers`
  ADD CONSTRAINT `chantiers_ibfk_1` FOREIGN KEY (`id_pays`) REFERENCES `pays` (`id`);

--
-- Contraintes pour la table `demandes_rebut`
--
ALTER TABLE `demandes_rebut`
  ADD CONSTRAINT `demandes_rebut_ibfk_1` FOREIGN KEY (`id_materiel_topo`) REFERENCES `materiel_topo` (`id`),
  ADD CONSTRAINT `demandes_rebut_ibfk_2` FOREIGN KEY (`requested_by`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `interventions`
--
ALTER TABLE `interventions`
  ADD CONSTRAINT `interventions_ibfk_1` FOREIGN KEY (`id_materiel_topo`) REFERENCES `materiel_topo` (`id`);

--
-- Contraintes pour la table `materiel_topo`
--
ALTER TABLE `materiel_topo`
  ADD CONSTRAINT `materiel_topo_ibfk_1` FOREIGN KEY (`id_famille_topo`) REFERENCES `familles_topo` (`id`),
  ADD CONSTRAINT `materiel_topo_ibfk_2` FOREIGN KEY (`id_fournisseur`) REFERENCES `fournisseurs` (`id`),
  ADD CONSTRAINT `materiel_topo_ibfk_3` FOREIGN KEY (`id_chantier`) REFERENCES `chantiers` (`id`);

--
-- Contraintes pour la table `reforme_materiel`
--
ALTER TABLE `reforme_materiel`
  ADD CONSTRAINT `reforme_materiel_ibfk_1` FOREIGN KEY (`id_materiel_topo`) REFERENCES `materiel_topo` (`id`);

--
-- Contraintes pour la table `transfert_materiel`
--
ALTER TABLE `transfert_materiel`
  ADD CONSTRAINT `transfert_materiel_ibfk_1` FOREIGN KEY (`id_materiel_topo`) REFERENCES `materiel_topo` (`id`),
  ADD CONSTRAINT `transfert_materiel_ibfk_2` FOREIGN KEY (`id_provenance`) REFERENCES `chantiers` (`id`),
  ADD CONSTRAINT `transfert_materiel_ibfk_3` FOREIGN KEY (`id_destination`) REFERENCES `chantiers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
