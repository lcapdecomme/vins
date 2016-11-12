ALTER TABLE bouteille ADD nomCepage varchar(500);

UPDATE bouteille b
inner join cepage p
on p.id = b.id_cepage 
SET b.nomCepage=p.nom;

ALTER TABLE bouteille DROP COLUMN id_cepage;
