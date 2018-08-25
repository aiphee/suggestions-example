CREATE TABLE `terms` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `term` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Original value',
    `term_standardized` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Lowercase and without diacritics',
    PRIMARY KEY (`id`),
    UNIQUE KEY `term` (`term`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci; 
