<?php
/**
 * 郵便番号テーブル
 * @author kazunari
 * @property Prefecture $Prefecture
 */
class PostalCode extends AppModel {

	public $name = 'PostalCode';
	public $useTable = 'postal_codes';

	/* validation settings */
	/**
	 * 全国地方公共団体コード（JIS X0401、X0402）………　半角数字5桁
	 * （旧）郵便番号（5桁）………………………………………　半角数字、郵便番号の先頭５桁
	 * 郵便番号（7桁）………………………………………　半角数字
	 * 都道府県ID　…………　prefecturesに存在すること
	 * 市区町村名　…………　漢字（コード順に掲載）　（注1,2）
	 * 町域名　………………　漢字（五十音順に掲載）　（注1,2）
	 */

	public $validate = array(
					'local_goverment_code' => array(
							'alphanumeric' => array(
									'rule' => 'numeric',
									'message' => '数字を入力してください。'
							),
							'between' => array(
									'rule' => array('between', 5, 5),
									'message' => '5文字にしてください'
							),
							'rule0' => array(
									'rule'=> 'notEmpty',
									'message'=>'必須です！！'
							)


					),
					'postal_code' => array(
							'alphanumeric' => array(
									'rule' => 'numeric',
									'message' => '数字を入力してください。'
							),
							'between7' => array(
									'rule' => array('between', 7, 7),
									'message' => '7文字にしてください'
							)
					)


/*
			'local_goverment_code' => array(
					'rule0' => array(
							'rule'=> 'notEmpty',
							'message'=>'必須です！！'
					),
					'numelic' => array(
							'rule' => 'numelic',
							'message' => '5～15文字です'
					)
			)*/
	);//isHalfLetter

	/* PostalCode belongs to Prefecture */
	public $belongsTo = array(
			'Prefecture' => array(
					'className' => 'Prefecture',
					'foreignKey' => 'prefecture_id'
			)
	);
}