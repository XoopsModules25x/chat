CREATE TABLE `chat_delmsg` (
  `delmsgid` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `messid`   INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `uid`      INT(11) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`delmsgid`),
  UNIQUE KEY `messid_uid` (`messid`, `uid`)
)
  ENGINE = MEMORY;

CREATE TABLE `chat_message` (
  `messid`      INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `uid`         INT(11)             NOT NULL DEFAULT '0',
  `uname`       VARCHAR(20)         NOT NULL DEFAULT '',
  `time`        INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `ip`          VARCHAR(15)         NOT NULL DEFAULT '',
  `message`     TEXT                NOT NULL,
  `deleted`     TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `deleted_uid` INT(11) UNSIGNED    NOT NULL DEFAULT '0',
  PRIMARY KEY (`messid`),
  KEY `deleted` (`deleted`)
)
  ENGINE = MyISAM;

CREATE TABLE `chat_online` (
  `uid`       INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `uname`     VARCHAR(25)      NOT NULL DEFAULT '',
  `updated`   INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `sleepdate` INT(10)          NOT NULL DEFAULT '0',
  KEY `uid` (`uid`),
  KEY `uname` (`uname`),
  KEY `updated` (`updated`)
)
  ENGINE = MEMORY;
