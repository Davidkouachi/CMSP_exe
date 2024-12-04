INSERT INTO `users` (`api_token`, `user_username`, `user_password`, `user_first_name`, `user_last_name`, `user_phone`, `user_profil_id`, `user_email`, `user_rights`, `user_make_date`, `user_revised_date`, `user_ip`, `user_history`, `user_logs`, `user_lang`, `user_photo`, `user_actif`, `user_actions`, `code_personnel`, `photo`) VALUES
(NULL, 'KIMA', '$2y$10$rSNGweHMFYpr89nM6bEodeioLrh9EZ6bjFflGN.KDJPcA1n1h7Ok.', 'KONE', 'KIPOMA', '02054849', 7, 'kimgloire@gmail.com', 'SuperAdmin', 1542646778, 0, '', '', 789, 'fr', 1, 1, 1, '', NULL),
(NULL, 'DJEDOU', '$2y$10$luz5CzAKe6zvjAzIHDnVauaM5Cu4XYzt./Y1gSdrFJYIfwauYX5/m', 'DJEDOU', 'DJEDOU JOSE GHISLAIN', '77325295', 10, '', 'SimpleUser', 1575638011, NULL, NULL, '--o-- Créé le : 2019-12-06 13:13:31 --o--\r\n', 2316, 'fr', 1, 1, 1, '', NULL),
(NULL, 'GNAKAFRANCK', '$2y$10$E7PR9ZAPQwf416iszdie4.gQGfAnf87xD5LpKkBR3Xq1UodZ/CtAu', 'GNAKA', 'SANDRINE LYSE KOKRE', '59209876', 4, '', 'SuperAdmin', 1575635459, NULL, NULL, '--o-- Créé le : 2019-12-06 12:30:59 --o--\r\n', 4477, 'fr', 1, 1, 1, '', NULL),
(NULL, 'AZARO', '$2y$10$N4p1l0Et4pYGPdpszLUy2OFFea4C03JPcgyxK/ZR8fK4w8ha4DA4i', 'KOUADIO', 'LUCIEN', '0101027407', 1, 'lukdio@hotmail.fr', 'SuperAdmin', 1673028887, NULL, NULL, '--o-- Créé le : 2023-01-06 18:14:47 --o--\r\n', 5, 'fr', 0, 1, 0, 'P001', NULL),
(NULL, 'TEST', '$2y$10$DGxFxzhuug/rXC7jbfB8ruZqOXt.T6YryXKKVODSGjnZTrzitEAem', 'Test', 'test', NULL, 14, 'test@gmail.com', 'SuperAdmin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL);

ALTER TABLE `users`
  ADD UNIQUE KEY `user_name` (`user_username`);

COMMIT;
