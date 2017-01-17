CREATE TABLE if not exists `lang` (
    `lang_id`   SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`      varchar(2) NOT NULL unique,
    PRIMARY KEY (`lang_id`));
INSERT INTO `lang` (`lang_id`, `name`) VALUES (1, 'en');
INSERT INTO `lang` (`lang_id`, `name`) VALUES (2, 'ru');

CREATE TABLE if not exists `content_type` (
    `content_type_id`   SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`              varchar(255) NOT NULL unique,
    `tag_open`          varchar(255) DEFAULT '<h4>',
    `tag_close`         varchar(255) DEFAULT '</h4>',
    `display_type`      SMALLINT UNSIGNED NOT NULL,
    `display_dt`        tinyint(1) DEFAULT 0,
    `display_user`      tinyint(1) DEFAULT 0,
    `link_type`         SMALLINT UNSIGNED NOT NULL,
    `open_new_page`     tinyint(1) DEFAULT 0,
    `order_field`       SMALLINT UNSIGNED NOT NULL,
    `order_desc`        tinyint(1) DEFAULT 0,
    PRIMARY KEY (`content_type_id`));

INSERT INTO `content_type` (
    `content_type_id`, `name`, `tag_open`, `tag_close`,
    `display_type`, `display_dt`, `display_user`, `link_type`, `open_new_page`,
    `order_field`, `order_desc`)
    VALUES (
    1, 'News page', '<h4>', '</h4>',
    2, 1, 1, 1, 0, 1, 1);
INSERT INTO `content_type` (
    `content_type_id`, `name`, `tag_open`, `tag_close`,
    `display_type`, `display_dt`, `display_user`, `link_type`, `open_new_page`,
    `order_field`, `order_desc`)
    VALUES (
    2, 'Info page', '<h4>', '</h4>',
    3, 0, 0, 2, 0, 1, 1);
INSERT INTO `content_type` (
    `content_type_id`, `name`, `tag_open`, `tag_close`,
    `display_type`, `display_dt`, `display_user`, `link_type`, `open_new_page`,
    `order_field`, `order_desc`)
    VALUES (
    3, 'About page', '<h4>', '</h4>',
    3, 0, 0, 2, 0, 1, 1);