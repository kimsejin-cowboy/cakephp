<?php
/**
 * コメントテーブル
 * 検索・ツリー検索・追加・削除を実行する
 * ツリービヘイビアを使用する
 * @author toshiko
 * @property Topic $Topic
 * @property User $User
 * @property TransactionManager $TransactionManager
 */
class Comment extends AppModel {
    public $name = 'Comment';
    public $actsAs = array('Tree');
    public $useTable = 'comments';
    /* validation settings */
    public $validate = array(
    		'comment' => array(
    				'required' => array(
    						'rule' => array('notEmpty'),
    						'message' => '内容を入力してください。'
    				),
    				'spaceOnly' => array(
    						'rule' => array('spaceOnly'),
    						'message' => '入力が空白のみです。'
    				),
    				'over' => array(
    						'rule' => array('maxLength',1024),
    						'message' => '1024文字以内で入力してください。'
    				)
    		)
    );
    /* Comment belongs to Topic */
    public $belongsTo = array(
    		'Topic' => array(
    				'className' => 'Topic',
    				'foreignKey' => 'topic_id',
    				'counterCache' => true
    		)
    );

    /**
     * 指定トピックの最大コメント番号を取得
     * @param int $topicId
     * @return int MAX(Topic.comment_count)
     */
    public function getMaxSequentialNumber($topicId=null) {
    	$options = array(
    			'conditions' => array('topic_id' => $topicId),
    			'fields' => 'MAX(sequential_number) as max_num',
    			'recursive' => -1
    	);
    	$result = $this->find('first', $options);
    	return (int)$result['0']['max_num'];
    }
    /**
     * 指定コメントのコメント番号を取得
     * @param int $commentId
     * @return int Topic.sequential_number
     */
    public function getSequentialNumber($commentId=null) {
    	$options = array(
    			'conditions' => array('id' => $commentId),
    			'fields' => 'sequential_number',
    			'recursive' => -1
    	);
    	$result = $this->find('first', $options);
    	return (int)$result['Comment']['sequential_number'];
    }

    /**
     * コメント一覧を取得
     * @param int $topicId
     * @param varchar $topicName
     * @return Ambigous <multitype:, NULL, mixed>
     */
    public function getCommentList($topicId=null, $commentKeyword=null) {
		$options = array();
		if ($topicId) {
			$options['conditions']['topic_id'] = $topicId;
		}
		if ($commentKeyword) {
			$options['conditions']['comment LIKE '] = '%'.$commentKeyword.'%';
		}
		$options['order'] = array('Comment.created' => 'ASC');
		$options['fields'] = array('id', 'parent_id', 'sequential_number', 'user_id', 'comment', 'created');
		$options['recursive'] = -1;
		//$this->bindModel(array('belongsTo' => array('User' => array('conditions' =>array('User.id' => 'Comment.user_id'), 'foreignKey' => 'user_id', 'fields' => 'user_name'))),false);
		$comments = $this->find('all', $options);
    	return $comments;
    }
    /**
     * コメントツリーを取得
     * @param int $id
     * @return array $result
     */
    public function getTree($id=null) {
    	$result = array(
    			'success' => false,
    			'results' => array(),
    			'errors' => array()
    	);
    	try {
    		if(!$id){
    			throw new Exception('IDがありません。');
    		}
    		// UserName表示用処理
    		$this->bindModel(array(
    				'belongsTo' => array(
    						'User' => array(
    								'conditions' =>array('User.id' => 'Comment.user_id'),
    								'foreignKey' => 'user_id'
    						)
    				)),false);
    		$userList = $this->User->getUserList();
    		// ツリー取得・データ成型
    		$tree = $this->children($id, false, array('id', 'parent_id', 'sequential_number', 'user_id', 'comment', 'created'));
    		foreach($tree as $key=>$value){
    			$tree[$key]['Comment']['user_name'] = (string)$userList[$value['Comment']['user_id']];
    			unset($tree[$key]['Comment']['user_id']);
    		}
    		$result['results'] = $tree;
    		if(!$result['results']){
    			throw new Exception('結果が空です。');
    		}
    		// 成功時
    		$result['success'] = true;
    	} catch (Exception $e) {
    		$result['errors'] = $e;
    	}
    	return $result;
    }
    /**
     * コメントを検索
     * @param int $id
     * @return array $result
     */
    public function findData($id=null){
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
    				'fields' => array('Comment.id', 'Comment.topic_id')
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
     * コメントを追加・更新
     * トランザクション使用
     * @param array $data
     * @return array $result
     */
    public function editData($data=null) {
    	$ds = $this->getTransactionManager()->begin();
    	$result = array(
    			'success' => false,
    			'results' => array(),
    			'errors' => array()
    	);
    	try {
    		// Commentへの保存用データ成型
    		$commentData = $data;
    		$commentData['sequential_number'] = 1;
    		if($commentData['topic_id']){
    			$commentData['sequential_number'] += $this->getMaxSequentialNumber($commentData['topic_id']);
    		}
    		$this->set($commentData);
    		// バリデーション
    		if (!$this->validates()) {
    			throw new Exception('Commentのデータチェックに失敗しました。');
    		}
    		// アンカー追加
    		if($commentData['parent_id']){
    			$parentSequentialNumber = $this->getSequentialNumber($commentData['parent_id']);
    			$commentData['comment'] = '>>'.$parentSequentialNumber.' '.$commentData['comment'];
    		}
    		// 保存判定開始
    		if (!$this->exists()) {
    			$this->create();
    		}
    		// トピック modified更新
    		$topicData = array('id'=>$data['topic_id'],'modified'=>date('Y-m-d H:i:s'));
    		$this->Topic->save($topicData,false);
    		$saveCommentResult = $this->save($commentData);
    		if (!$saveCommentResult) {
    			throw new Exception('Commentのデータセーブに失敗しました。');
    		}
    		// 成功時
    		$result['results']['topic_id'] = $commentData['topic_id'];
    		$result['success'] = true;
    		$this->getTransactionManager()->commit($ds);
    	} catch (Exception $e) {
    		$this->getTransactionManager()->rollback();
    		$result['errors']['reason'] = $e->getMessage();
    		foreach($this->validationErrors as $Key=>$value){
    			$result['errors']['validation'][$Key] = Util::arrayToString($value);
    		}
    	}
    	return $result;
    }
    /**
     * 指定のコメントを削除
     * トランザクション使用
     * @param int $id
     * @return array $result
     */
    public function deleteData($id=null) {
    	$ds = $this->getTransactionManager()->begin();
    	$result = array(
    			'success' => false,
    			'errors' => array()
    	);
    	try {
    		if ($id==1) {
    			throw new Exception('最初のコメントです');
    		}
    		$deleteCommentResult = $this->delete($id);
    		if (!$deleteCommentResult) {
    			throw new Exception('Commentのデータ削除に失敗しました。');
    		}
    		$result['success'] = true;
    		$this->getTransactionManager()->commit($ds);
    		return $result;
    	} catch (Exception $e) {
    		$this->getTransactionManager()->rollback();
    		$result['errors']['reason'] = $e->getMessage();
    		return $result;
    	}
    }

}