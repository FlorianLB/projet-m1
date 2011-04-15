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


--
-- Contenu de la table `ue`
--
INSERT INTO `ue` (`id_ue`, `formule`, `code_ue`, `coeff`, `credits`, `libelle`, `annee`) VALUES
(1, 'PA1 + 2PA2', 'G2MA1', 2, 2, 'Analyse et Algèbre 2', 2009),
(2, 'SUP(PA1, PA2)', 'G2MA3', 3, 3, 'Géometrie et Algèbre linéaire', 2009),
(3, 'PA1 + 2PA2 + EvC', 'G2MA5', 2, 2, 'Algorithmique numérique', 2009),
(4, '2PA + EvC', 'ANG', 1, 1, 'Anglais', 2009),
(5, 'EvC', 'TEC', 1, 1, 'Technique Expression Communica', 2009),
(6, '3PA + TP', 'GINF8', 2, 2, 'Programmation Impérative', 2009),
(7, 'PA1 + 2PA2 + TP', 'G1INF3', 4, 4, 'Théorie des langages', 2009);


INSERT INTO `semestre_ue` (`id_ue`, `id_semestre`, `optionelle`) VALUES
(1, 1, 0),
(2, 1, 1),
(3, 2, 0),
(1, 3, 0),
(2, 4, 0),
(3, 3, 0),
(4, 1, 1),
(4, 2, 0),
(5, 1, 0),
(5, 2, 0),
(4, 5, 1),
(5, 6, 0),
(6, 5, 0),
(7, 6, 0);


--
-- Contenu de la table `epreuve`
--
INSERT INTO `epreuve` (`id_epreuve`, `id_ue`, `coeff`, `type_epreuve`) VALUES
(1, 1, 1, 'PA1'),
(2, 1, 2, 'PA2'),
(3, 2, 1, 'PA1'),
(4, 2, 1, 'PA2'),
(5, 3, 1, 'PA1'),
(6, 3, 2, 'PA2'),
(7, 3, 1, 'EvC'),
(8, 4, 2, 'PA'),
(9, 4, 1, 'EvC'),
(10, 5, 1, 'EvC'),
(11, 6, 3, 'PA'),
(12, 6, 1, 'TP'),
(13, 7, 1, 'PA1'),
(14, 7, 2, 'PA2'),
(15, 7, 1, 'TP');


INSERT INTO `statut_semestre` (`statut`, `libelle`) VALUES
('ADM', 'Admis'),
('AJO', 'Ajourné'),
('AJC', 'Ajourné avec contrat'),
('ENC', 'En cours'),
('DET', 'En cours avec dette');

--
-- Contenu de la table `etudiants`
--
INSERT INTO `etudiants` (`num_etudiant`, `nom`, `prenom`, `date_naissance`, `nom_usuel`, `sexe`, `adresse`, `code_postal`, `ville`, `email`, `telephone`) VALUES
(10905684, 'INADJAREN', 'Houda', '1991-12-05', '', 'F', '23 rue de la République', '75000', 'Paris', 'galileecapoutre@gmail.com', '01.02.03.04.05'),
(10907703, 'NELA', 'Samuel', '1987-05-02', '', 'M', '12 rue de la République', '75000', 'Paris', 'galileecapoutre@gmail.com', '01.02.03.04.05'),
(10908728, 'NGUYEN', 'Hoang', '1990-06-20', '', 'M', '15 rue de la République', '75000', 'Paris', 'galileecapoutre@gmail.com', '01.02.03.04.05'),
(10908769, 'HAKEM', 'Abdelmounaim', '1990-09-07', '', 'M', '103 rue de la République', '75000', 'Paris', 'galileecapoutre@gmail.com', '01.02.03.04.05'),
(11001351, 'NIZOU', 'Jeremie', '1992-12-23', '', 'M', '95 rue de la République', '75000', 'Paris', 'galileecapoutre@gmail.com', '01.02.03.04.05'),
(11005748, 'LAM', 'Blandine', '1991-02-21', '', 'F', '785 rue de la République', '75000', 'Paris', 'galileecapoutre@gmail.com', '01.02.03.04.05'),
(11007102, 'NOSLEN', 'William', '1991-08-10', '', 'M', '02 rue de la République', '75000', 'Paris', 'galileecapoutre@gmail.com', '01.02.03.04.05'),
(11008172, 'KENMEGNI DEWAMBA', 'Franck', '1991-04-16', '', 'M', '36 rue de la République', '75000', 'Paris', 'galileecapoutre@gmail.com', '01.02.03.04.05');


