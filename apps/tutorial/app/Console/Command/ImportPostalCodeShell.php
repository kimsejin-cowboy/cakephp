<?php
/**
 * 
 * @author kazunari
 * @property TransactionManager $TransactionManager
 * @property Prefecture         $Prefecture
 * @property PostalCode         $PostalCode
 */
class ImportPostalCodeShell extends AppShell {
    
    public $uses = array('Prefecture', 'PostalCode', 'TransactionManager');
    
    private $importFile = '/share/vagrant/data/KEN_ALL.CSV';
    
    public function welcome() {
        
    }
    
    public function main() {
        try {
            $this->import();
        } catch (Exception $e) {
            $this->out($e->getMessage());
            if (!empty($e->queryString)) $this->out($e->queryString);
        }
    }
    
    private function import() {

        try {
            if (!file_exists($this->importFile)) throw new Exception('file not exists');
            
            $fp = fopen($this->importFile, 'r');
            if (!$fp) throw new Exception('file open failed');
            $this->TransactionManager->begin();
            
            while (!feof($fp)) {
                $items = fgetcsv($fp, 1024, ',', '"');
                if (count($items) != 15) continue;
            
                array_walk_recursive($items, array($this, 'convert'));
            
                $prefectureId = null;
                if ($prefecture = $this->Prefecture->getByName($items[6])) {
                    $prefectureId = $prefecture[$this->Prefecture->alias]['id'];
                }
                if (empty($prefectureId)) continue;
                
                $data = array(
                    'local_goverment_code' => $items[0],
                    'old_postal_code'      => $items[1],
                    'postal_code'          => $items[2],
                    'prefecture_id'        => $prefectureId,
                    'city_name'            => $items[7],
                    'address'              => $items[8]
                );
                $this->PostalCode->create();
                if (!$this->PostalCode->save($data, false)) throw new Exception('save failed');
//                 throw new Exception('TEST OK');
            }
            if ($fp) fclose($fp);
            
            $this->TransactionManager->commit();
        } catch (Exception $e) {
            $this->TransactionManager->rollback();
            throw $e;
        }

    }
    
    private function convert(&$item, $key) {
        $item = Util::toUTF8($item);
    }
}