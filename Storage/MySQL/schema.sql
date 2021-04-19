/* Main wallpapers */
DROP TABLE IF EXISTS `bono_module_wallpaper`;
CREATE TABLE `bono_module_wallpaper` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `sku` varchar(255) NOT NULL
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