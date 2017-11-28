<?php
/**
 * トピックテーブル
 * 検索・追加・削除を実行する(編集しない)
 * @author toshiko
 * @property GenreTopicLink $GenreTopicLink
 * @property Comment $Comment
 */
class Topic extends AppModel {
    public $name = 'Topic';
    public $useTable = 'topics';
    /* validation settings */
    public $validate = array(
    	'topic_name' => array(
    			'required' => array(
    					'rule' => array('notEmpty'),
    					'message' => '内容を入力してください。'
    			),
    			'spaceOnly' => array(
    					'rule' => array('spaceOnly'),
    					'message' => '入力が空白のみです。'
    			),
    			'over' => array(
    					'rule' => array('maxLength',32),
    					'message' => '32文字以内で入力してください。'
    			)
    	)
    );
    /* Topic has many 'Comment' and 'Genre on Linked Table'*/
    public $hasMany = array(
    		'Comment' => array(
    				'className' => 'Comment',
    				'foreignKey' => 'topic_id'
    		),
    		'GenreTopicLink' => array(
    				'className' => 'GenreTopicLink',
    				'foreignKey' => 'topic_id'
    		)
    );

    /**
     * トピック名リストを取得
     * @param int $genreId
     * @param varchar $topicName
     * @return Ambigous <multitype:, NULL, mixed>
     */
    public function getTopicList($genreId = null, $topicName = null) {
    	$options = array(
    			'fields' => $group ? array('id', 'topic_name', 'genre_id') : array('id', 'topic_name'),
    			'order' => array('id' => 'ASC')
    	);
    	if (!empty($topicName)) {
    		$options['conditions']['topic_name LIKE '] = '%'.$topicName.'%';
    	}
    	if (!empty($genreId)) {
    		$this->virtualFields['genre_id'] = 'GenreTopicLinks.genre_id';
    		$options['recursive'] = 2;
    	} else {
    		$options['recursive'] = -1;
    	}
    	//ここでコメントの情報は使わないため処理を軽くするためにはずしておく
    	$this->unbindModel(array('hasMany' => 'Comment'));
    	return $this->find('list', $options);
    }
    /**
     * 指定トピックのコメント数を取得
     * @param int $topicId
     * @return int Topic.comment_count
     */
    public function getCommentCount($topicId = null) {
    	$options = array(
    			'conditions' => array('id' => $topicId),
    			'fields' => 'Topic.comment_count',
    			'recursive' => -1
    	);
    	$result = $this->find('first', $options);
    	return (int)$result['Topic']['comment_count'];
    }

    /**
     * トピックを検索
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
    				'fields' => array('Topic.id', 'Topic.topic_name')
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
     * トピックを追加・更新
     * トランザクション使用
     * @param array $allData
     * @return array $result
     */
    public function editData($allData=null) {
    	$ds = $this->getTransactionManager()->begin();
    	$result = array(
    			'success' => false,
    			'errors' => array()
    	);
    	try {
    		// Topic
    		$topicData['topic_name'] = $allData['topic_name'];
    		$this->set($topicData);
    	    if (!$this->exists()) { // 新規データ追加用 初期化
    			$this->create();
    		}
    		$validateTopicResult = $this->validates();
    		$saveTopicResult = $this->save($topicData);
    		$saveTopicId = $this->getLastInsertID();
    		// Comment
    		$commentData['topic_id'] = $saveTopicId;
    		$commentData['parent_id'] = 0;
    		$commentData['sequential_number'] = 1;
    		$commentData['comment'] = $allData['comment'];
    		$commentData['user_id'] = $allData['user_id'];
    		$this->Comment->set($commentData);
    		$validateCommentResult = $this->Comment->validates();
    		$saveCommentResult = $this->Comment->editData($commentData);
    		// GenreTopicLink
    		if(!$allData['genre_id']){
    			throw new Exception('nogenre');
    		}
    		foreach($allData['genre_id'] as $ad){ // genre_idがないとエラーになる
    			$linkData['topic_id'] = $saveTopicId;
    			$linkData['genre_id'] = $ad;
    			$this->GenreTopicLink->set($linkData);
    			$validateTopicResult = $this->GenreTopicLink->validates();
    			if (!$validateTopicResult) break;
    			$saveLinkResult = $this->GenreTopicLink->addData($linkData);
    			if (!$saveLinkResult) break;
    		}
    		// Validationとsaveは先に行われており、ここで例外処理を行うことによりすべてのエラーを送る
    		if ( !$validateTopicResult || !$validateCommentResult || !$validateTopicResult ) {
    			throw new Exception('データチェックに失敗しました。');
    		}
    		if ( !$saveTopicResult || !$saveCommentResult || !$saveLinkResult ) {
    			throw new Exception('データセーブに失敗しました。');
    		}
    		// 成功時
    		$result['success'] = true;
    		$this->getTransactionManager()->commit($ds);
    	} catch (Exception $e) {
    		$this->getTransactionManager()->rollback();
    		$result['errors']['reason'] = $e->getMessage();
    		if($result['errors']['reason'] == 'nogenre'){
    			$result['errors']['validation']['genre_id'] = 'ジャンルを選択してください';
    		}
    		foreach($this->validationErrors as $Key=>$value){
    			$result['errors']['validation'][$Key] = Util::arrayToString($value);
    		}
    		foreach($this->Comment->validationErrors as $Key=>$value){
    			$result['errors']['validation'][$Key] = Util::arrayToString($value);
    		}
    	}
    	return $result;
    }
    /**
     * 指定のトピックIDを削除
     * トピックに所属するコメント・リンクも全削除
     * トランザクション使用
     * @param int $topicId
     * @return boolean True on success
     */
    public function deleteData($topicId=null) {
    	$ds = $this->getTransactionManager()->begin();
    	try {
    		// GenreTopicLink削除
    		$resultLinkDelete = $this->GenreTopicLink->deleteAll(array('GenreTopicLink.topic_id'=>$topicId), false);
    		if (!$resultLinkDelete) {
    			throw new Exception('GenreTopicLinkのデータ削除に失敗しました。');
    		}
    		// Comment削除
    		$resultCommentDelete = $this->Comment->deleteAll(array('Comment.topic_id'=>$topicId), false);
    		if (!$resultCommentDelete) {
    			throw new Exception('Commentのデータ削除に失敗しました。');
    		}
    		$resultTopicDelete = $this->delete($topicId);
    		if (!$resultTopicDelete) {
    			throw new Exception('Topicのデータ削除に失敗しました。');
    		}
    		$this->getTransactionManager()->commit($ds);
    		// 成功時
    		return $resultTopicDelete;
    	} catch (Exception $e) {
    		$this->getTransactionManager()->rollback();
    		return false;
    	}
    }

}