<?php
/**
 * transaction宣言用のモデル
 * @author kazunari
 *
 */
class TransactionManager extends AppModel {
    
    public $name = 'TransactionManager';
    public $useTable = false;
    
    public function begin() {
        return $this->getDataSource()->begin();
    }
    
    public function commit() {
        return $this->getDataSource()->commit();
    }
    
    public function rollback() {
        return $this->getDataSource()->rollback();
    }
}