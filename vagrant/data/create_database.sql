

DROP TABLE IF EXISTS `regions`;
CREATE TABLE IF NOT EXISTS `regions` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'åL[',
  `region_name` varchar(255) DEFAULT NULL COMMENT 'nû¼',
  `region_name_ruby` varchar(255) DEFAULT NULL COMMENT 'nû¼©È',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='nû}X^';

DROP TABLE IF EXISTS `prefectures`;
CREATE TABLE `prefectures` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'åL[',
  `region_id` int(3) DEFAULT NULL COMMENT 'nûID',
  `prefecture_name` varchar(255) DEFAULT NULL COMMENT 's¹{§¼',
  `prefecture_name_kana` varchar(255) DEFAULT NULL COMMENT 's¹{§¼©È',
  PRIMARY KEY (`id`),
  INDEX region_id (`region_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='s¹{§}X^';

INSERT INTO `regions` VALUES
  (1,'kC¹nû','zbJChE`zE'),
  (2,'knû','gEzN`zE'),
  (3,'Önû','JgE`zE'),
  (4,'nû','`Eu`zE'),
  (5,'ßEnû','LL`zE'),
  (6,'nû','`ESN`zE'),
  (7,'lnû','VRN`zE'),
  (8,'ãBnû','LEVE`zE');
  
  
INSERT INTO `prefectures` VALUES
  (1,1,'kC¹','zbJChE'),
  (2,2,'ÂX§','AIP'),
  (3,2,'âè§','CeP'),
  (4,2,'{é§','~MP'),
  (5,2,'Hc§','AL^P'),
  (6,2,'R`§','}K^P'),
  (7,2,'§','tNV}P'),
  (8,3,'ïé§','CoLP'),
  (9,3,'ÈØ§','g`MP'),
  (10,3,'Qn§','O}P'),
  (11,3,'éÊ§','TC^}P'),
  (12,3,'çt§','`oP'),
  (13,3,'s','gELEg'),
  (14,3,'_Þì§','JiKP'),
  (15,4,'V§','jCK^P'),
  (16,4,'xR§','g}P'),
  (17,4,'Îì§','CVJP'),
  (18,4,'ä§','tNCP'),
  (19,4,'R§','}iVP'),
  (20,4,'·ì§','iKmP'),
  (21,4,'ò§','MtP'),
  (22,4,'Ãª§','VYIJP'),
  (23,4,'¤m§','AC`P'),
  (24,5,'Od§','~GP'),
  (25,5,' ê§','VKP'),
  (26,5,'s{','LEgt'),
  (27,5,'åã{','IITJt'),
  (28,5,'ºÉ§','qESP'),
  (29,5,'ÞÇ§','iP'),
  (30,5,'aÌR§','J}P'),
  (31,6,'¹æ§','gbgP'),
  (32,6,'ª§','V}lP'),
  (33,6,'ªR§','IJ}P'),
  (34,6,'L§','qV}P'),
  (35,6,'Rû§','}O`P'),
  (36,7,'¿§','gNV}P'),
  (37,7,'ì§','JKP'),
  (38,7,'¤Q§','GqP'),
  (39,7,'m§','RE`P'),
  (40,8,'ª§','tNIJP'),
  (41,8,'²ê§','TKP'),
  (42,8,'·è§','iKTLP'),
  (43,8,'F{§','N}gP'),
  (44,8,'åª§','IIC^P'),
  (45,8,'{è§','~ULP'),
  (46,8,'­§','JSV}P'),
  (47,8,'«ê§','ILiP');

DROP TABLE IF EXISTS `postal_codes`;
CREATE TABLE `postal_codes` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'åL[',
 `local_goverment_code` int(6) unsigned zerofill NOT NULL COMMENT 'Snûö¤cÌR[h',
 `old_postal_code` char(5) NOT NULL COMMENT 'XÖÔ',
 `postal_code` char(7) NOT NULL COMMENT 'XÖÔ',
 `prefecture_id` int(3) NOT NULL COMMENT 's¹{§ID',
 `city_name` varchar(256) NOT NULL COMMENT 'sæ¬º¼',
 `address` varchar(256) NOT NULL COMMENT '¬æ¼',
 `created` datetime NOT NULL COMMENT 'ì¬ú',
 `modified` datetime NOT NULL COMMENT 'XVú',
 PRIMARY KEY (`id`),
 KEY `postal_code` (`postal_code`),
 KEY `prefecture_id` (`prefecture_id`,`postal_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='XÖÔ}X^'


