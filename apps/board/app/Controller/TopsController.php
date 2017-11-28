<?php
App::uses('AppController', 'Controller');
/**
 * @property Top $Top
 * @author toshiko
 */

class TopsController extends AppController {
	public $name = 'Tops';
	public $components =array('Paginator');
	public $uses = array('User'); // model
	public function beforeFilter() {
		parent::beforeFilter();
		// 全員による閲覧を許可する
		$this->Auth->allow();
	}
	public function signUp() {
		$resultSignUp = false;
		if ($this->request->is('post')) {
			$resultSignUp = $this->User->addData($this->request->data);
			if ($resultSignUp) {
				$this->Auth->login();
				$this->redirect(array('controller' => 'topics', 'action' => 'search'));
			}
		}
	}
	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->redirect(array('controller' => 'topics', 'action' => 'search'));
			}
		}
	}
	public function logout() {
		$this->Auth->logout();
		$this->redirect(array('controller' => 'tops', 'action' => 'index'));
	}
	public function index() {
		// IndexPage
	}
	public function manager() {
		if($this->Auth->user('admin') != ADMIN_USER){
			// Adminユーザー以外はトピック一覧へ返す
			$this->redirect(array('controller' => 'topics', 'action' => 'search'));
		}
	}

}