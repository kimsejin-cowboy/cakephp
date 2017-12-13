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
            'rule0' => array(
                'rule' => array('notEmpty'),
                'message' => '全国地方公共団体コードが空です'
            )
        )
    );

    /* PostalCode belongs to Prefecture */
    public $belongsTo = array(
        'Prefecture' => array(
            'className' => 'Prefecture',
            'foreignKey' => 'prefecture_id'
        )
    );
}