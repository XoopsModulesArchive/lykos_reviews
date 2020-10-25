# phpMyAdmin MySQL-Dump
# version 2.2.6
# http://phpwizard.net/phpMyAdmin/
# http://www.phpmyadmin.net/ (download page)
#
# Host: localhost
# Generation Time: Nov 27, 2002 at 09:11 AM
# Server version: 4.00.01
# PHP Version: 4.2.2
# Database : `xoops`
# --------------------------------------------------------

#
# Table structure for table `xoops_lykos_reviews_categories`
#

CREATE TABLE lykos_reviews_categories (
    category_id    TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
    category_title VARCHAR(255)        NOT NULL DEFAULT '',
    PRIMARY KEY (category_id)
)
    ENGINE = ISAM;
# --------------------------------------------------------

#
# Table structure for table `xoops_lykos_reviews_contents`
#

CREATE TABLE lykos_reviews_contents (
    review_id       SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    category_id     TINYINT(3) UNSIGNED  NOT NULL DEFAULT '0',
    review_title    VARCHAR(255)         NOT NULL DEFAULT '',
    review_item     VARCHAR(255)         NOT NULL DEFAULT '',
    review_label    VARCHAR(255)         NOT NULL DEFAULT '',
    release_date    VARCHAR(6)           NOT NULL DEFAULT '',
    release_format  VARCHAR(18)          NOT NULL DEFAULT '',
    review_contents TEXT                 NOT NULL,
    review_time     INT(10) UNSIGNED     NOT NULL DEFAULT '0',
    review_uid      INT(6) UNSIGNED      NOT NULL DEFAULT '0',
    review_hits     INT(6) UNSIGNED      NOT NULL DEFAULT '0',
    review_visible  TINYINT(1) UNSIGNED  NOT NULL DEFAULT '0',
    review_nohtml   TINYINT(1) UNSIGNED  NOT NULL DEFAULT '0',
    review_nosmiley TINYINT(1) UNSIGNED  NOT NULL DEFAULT '0',
    review_noxcode  TINYINT(1) UNSIGNED  NOT NULL DEFAULT '0',
    release_amazon  VARCHAR(255)         NOT NULL DEFAULT '',
    release_image   VARCHAR(255)         NOT NULL DEFAULT '',
    PRIMARY KEY (review_id),
    KEY review_title (review_title(40)),
    KEY review_item (review_item(40)),
    KEY review_label (review_label(40)),
    KEY review_visible_category_id (review_visible, category_id),
    FULLTEXT KEY review_contents (review_contents)
)
    ENGINE = ISAM;

