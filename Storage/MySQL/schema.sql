
/* Main wallpapers */
DROP TABLE IF EXISTS `bono_module_wallpaper`;
CREATE TABLE `bono_module_wallpaper` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `interior_id` INT DEFAULT NULL COMMENT 'Attached iterior image ID',
    `image_id` INT DEFAULT NULL COMMENT 'Attached primary image ID',
    `sku` varchar(255) NOT NULL,
    `purpose` TINYINT NOT NULL COMMENT 'Purpose constant',
    `pattern` TINYINT NOT NULL COMMENT 'Pattern constant',
    `format` TINYINT NOT NULL COMMENT 'Format constant'

) ENGINE = InnoDB DEFAULT CHARSET = UTF8;

DROP TABLE IF EXISTS `bono_module_wallpaper_translations`;
CREATE TABLE `bono_module_wallpaper_translations` (
    `id` INT NOT NULL,
    `lang_id` INT NOT NULL COMMENT 'Language identificator',
    `web_page_id` INT NOT NULL COMMENT 'Web page identificator can be found in site module',
    `title` varchar(255) NOT NULL COMMENT 'Page title',
    `name` varchar(255) NOT NULL COMMENT 'Page name',
    `description` LONGTEXT NOT NULL COMMENT 'Fits for description',
    `keywords` TEXT NOT NULL,
    `meta_description` TEXT NOT NULL COMMENT 'Meta-description for search engines',

    FOREIGN KEY (id) REFERENCES bono_module_wallpaper(id) ON DELETE CASCADE,
    FOREIGN KEY (lang_id) REFERENCES bono_module_cms_languages(id) ON DELETE CASCADE,
    FOREIGN KEY (web_page_id) REFERENCES bono_module_cms_webpages(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = UTF8;

/* Companions */
DROP TABLE IF EXISTS `bono_module_wallpaper_companions`;
CREATE TABLE `bono_module_wallpaper_companions` (
    `master_id` INT NOT NULL COMMENT 'Main wallpaper',
    `slave_id` INT NOT NULL COMMENT 'Attached wallpaper',

    FOREIGN KEY (master_id) REFERENCES bono_module_wallpaper(id) ON DELETE CASCADE,
    FOREIGN KEY (slave_id) REFERENCES bono_module_wallpaper(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = UTF8;

/* Interior */
DROP TABLE IF EXISTS `bono_module_wallpaper_interior`;
CREATE TABLE `bono_module_wallpaper_interior` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `wallpaper_id` INT NOT NULL COMMENT 'Attached wallpaper id',
    `order` INT NOT NULL COMMENT 'Sorting order',
    `filename` varchar(255) NOT NULL,

    FOREIGN KEY (wallpaper_id) REFERENCES bono_module_wallpaper(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = UTF8;

/* Gallery */
DROP TABLE IF EXISTS `bono_module_wallpaper_gallery`;
CREATE TABLE `bono_module_wallpaper_gallery` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `wallpaper_id` INT NOT NULL COMMENT 'Attached wallpaper id',
    `sku` varchar(255) NOT NULL,
    `order` INT NOT NULL COMMENT 'Sorting order',
    `color` TINYINT NOT NULL COMMENT 'Color constant',
    `filename` varchar(255) NOT NULL,

    FOREIGN KEY (wallpaper_id) REFERENCES bono_module_wallpaper(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = UTF8;
