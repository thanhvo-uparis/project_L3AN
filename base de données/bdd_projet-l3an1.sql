-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : ven. 16 avr. 2021 à 18:14
-- Version du serveur :  10.4.11-MariaDB
-- Version de PHP : 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bdd_projet-l3an1`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie_general`
--

CREATE TABLE `categorie_general` (
  `id` int(11) NOT NULL,
  `nom_categorie` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `categorie_general`
--

INSERT INTO `categorie_general` (`id`, `nom_categorie`) VALUES
(1, 'Sécurité logique'),
(2, 'Gestion du changement'),
(7, 'Gestion du chargement');

-- --------------------------------------------------------

--
-- Structure de la table `categorie_mission`
--

CREATE TABLE `categorie_mission` (
  `id` int(11) NOT NULL,
  `id_categorie` int(11) NOT NULL,
  `id_mission` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `categorie_mission`
--

INSERT INTO `categorie_mission` (`id`, `id_categorie`, `id_mission`) VALUES
(5, 1, 1426),
(27, 7, 1426),
(34, 2, 1425),
(35, 1, 1425),
(36, 1, 1425),
(37, 7, 1426);

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

CREATE TABLE `commentaires` (
  `id` int(11) NOT NULL,
  `commentaire` text CHARACTER SET utf8 NOT NULL,
  `date_commentaire` date NOT NULL,
  `id_controle` int(11) NOT NULL,
  `email_utilisateur` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `commentaires`
--

INSERT INTO `commentaires` (`id`, `commentaire`, `date_commentaire`, `id_controle`, `email_utilisateur`) VALUES
(42, 'ajouter un commentaire', '2021-03-21', 17, 'benjaminbenharbon@gmail.com'),
(44, 'ajouter un commentaire', '2021-03-21', 17, 'benjaminbenharbon@gmail.com'),
(45, 'test', '2021-03-21', 17, 'benjaminbenharbon@gmail.com'),
(47, 'je test ', '2021-03-24', 24, 'chouat.david@mazars.fr'),
(48, 'coaijpqs,', '2021-03-26', 29, 'chouat.david@mazars.fr'),
(49, 'ajout d\'un commentaires\n', '2021-03-30', 28, 'lova.rakotozafy@mazars.fr'),
(50, 'test', '2021-03-31', 24, 'chouat.david@mazars.fr'),
(51, 'vjhbkl', '2021-04-07', 21, 'benjaminbenharbon@gmail.com'),
(52, 'rytugihojp\n', '2021-04-11', 35, 'chouat.david@mazars.fr'),
(54, 'voici mon commentaire\n', '2021-04-14', 28, 'chouat.david@mazars.fr'),
(57, 'test', '2021-04-14', 73, 'chouat.david@mazars.fr'),
(58, 'test', '2021-04-14', 66, 'chouat.david@mazars.fr');

-- --------------------------------------------------------

--
-- Structure de la table `controle`
--

CREATE TABLE `controle` (
  `id` int(11) NOT NULL,
  `mission_id` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `nom_du_controle` varchar(1000) NOT NULL,
  `deadline` date NOT NULL,
  `email_utilisateur_realise_par` varchar(255) NOT NULL,
  `email_utilisateur_revu_par` varchar(255) NOT NULL,
  `email_utilisateur_sign_off` varchar(255) NOT NULL,
  `statut` enum('Non débuté','Documenté','Revu','Sign-off') NOT NULL,
  `niveau_de_risque` enum('Élevé','Moyen','Faible') NOT NULL,
  `design` enum('Non effectif','Remarque mineure','Remarque majeure','Effectif') NOT NULL,
  `efficacite` enum('Non effectif','Remarque mineure','Remarque majeure','Effectif') NOT NULL,
  `lu` tinyint(1) NOT NULL,
  `lu_statut` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `controle`
--

INSERT INTO `controle` (`id`, `mission_id`, `categorie_id`, `nom_du_controle`, `deadline`, `email_utilisateur_realise_par`, `email_utilisateur_revu_par`, `email_utilisateur_sign_off`, `statut`, `niveau_de_risque`, `design`, `efficacite`, `lu`, `lu_statut`) VALUES
(78, 1425, 1, 'application', '2021-04-15', 'berth.paul@mazars.fr', 'nachet.haroune@mazars.fr', 'chouat.david@mazars.fr', 'Documenté', 'Moyen', 'Remarque majeure', 'Remarque mineure', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `equipe`
--

CREATE TABLE `equipe` (
  `id` int(11) NOT NULL,
  `email_utilisateur` varchar(255) CHARACTER SET utf8 NOT NULL,
  `role` varchar(255) CHARACTER SET utf8 NOT NULL,
  `id_mission` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `equipe`
--

INSERT INTO `equipe` (`id`, `email_utilisateur`, `role`, `id_mission`) VALUES
(1, 'arbouche.anas@mazars.fr', 'Junior', 1424),
(4, 'chouat.david@mazars.fr', 'Senior', 1425),
(5, 'benjaminbenharbon@gmail.com', 'Associé', 1424),
(9, 'benjaminbenharbon@gmail.com', 'Senior', 1425),
(10, 'roch.ethan@mazars.fr', 'Senior', 1424),
(12, 'roch.ethan@mazars.fr', 'Senior', 1426),
(13, 'lova.rakotozafy@mazars.fr', 'Senior', 1424),
(17, 'lova.rakotozafy@mazars.fr', 'Senior', 1425),
(21, 'benjaminbenharbon@gmail.com', 'Senior', 1426),
(34, 'nachet.haroune@mazars.fr', 'Senior', 1425),
(35, 'chouat.david@mazars.fr', 'Associé', 1426),
(38, 'chouat.david@mazars.fr', 'Senior Manager', 1424),
(39, 'berth.paul@mazars.fr', 'Junior', 1425),
(40, 'paul.jean@mazars.fr', 'Junior', 1426),
(43, 'chouat.david@mazars.fr', 'Junior', 1424);

-- --------------------------------------------------------

--
-- Structure de la table `fichier_controle`
--

CREATE TABLE `fichier_controle` (
  `id` int(11) NOT NULL,
  `id_controle` int(11) NOT NULL,
  `nom_fichier` varchar(255) NOT NULL,
  `chemin_fichier` varchar(255) NOT NULL,
  `date_depot` date NOT NULL,
  `email_utilisateur` varchar(255) NOT NULL,
  `commentaires_fichier` text NOT NULL,
  `categorie_fichier` enum('Preuves','Fiches de travail','Information produced by Entity') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `mission`
--

CREATE TABLE `mission` (
  `mission_id` int(11) NOT NULL,
  `mission_nom` varchar(255) NOT NULL,
  `email_proprietaire` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `mission`
--

INSERT INTO `mission` (`mission_id`, `mission_nom`, `email_proprietaire`) VALUES
(1424, 'BNP Paribas', 'chouat.david@mazars.fr'),
(1425, 'LCL', 'chouat.david@mazars.fr'),
(1426, 'CREDIT AGRICOLE', 'chouat.david@mazars.fr'),
(1427, 'test', 'pixars.disney@mazars.fr'),
(1430, 'Natixis', 'chouat.david@mazars.fr'),
(1445, 'Mission test ', 'chouat.david@mazars.fr');

-- --------------------------------------------------------

--
-- Structure de la table `set_colonne`
--

CREATE TABLE `set_colonne` (
  `id` int(11) NOT NULL,
  `mission` int(1) NOT NULL,
  `categorie` int(1) NOT NULL,
  `nom_du_controle` int(1) NOT NULL,
  `deadline` int(1) NOT NULL,
  `affecte_a` int(1) NOT NULL,
  `statut` int(1) NOT NULL,
  `niveau_de_risque` int(1) NOT NULL,
  `design` int(1) NOT NULL,
  `efficacite` int(1) NOT NULL,
  `commentaires` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `set_colonne`
--

INSERT INTO `set_colonne` (`id`, `mission`, `categorie`, `nom_du_controle`, `deadline`, `affecte_a`, `statut`, `niveau_de_risque`, `design`, `efficacite`, `commentaires`) VALUES
(1, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `email` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `numero_telephone` varchar(255) NOT NULL,
  `role_mission` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`email`, `nom`, `prenom`, `numero_telephone`, `role_mission`, `mot_de_passe`) VALUES
('', '', '', '', '', '534c9a5b050c2b082b4c6b85766bdee4'),
('arbouche.anas@mazars.fr', 'ARBOUCHE', 'Anas', '0612345789', 'Junior', '534c9a5b050c2b082b4c6b85766bdee4'),
('benjaminbenharbon@gmail.com', 'Benharbon', 'Benjamin', '0612348789', 'Junior', '534c9a5b050c2b082b4c6b85766bdee4'),
('berth.paul@mazars.fr', 'BERTH', 'Paul', '0612345689', 'Associé', '534c9a5b050c2b082b4c6b85766bdee4'),
('chouat.david@mazars.fr', 'CHOUAT', 'David', '0612345679', 'Associé', '534c9a5b050c2b082b4c6b85766bdee4'),
('dupond.eric@mazars.fr', 'DUPOND', 'Eric', '0706123456', 'Senior', '534c9a5b050c2b082b4c6b85766bdee4'),
('lova.rakotozafy@mazars.fr', 'RAKOTOZAFY', 'Lova', '0808080808', 'Senior', '534c9a5b050c2b082b4c6b85766bdee4'),
('nachet.haroune@mazars.fr', 'Nachet', 'Haroune', '0645345689', 'Junior', '534c9a5b050c2b082b4c6b85766bdee4'),
('paul.jean@mazars.fr', 'PAUL', 'Jean', '0612345898', 'Senior', '534c9a5b050c2b082b4c6b85766bdee4'),
('pierrick.david@mazars.fr', 'PIERRICK', 'David', '0612345607', 'Senior Manager', '534c9a5b050c2b082b4c6b85766bdee4'),
('pixars.disney@mazars.fr', 'PIXARS', 'Disney', '0612307456', 'Senior Manager', '534c9a5b050c2b082b4c6b85766bdee4'),
('roch.ethan@mazars.fr', 'ROCH', 'Ethan', '0612345698', 'Associé', '534c9a5b050c2b082b4c6b85766bdee4'),
('vo.thanh@mazars.fr', 'VO', 'Thanh', '0612345697', 'Junior', '534c9a5b050c2b082b4c6b85766bdee4');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie_general`
--
ALTER TABLE `categorie_general`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `categorie_mission`
--
ALTER TABLE `categorie_mission`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `controle`
--
ALTER TABLE `controle`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `equipe`
--
ALTER TABLE `equipe`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `fichier_controle`
--
ALTER TABLE `fichier_controle`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `mission`
--
ALTER TABLE `mission`
  ADD PRIMARY KEY (`mission_id`);

--
-- Index pour la table `set_colonne`
--
ALTER TABLE `set_colonne`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categorie_general`
--
ALTER TABLE `categorie_general`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `categorie_mission`
--
ALTER TABLE `categorie_mission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `commentaires`
--
ALTER TABLE `commentaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT pour la table `controle`
--
ALTER TABLE `controle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT pour la table `equipe`
--
ALTER TABLE `equipe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT pour la table `fichier_controle`
--
ALTER TABLE `fichier_controle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT pour la table `mission`
--
ALTER TABLE `mission`
  MODIFY `mission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1446;

--
-- AUTO_INCREMENT pour la table `set_colonne`
--
ALTER TABLE `set_colonne`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
