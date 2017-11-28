<?php
/**
 * 都道府県テーブル
 * @author kazunari
 * @property Region     $Region
 * @property PostalCode $PostalCode
 */
class Prefecture extends AppModel {
    
    public $name = 'Prefecture';
    public $useTable = 'prefectures';
    /* Prefecture belongs to Region */
    public $belongsTo = array(
        'Region' => array(
            'className' => 'Region',
            'foreignKey' => 'region_id'
        )
    );
    /* Prefecture has many PostalCode */
    public $hasMany = array(
        'PostalCode' => array(
            'className' => 'PostalCode',
            'foreignKey' => 'prefecture_id'
        )
    );

    /**
     * 都道府県リストを取得
     * @param int $regionId
     * @param bool $group
     * @return Ambigous <multitype:, NULL, mixed>
     */
    public function getPrefectureList($regionId = null, $group = false) {
        $options = array(
            'fields' => $group ? array('id', 'prefecture_name', 'region_name') : array('id', 'prefecture_name'),
            'order' => array('id' => 'ASC')
        );
        if (!empty($regionId)) {
            $options['conditions']['region_id'] = $regionId;
        }
        if ($group) {
            $this->virtualFields['region_name'] = 'Region.region_name';
            $options['recursive'] = 2;
        } else {
            $options['recursive'] = -1;
        }
        $this->unbindModel(array('hasMany' => array('PostalCode')));
        return $this->find('list', $options);
    }
    
    /**
     * 都道府県名から取得
     * @param string $prefectureName
     * @return Ambigous <multitype:, NULL, mixed>
     */
    public function getByName($prefectureName) {
        $options = array(
            'conditions' => array(
                'prefecture_name' => $prefectureName
            ),
            'recursive' => -1
        );
        return $this->find('first', $options);
    }
}