<?php

class TransactionManager extends AppModel {

	public $useTable = false;

	public function begin() {
		return $this->getDataSource()->begin($this);
	}

	public function commit() {
		$this->getDataSource()->commit($this);
	}

	public function rollback() {
		$this->getDataSource()->rollback($this);
	}
}