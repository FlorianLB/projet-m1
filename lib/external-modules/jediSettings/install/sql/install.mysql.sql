CREATE TABLE `%%PREFIX%%jedisettings` (
  `key` varchar(30) collate utf8_unicode_ci NOT NULL,
  `value` varchar(250) collate utf8_unicode_ci NOT NULL,
  `label` varchar(50) collate utf8_unicode_ci NOT NULL,
  `last_update` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`key`)
)