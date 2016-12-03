CREATE TABLE `fournisseur` (
  `id` int(10) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `cp` varchar(100),
  `ville` varchar(100),
  `id_utilisateur` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Index pour la table `fournisseur` 
--
ALTER TABLE `fournisseur` 
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT pour la table `fournisseur` 
--
ALTER TABLE `fournisseur` 
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Id_fournisseur pour la table `bouteille` 
--
ALTER TABLE `bouteille` ADD `id_fournisseur` INT(10) NULL AFTER `id_emplacement`;