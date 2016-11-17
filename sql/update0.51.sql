ALTER TABLE bouteille ADD nomCepage varchar(500);

UPDATE bouteille b
inner join cepage p
on p.id = b.id_cepage 
SET b.nomCepage=p.nom;

ALTER TABLE bouteille DROP COLUMN id_cepage;

alter table emplacement add id_utilisateur int(10);

alter table utilisateur add nb_vins_affiches int(5);

alter table emplacement MODIFY id int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;