--
-- Contenu de la table `etudiants_semestre`
--
INSERT INTO `etudiants_semestre` (`num_etudiant`, `id_semestre`, `statut`, `options`) VALUES
(10905684, 1, 'ENC', NULL),
(10905684, 2, 'ENC', NULL),
(10907703, 2, 'DET', NULL),
(10907703, 3, 'ENC', NULL),
(10907703, 4, 'ENC', NULL),
(10908728, 3, 'ENC', NULL),
(10908728, 4, 'ENC', NULL),
(10908769, 1, 'ENC', NULL),
(10908769, 2, 'ENC', NULL),
(11001351, 1, 'ADM', NULL),
(11001351, 2, 'ADM', NULL),
(11001351, 3, 'ENC', NULL),
(11001351, 4, 'ENC', NULL),
(11005748, 1, 'ENC', NULL),
(11005748, 2, 'ENC', NULL),
(11007102, 3, 'ENC', NULL),
(11007102, 4, 'ENC', NULL),
(11008172, 1, 'ENC', NULL),
(11008172, 2, 'ENC', NULL);



--
-- Contenu de la table `jacl2_group`
--
INSERT INTO `jacl2_group` (`id_aclgrp`, `name`, `code`, `grouptype`, `ownerlogin`) VALUES
(1, 'admins', 'admins', 0, NULL),
(2, 'users', 'users', 1, NULL),
(0, 'anonymous', 'anonymous', 0, NULL),
(4, 'admin', NULL, 2, 'admin');

--
-- Contenu de la table `jacl2_rights`
--
INSERT INTO `jacl2_rights` (`id_aclsbj`, `id_aclgrp`, `id_aclres`) VALUES
('acl.group.create', 1, ''),
('acl.group.delete', 1, ''),
('acl.group.modify', 1, ''),
('acl.group.view', 1, ''),
('acl.user.modify', 1, ''),
('acl.user.view', 1, ''),
('auth.user.change.password', 1, ''),
('auth.user.change.password', 2, ''),
('auth.user.modify', 1, ''),
('auth.user.modify', 2, ''),
('auth.user.view', 1, ''),
('auth.user.view', 2, ''),
('auth.users.change.password', 1, ''),
('auth.users.create', 1, ''),
('auth.users.delete', 1, ''),
('auth.users.list', 1, ''),
('auth.users.modify', 1, ''),
('auth.users.view', 1, '');

--
-- Contenu de la table `jacl2_subject`
--
INSERT INTO `jacl2_subject` (`id_aclsbj`, `label_key`) VALUES
('acl.user.view', 'jelix~acl2db.acl.user.view'),
('acl.user.modify', 'jelix~acl2db.acl.user.modify'),
('acl.group.modify', 'jelix~acl2db.acl.group.modify'),
('acl.group.create', 'jelix~acl2db.acl.group.create'),
('acl.group.delete', 'jelix~acl2db.acl.group.delete'),
('acl.group.view', 'jelix~acl2db.acl.group.view'),
('auth.users.list', 'jelix~auth.acl.users.list'),
('auth.users.view', 'jelix~auth.acl.users.view'),
('auth.users.modify', 'jelix~auth.acl.users.modify'),
('auth.users.create', 'jelix~auth.acl.users.create'),
('auth.users.delete', 'jelix~auth.acl.users.delete'),
('auth.users.change.password', 'jelix~auth.acl.users.change.password'),
('auth.user.view', 'jelix~auth.acl.user.view'),
('auth.user.modify', 'jelix~auth.acl.user.modify'),
('auth.user.change.password', 'jelix~auth.acl.user.change.password');

--
-- Contenu de la table `jacl2_user_group`
--
INSERT INTO `jacl2_user_group` (`login`, `id_aclgrp`) VALUES
('user1', 1),
('user2', 2),
('user3', 2);




