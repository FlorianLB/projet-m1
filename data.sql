--
-- Contenu de la table `jlx_user`
--

INSERT INTO `jlx_user` (`usr_login`, `usr_password`, `usr_email`) VALUES
('user1', '3a5710ebc9e37a2cf339b8c584ccbe6bc0c2da06', ''),
('user2', 'e0707d7850a71a1bdcb58deb7469dd0eecde62dd', ''),
('user3', '0c85c05216680225ef7f08959d03e31b78dad5a6', '');




INSERT INTO `formation` (`id_formation`, `code_formation`, `annee`, `libelle`) VALUES
(1, 'L2MATH', '2010-2011', 'L2 MathÃ©matiques'),
(2, 'L2INFO', '2010-2011', 'L2 Informatique');


INSERT INTO `semestre` (`id_semestre`, `id_formation`, `num_semestre`) VALUES
(1, 1, '1'),
(2, 1, '2'),
(3, 2, '1'),
(4, 2, '2');

INSERT INTO `ue` (`id_ue`, `code_ue`, `coeff`, `credits`, `libelle`, `last_version`) VALUES
(1, 'G2MA1', 2, 2, 'Analyse et AlgÃ¨bre 2', 1),
(2, 'G2MA3', 3, 3, 'Géometrie et Algèbre linéaire', 1),
(3, 'G2MA5', 2, 2, 'Algorithmique numÃ©rique', 1),
(4, 'ANG', 1, 1, 'Anglais', 1),
(5, 'TEC', 1, 1, 'Technique Expression Communica', 1);

INSERT INTO `semestre_ue` (`id_ue`, `id_semestre`, `optionelle`) VALUES
(1, 1, 0),
(2, 1, 0),
(3, 2, 0),
(4, 1, 0),
(4, 2, 0),
(5, 1, 0),
(5, 2, 0);