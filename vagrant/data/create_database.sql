

DROP TABLE IF EXISTS `regions`;
CREATE TABLE IF NOT EXISTS `regions` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '主キー',
  `region_name` varchar(255) DEFAULT NULL COMMENT '地方名',
  `region_name_ruby` varchar(255) DEFAULT NULL COMMENT '地方名かな',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='地方マスタ';

DROP TABLE IF EXISTS `prefectures`;
CREATE TABLE `prefectures` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '主キー',
  `region_id` int(3) DEFAULT NULL COMMENT '地方ID',
  `prefecture_name` varchar(255) DEFAULT NULL COMMENT '都道府県名',
  `prefecture_name_kana` varchar(255) DEFAULT NULL COMMENT '都道府県名かな',
  PRIMARY KEY (`id`),
  INDEX region_id (`region_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='都道府県マスタ';

INSERT INTO `regions` VALUES
  (1,'北海道地方','ホッカイドウチホウ'),
  (2,'東北地方','トウホクチホウ'),
  (3,'関東地方','カントウチホウ'),
  (4,'中部地方','チュウブチホウ'),
  (5,'近畿地方','キンキチホウ'),
  (6,'中国地方','チュウゴクチホウ'),
  (7,'四国地方','シコクチホウ'),
  (8,'九州地方','キュウシュウチホウ');
  
  
INSERT INTO `prefectures` VALUES
  (1,1,'北海道','ホッカイドウ'),
  (2,2,'青森県','アオモリケン'),
  (3,2,'岩手県','イワテケン'),
  (4,2,'宮城県','ミヤギケン'),
  (5,2,'秋田県','アキタケン'),
  (6,2,'山形県','ヤマガタケン'),
  (7,2,'福島県','フクシマケン'),
  (8,3,'茨城県','イバラキケン'),
  (9,3,'栃木県','トチギケン'),
  (10,3,'群馬県','グンマケン'),
  (11,3,'埼玉県','サイタマケン'),
  (12,3,'千葉県','チバケン'),
  (13,3,'東京都','トウキョウト'),
  (14,3,'神奈川県','カナガワケン'),
  (15,4,'新潟県','ニイガタケン'),
  (16,4,'富山県','トヤマケン'),
  (17,4,'石川県','イシカワケン'),
  (18,4,'福井県','フクイケン'),
  (19,4,'山梨県','ヤマナシケン'),
  (20,4,'長野県','ナガノケン'),
  (21,4,'岐阜県','ギフケン'),
  (22,4,'静岡県','シズオカケン'),
  (23,4,'愛知県','アイチケン'),
  (24,5,'三重県','ミエケン'),
  (25,5,'滋賀県','シガケン'),
  (26,5,'京都府','キョウトフ'),
  (27,5,'大阪府','オオサカフ'),
  (28,5,'兵庫県','ヒョウゴケン'),
  (29,5,'奈良県','ナラケン'),
  (30,5,'和歌山県','ワカヤマケン'),
  (31,6,'鳥取県','トットリケン'),
  (32,6,'島根県','シマネケン'),
  (33,6,'岡山県','オカヤマケン'),
  (34,6,'広島県','ヒロシマケン'),
  (35,6,'山口県','ヤマグチケン'),
  (36,7,'徳島県','トクシマケン'),
  (37,7,'香川県','カガワケン'),
  (38,7,'愛媛県','エヒメケン'),
  (39,7,'高知県','コウチケン'),
  (40,8,'福岡県','フクオカケン'),
  (41,8,'佐賀県','サガケン'),
  (42,8,'長崎県','ナガサキケン'),
  (43,8,'熊本県','クマモトケン'),
  (44,8,'大分県','オオイタケン'),
  (45,8,'宮崎県','ミヤザキケン'),
  (46,8,'鹿児島県','カゴシマケン'),
  (47,8,'沖縄県','オキナワケン');

DROP TABLE IF EXISTS `postal_codes`;
CREATE TABLE `postal_codes` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主キー',
 `local_goverment_code` int(6) unsigned zerofill NOT NULL COMMENT '全国地方公共団体コード',
 `old_postal_code` char(5) NOT NULL COMMENT '旧郵便番号',
 `postal_code` char(7) NOT NULL COMMENT '郵便番号',
 `prefecture_id` int(3) NOT NULL COMMENT '都道府県ID',
 `city_name` varchar(256) NOT NULL COMMENT '市区町村名',
 `address` varchar(256) NOT NULL COMMENT '町域名',
 `created` datetime NOT NULL COMMENT '作成日時',
 `modified` datetime NOT NULL COMMENT '更新日時',
 PRIMARY KEY (`id`),
 KEY `postal_code` (`postal_code`),
 KEY `prefecture_id` (`prefecture_id`,`postal_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='郵便番号マスタ'


