

DROP TABLE IF EXISTS `regions`;
CREATE TABLE IF NOT EXISTS `regions` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '��L�[',
  `region_name` varchar(255) DEFAULT NULL COMMENT '�n����',
  `region_name_ruby` varchar(255) DEFAULT NULL COMMENT '�n��������',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='�n���}�X�^';

DROP TABLE IF EXISTS `prefectures`;
CREATE TABLE `prefectures` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '��L�[',
  `region_id` int(3) DEFAULT NULL COMMENT '�n��ID',
  `prefecture_name` varchar(255) DEFAULT NULL COMMENT '�s���{����',
  `prefecture_name_kana` varchar(255) DEFAULT NULL COMMENT '�s���{��������',
  PRIMARY KEY (`id`),
  INDEX region_id (`region_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='�s���{���}�X�^';

INSERT INTO `regions` VALUES
  (1,'�k�C���n��','�z�b�J�C�h�E�`�z�E'),
  (2,'���k�n��','�g�E�z�N�`�z�E'),
  (3,'�֓��n��','�J���g�E�`�z�E'),
  (4,'�����n��','�`���E�u�`�z�E'),
  (5,'�ߋE�n��','�L���L�`�z�E'),
  (6,'�����n��','�`���E�S�N�`�z�E'),
  (7,'�l���n��','�V�R�N�`�z�E'),
  (8,'��B�n��','�L���E�V���E�`�z�E');
  
  
INSERT INTO `prefectures` VALUES
  (1,1,'�k�C��','�z�b�J�C�h�E'),
  (2,2,'�X��','�A�I�����P��'),
  (3,2,'��茧','�C���e�P��'),
  (4,2,'�{�錧','�~���M�P��'),
  (5,2,'�H�c��','�A�L�^�P��'),
  (6,2,'�R�`��','���}�K�^�P��'),
  (7,2,'������','�t�N�V�}�P��'),
  (8,3,'��錧','�C�o���L�P��'),
  (9,3,'�Ȗ،�','�g�`�M�P��'),
  (10,3,'�Q�n��','�O���}�P��'),
  (11,3,'��ʌ�','�T�C�^�}�P��'),
  (12,3,'��t��','�`�o�P��'),
  (13,3,'�����s','�g�E�L���E�g'),
  (14,3,'�_�ސ쌧','�J�i�K���P��'),
  (15,4,'�V����','�j�C�K�^�P��'),
  (16,4,'�x�R��','�g���}�P��'),
  (17,4,'�ΐ쌧','�C�V�J���P��'),
  (18,4,'���䌧','�t�N�C�P��'),
  (19,4,'�R����','���}�i�V�P��'),
  (20,4,'���쌧','�i�K�m�P��'),
  (21,4,'�򕌌�','�M�t�P��'),
  (22,4,'�É���','�V�Y�I�J�P��'),
  (23,4,'���m��','�A�C�`�P��'),
  (24,5,'�O�d��','�~�G�P��'),
  (25,5,'���ꌧ','�V�K�P��'),
  (26,5,'���s�{','�L���E�g�t'),
  (27,5,'���{','�I�I�T�J�t'),
  (28,5,'���Ɍ�','�q���E�S�P��'),
  (29,5,'�ޗǌ�','�i���P��'),
  (30,5,'�a�̎R��','���J���}�P��'),
  (31,6,'���挧','�g�b�g���P��'),
  (32,6,'������','�V�}�l�P��'),
  (33,6,'���R��','�I�J���}�P��'),
  (34,6,'�L����','�q���V�}�P��'),
  (35,6,'�R����','���}�O�`�P��'),
  (36,7,'������','�g�N�V�}�P��'),
  (37,7,'���쌧','�J�K���P��'),
  (38,7,'���Q��','�G�q���P��'),
  (39,7,'���m��','�R�E�`�P��'),
  (40,8,'������','�t�N�I�J�P��'),
  (41,8,'���ꌧ','�T�K�P��'),
  (42,8,'���茧','�i�K�T�L�P��'),
  (43,8,'�F�{��','�N�}���g�P��'),
  (44,8,'�啪��','�I�I�C�^�P��'),
  (45,8,'�{�茧','�~���U�L�P��'),
  (46,8,'��������','�J�S�V�}�P��'),
  (47,8,'���ꌧ','�I�L�i���P��');

DROP TABLE IF EXISTS `postal_codes`;
CREATE TABLE `postal_codes` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '��L�[',
 `local_goverment_code` int(6) unsigned zerofill NOT NULL COMMENT '�S���n�������c�̃R�[�h',
 `old_postal_code` char(5) NOT NULL COMMENT '���X�֔ԍ�',
 `postal_code` char(7) NOT NULL COMMENT '�X�֔ԍ�',
 `prefecture_id` int(3) NOT NULL COMMENT '�s���{��ID',
 `city_name` varchar(256) NOT NULL COMMENT '�s�撬����',
 `address` varchar(256) NOT NULL COMMENT '���於',
 `created` datetime NOT NULL COMMENT '�쐬����',
 `modified` datetime NOT NULL COMMENT '�X�V����',
 PRIMARY KEY (`id`),
 KEY `postal_code` (`postal_code`),
 KEY `prefecture_id` (`prefecture_id`,`postal_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='�X�֔ԍ��}�X�^'


