<?php
/**
 * 地方テーブル
 * @author kazunari
 *
 */
class Region extends AppModel {
    public $name = 'Region';
    public $useTable = 'regions';
    
    /**
     * 地方名とIDのリスト
     */
    public function getRegionList() {
        $options = array(
            'fields' => array('id', 'region_name'),
            'order'  => array('id' => 'ASC')
        );
        return $this->find('list', $options);
    }
}