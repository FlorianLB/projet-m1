--
-- Contenu de la table `jlx_user`
--

INSERT INTO `jlx_user` (`usr_login`, `usr_password`, `usr_email`) VALUES
('user1', '3a5710ebc9e37a2cf339b8c584ccbe6bc0c2da06', ''),
('user2', 'e0707d7850a71a1bdcb58deb7469dd0eecde62dd', ''),
('user3', '0c85c05216680225ef7f08959d03e31b78dad5a6', '');




INSERT INTO `formation` (`id_formation`, `code_formation`, `annee`, `libelle`) VALUES
(1, 'G1MAT', '2010-2011', 'Licence 1 Maths'),
(2, 'G1MAT', '2011-2012', 'Licence 1 Maths'),
(3, 'G1INF', '2010-2011', 'Licence 1 Informatique');


INSERT INTO `semestre` (`id_semestre`, `id_formation`, `num_semestre`) VALUES
(1, 1, '1'),
(2, 1, '2'),
(3, 2, '1'),
(4, 2, '2'),
(5, 3, '1'),
(6, 3, '2');

INSERT INTO `ue` (`id_ue`, `code_ue`, `coeff`, `credits`, `libelle`, `annee`) VALUES
(1, 'G2MA1', 2, 2, 'Analyse et Algèbre 2', 2010),
(2, 'G2MA3', 3, 3, 'Géometrie et Algèbre linéaire', 2010),
(3, 'G2MA5', 2, 2, 'Algorithmique numérique', 2010),
(4, 'ANG', 1, 1, 'Anglais', 2010),
(5, 'TEC', 1, 1, 'Technique Expression Communica', 2010);

INSERT INTO `semestre_ue` (`id_ue`, `id_semestre`, `optionelle`) VALUES
(1, 1, 0),
(2, 1, 0),
(3, 2, 0),
(4, 1, 0),
(4, 2, 0),
(5, 1, 0),
(5, 2, 0);

INSERT INTO `statut_semestre` (`statut`, `libelle`) VALUES
('OK', 'Valide'),
('NOK', 'Non Valide');