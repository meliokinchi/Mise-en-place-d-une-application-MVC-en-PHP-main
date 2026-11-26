USE covoit_mvc;

-- A) Agences

INSERT INTO `agencies` (`id`, `name`) VALUES
(9, 'Bordeaux'),
(10, 'Lille'),
(2, 'Lyon'),
(3, 'Marseille'),
(8, 'Montpellier'),
(6, 'Nantes'),
(7, 'Strasbourg'),
(5, 'Nice'),
(1, 'Paris'),
(12, 'Reims'),
(11, 'Rennes'),
(4, 'Toulouse');

-- B) Utilisateurs

INSERT INTO `users`
    (`id`, `first_name`, `last_name`, `phone`, `email`, `is_admin`)
VALUES
(1, 'Alexandre', 'Martin', '0612345678', 'alexandre.martin@email.fr', 1),
(2, 'Sophie', 'Dubois', '0698765432', 'sophie.dubois@email.fr', 1),
(3, 'Julien', 'Bernard', '0622446688', 'julien.bernard@email.fr', 0),
(4, 'Camille', 'Moreau', '0611223344', 'camille.moreau@email.fr', 0),
(5, 'Lucie', 'Lefèvre', '0777889900', 'lucie.lefevre@email.fr', 0),
(6, 'Thomas', 'Leroy', '0655443322', 'thomas.leroy@email.fr', 0),
(7, 'Chloé', 'Roux', '0633221199', 'chloe.roux@email.fr', 0),
(8, 'Maxime', 'Petit', '0766778899', 'maxime.petit@email.fr', 0),
(9, 'Laura', 'Garnier', '0688776655', 'laura.garnier@email.fr', 0),
(10, 'Antoine', 'Dupuis', '0744556677', 'antoine.dupuis@email.fr', 0),
(11, 'Emma', 'Lefebvre', '0699887766', 'emma.lefebvre@email.fr', 0),
(12, 'Louis', 'Fontaine', '0655667788', 'louis.fontaine@email.fr', 0),
(13, 'Clara', 'Chevalier', '0788990011', 'clara.chevalier@email.fr', 0),
(14, 'Nicolas', 'Robin', '0644332211', 'nicolas.robin@email.fr', 0),
(15, 'Marine', 'Gauthier', '0677889922', 'marine.gauthier@email.fr', 0),
(16, 'Pierre', 'Fournier', '0722334455', 'pierre.fournier@email.fr', 0),
(17, 'Sarah', 'Girard', '0688665544', 'sarah.girard@email.fr', 0),
(18, 'Hugo', 'Lambert', '0611223366', 'hugo.lambert@email.fr', 0),
(19, 'Julie', 'Masson', '0733445566', 'julie.masson@email.fr', 0),
(20, 'Arthur', 'Henry', '0666554433', 'arthur.henry@email.fr', 0);

-- Seuls les deux comptes utilisés pour la démonstration ont un mot de passe.

UPDATE `users`
SET `password_hash` = '$2y$10$1jfUAE8OFTDRZP.oXxuWz.ftATWdkD6Cer5kDTeVnAmlhMSs60ihy'
WHERE `email` = 'alexandre.martin@email.fr';

UPDATE `users`
SET `password_hash` = '$2y$10$k4deevi1eo8/GvglIbgTQOoUf2K7.8lyZOJrcNhfr4DrfJ1tdvD6C'
WHERE `email` = 'julien.bernard@email.fr';

-- C) Trajets d'exemple

SET @jour_import = CURDATE();

INSERT INTO `trips` (
    `id`, `departure_agency_id`, `departure_at`,
    `arrival_agency_id`, `arrival_at`,
    `seats_total`, `seats_available`, `created_by_user_id`
) VALUES
-- Futur avec places : Paris vers Lyon, par Alexandre.
(1, 1, TIMESTAMP(DATE_ADD(@jour_import, INTERVAL 1 DAY), '08:00:00'),
    2, TIMESTAMP(DATE_ADD(@jour_import, INTERVAL 1 DAY), '11:00:00'),
    4, 2, 1),
-- Futur complet : exclu de l'accueil.
(2, 2, TIMESTAMP(DATE_ADD(@jour_import, INTERVAL 2 DAY), '09:15:00'),
    1, TIMESTAMP(DATE_ADD(@jour_import, INTERVAL 2 DAY), '12:30:00'),
    3, 0, 2),
-- Passé avec places : exclu de l'accueil.
(3, 9, TIMESTAMP(DATE_SUB(@jour_import, INTERVAL 1 DAY), '08:00:00'),
    8, TIMESTAMP(DATE_SUB(@jour_import, INTERVAL 1 DAY), '14:00:00'),
    4, 2, 1),
-- Futur avec places : Nantes vers Rennes.
(4, 6, TIMESTAMP(DATE_ADD(@jour_import, INTERVAL 3 DAY), '14:20:00'),
    11, TIMESTAMP(DATE_ADD(@jour_import, INTERVAL 3 DAY), '18:15:00'),
    4, 4, 1),
-- Futur avec places : Strasbourg vers Lille, par Julien.
(5, 7, TIMESTAMP(DATE_ADD(@jour_import, INTERVAL 2 DAY), '07:30:00'),
    10, TIMESTAMP(DATE_ADD(@jour_import, INTERVAL 2 DAY), '13:30:00'),
    4, 1, 3);