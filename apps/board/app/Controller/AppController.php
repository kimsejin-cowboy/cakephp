<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $components = array('Session',
    		'DebugKit.Toolbar',
    		'Auth' => array(
    				// ログインなし
    				'loginAction' => array(
							'controller' => 'tops',
							'action' => 'index'
    				),
    				// ログイン成功後
					'loginRedirect' => array(
							'controller' => 'topics',
							'action' => 'search'
					),
    				// ログアウト後
					'logoutRedirect' => array(
							'controller' => 'tops',
							'action' => 'index'
							//'home'
					),
					'authenticate' => array(
							'Form' => array(
								'fields' => array(
										'username' => 'user_name',
										'password' => 'user_password'
								)
							)
					)
    		)
    );

//     public function isAuthorized() {
//     	// 登録ユーザーのみに以下のアクションを許可する
//     	if (isset($user['role']) && $this->Auth->user('admin') == '1') {
//     		return true;
//     	}
//     	return false;
//     }
    public function beforeFilter() {
    	parent::beforeFilter();
    	$this->set('username', $this->Auth->user('user_name'));
    }
    public function beforeRender() {
    	parent::beforeRender();
    	$this->setParameters();
    }
    public function destroy() {
    	$this->Session->destroy();
    	$this->redirect(array('controller' => 'tops'));
    }
    protected function setParameters() {
    }
}
