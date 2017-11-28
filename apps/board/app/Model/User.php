<?php
/**
 * 使用者テーブル
 * 検索・追加・更新・削除を実行する
 * @author toshiko
 * @property Topic $Topic
 * @property Comment $Comment
 */
App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel {
	public $name = 'User';
	public $useTable = 'users';
	/* validation settings */
	public $validate = array(
			'user_name' => array(
					'length' => array(
							'rule' => array('between',8,16),
							'message' => 'IDは8～16文字で入力してください。'
					),
					'language' => array(
							'rule' => array('alphaNumeric'),
							'message' => 'IDは英数字のみで入力してください。'
					),
					'unique' => array(
							'rule' => array('isUnique'),
							'message' => '既にIDが存在します。'
					)
			),
			'user_password' => array(
					'length' => array(
							'rule' => array('between',4,16),
							'message' => 'パスワードは4～16文字で入力してください。'
					),
					'language' => array(
							'rule' => array('alphaNumeric'),
							'message' => 'パスワードは英数字のみで入力してください。'
					)
			)
	);
	private $Comment = null;
	protected function getComment() {
		if (is_null($this->Comment)) {
			$this->Comment = ClassRegistry::init('Comment');
		}
		return $this->Comment;
	}
	private $Topic = null;
	protected function getTopic() {
		if (is_null($this->Topic)) {
			$this->Topic = ClassRegistry::init('Topic');
		}
		return $this->Topic;
	}

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['user_password'])) {
			$this->data[$this->alias]['user_password'] = AuthComponent::password(
					$this->data[$this->alias]['user_password']
					);
		}
		return true;
	}
	/**
	 * ユーザー名リストを取得
	 * 権限は見せない
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function getUserList() {
		$options = array(
				'fields' => array('id', 'user_name'),
				'order'  => array('id' => 'ASC')
		);
		return $this->find('list', $options);
	}
	/**
	 * ユーザーを検索
	 * @param int $id
	 * @return array $result
	 */
	public function findData($id = null){
		$result = array(
				'success' => false,
				'results' => array(),
				'errors' => array()
		);
		try {
			if(!$id){
				throw new Exception('IDがありません。');
			}
			$options = array(
					'conditions' => array('id' => $id),
					'fields' => array('User.id', 'User.user_name', 'User.admin')
			);
			$result['results'] = $this->find('first', $options);
			if(!$result['results']){
				throw new Exception('結果が空です。');
			}
			$result['success'] = true;
		} catch (Exception $e) {
			$result['errors'] = $e;
		}
		return $result;
	}
	/**
	 * サインアップ用
	 * @param array $data
	 * @return boolean
	 */
	public function addData($data = null) {
		$this->create();
		if($this->save($data,true)) {
			return true;
		}
		return false;
	}
	/**
	 * ユーザーを追加・更新
	 * @param array $data
	 * @return array $result
	 */
	public function editData($data=null) {
		$result = array(
				'success' => false,
				'errors' => array()
		);
		try {
			$this->set($data);
			// バリデーション
			if (!$this->validates()) {
				throw new Exception('Userのデータチェックに失敗しました。');
			}
			// 保存判定開始
			if (!$this->exists()) {
				$this->create();
			}
			$saveUserResult = $this->save($data);
			if (!$saveUserResult) {
				throw new Exception('Userのデータセーブに失敗しました。');
			}
			// 成功時
			$result['success'] = true;
		} catch (Exception $e) {
			$result['errors']['reason'] = $e->getMessage();
			foreach($this->validationErrors as $Key=>$value){
				$result['errors']['validation'][$Key] = Util::arrayToString($value);
			}
		}
		return $result;
	}
	/**
	 * ユーザーを削除
	 * 作成したトピックス・コメントも削除
	 * @param int $id
	 * @return boolean
	 */
	public function deleteData($userId = null) {
		$ds = $this->getTransactionManager()->begin();
		try {
			// 削除リスト作成・sequential_numberが1のコメントのトピックは削除する
			$options = array(
					'conditions' => array('Comment.user_id' => $userId, 'Comment.sequential_number' => '1'),
					'fields' => 'Comment.topic_id'
			);
			$deleteTopicList = $this->getComment()->find('list', $options);
			debug($deleteTopicList);
			// Comment削除
			$resultCommentDelete = $this->getComment()->deleteAll(array('user_id'=>$userId), false);
			$this->getComment()->updateCounterCache();
			if (!$resultCommentDelete) {
				throw new Exception('Commentのデータ削除に失敗しました。');
			}
			foreach($deleteTopicList as $dtl){
				$resultTopicDelete = $this->getTopic()->deleteData($dtl);
				if (!$resultTopicDelete) {
					throw new Exception('Topicのデータ削除に失敗しました。');
				}
			}
			// User削除
			$resultUserDelete = $this->delete($userId);
			if (!$resultUserDelete) {
				throw new Exception('Userのデータ削除に失敗しました。');
			}
			$this->getTransactionManager()->commit($ds);
			// 成功時
			return $resultUserDelete;
		} catch (Exception $e) {
			$this->getTransactionManager()->rollback();
			return false;
		}
	}
}