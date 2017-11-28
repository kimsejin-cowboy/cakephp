<?php
App::uses('AppController', 'Controller');
/**
 * @property User $User
 * @author toshiko
 */

class UsersController extends AppController {

	public $name = 'Users';
	public $components =array('Paginator');
	public $uses = array('User'); //model
	public function beforeFilter() {
		parent::beforeFilter();
		// Adminユーザー以外はトピック一覧へ返す
		if($this->Auth->user('admin') != ADMIN_USER){
			$this->redirect(array('controller' => 'topics', 'action' => 'search'));
		}
	}

	/**
	 * メインページ
	 */
	public function search() {
		// Paginator表示
		$conditions = array();
		$userName = $this->request->query('user_name');
		if ($userName) {
			$conditions['user_name LIKE '] = '%'.$userName.'%';
			$this->request->data['UserGet']['user_name'] = $userName;
		}
		$this->Paginator->settings = array(
				'limit' => 10,
				'order' => array('User.id' => 'ASC'),
				'fields' => array('User.id', 'User.user_name', 'User.admin')
		);
		$users = $this->Paginator->paginate($this->User, $conditions);
		// set 画面表示
		$this->set('users', $users);
		$this->set('adminList', array(ADMIN_USER => 'はい', GENERAL_USER => 'いいえ'));
	}
	/**
	 * 以下ユーザー変更機能 ページを持たない
	 */
	public function deleteUser() {
		$this->request->onlyAllow('post');
		$deleteResult = $this->User->deleteData($this->request->data['UserDelete']['id']);
		$this->redirect(array('controller' => 'users', 'action' => 'search'));
	}
	public function saveUserAjax(){
		$this->layout = 'ajax';
		$userData = $this->request->data['UserPost'];
		$json = $this->User->editData($userData);
		// set 画面表示
		$this->set('json', $json);
		$this->render('/Elements/json');
	}
	public function getUserAjax($id=null){
		$this->layout = 'ajax';
		$json = $this->User->findData($id);
		// set 画面表示
		$this->set('json', $json);
		$this->render('/Elements/json');
	}
}