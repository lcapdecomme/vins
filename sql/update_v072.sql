--
-- Table Fournisseur
--
ALTER TABLE `fournisseur` ADD `adresse` VARCHAR(1000) NULL  AFTER `nom`;
ALTER TABLE `fournisseur` ADD `telFixe` VARCHAR(20) NULL AFTER `ville`;
ALTER TABLE `fournisseur` ADD `telPortable` VARCHAR(20) NULL AFTER `telFixe`;
ALTER TABLE `fournisseur` ADD `mail` VARCHAR(50) NULL AFTER `telPortable`;
ALTER TABLE `fournisseur` ADD `url` VARCHAR(50) NULL AFTER `mail`;