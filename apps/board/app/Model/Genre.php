<?php
/**
 * ジャンルテーブル
 * 検索・追加・更新・削除を実行する
 * @author toshiko
 * @property Topic $Topic
 * @property GenreTopicLink $GenreTopicLink
 */
class Genre extends AppModel {
    public $name = 'Genre';
    public $useTable = 'genres';
    /* validation settings */
    public $validate = array(
    		'genre_name' => array(
    				'required' => array(
    						'rule' => array('notEmpty'),
    						'message' => '内容を入力してください。'
    				),
    				'spaceOnly' => array(
    						'rule' => array('spaceOnly'),
    						'message' => '入力が空白のみです。'
    				),
    				'over' => array(
    						'rule' => array('maxLength',20),
    						'message' => '20文字以内で入力してください。'
    				)
    		)
    );
    private $Topic = null;
    protected function getTopic() {
    	if (is_null($this->Topic)) {
    		$this->Topic = ClassRegistry::init('Topic');
    	}
    	return $this->Topic;
    }
    /* Genre has many Topic on Linked Table */
    public $hasMany = array(
    		'GenreTopicLink' => array(
    				'className' => 'GenreTopicLink',
    				'foreignKey' => 'genre_id'
    		)
    );

    /**
     * ジャンル名リストを取得
     * @param string $genreName
     * @return Ambigous <multitype:, NULL, mixed>
     */
    public function getGenreList($genreId = null,$genreName = null) {
        $options = array(
            'fields' => array('id', 'genre_name'),
            'order'  => array('id' => 'ASC')
        );
        if (!empty($genreId)) {
        	$options['conditions']['id'] = $genreId;
        }
        if (!empty($genreName)) {
        	$options['conditions']['genre_name LIKE '] = '%'.$genreName.'%';
        }
        return $this->find('list', $options);
    }
    /**
	 * ジャンルを検索
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
					'fields' => array('Genre.id', 'Genre.genre_name')
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
     * ジャンルを追加・更新
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
				throw new Exception('Genreのデータチェックに失敗しました。');
			}
			// 保存判定開始
			if (!$this->exists()) {
				$this->create();
			}
			$saveUserResult = $this->save($data);
			if (!$saveUserResult) {
				throw new Exception('Genreのデータセーブに失敗しました。');
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
     * ジャンルIDを削除
     * リンクテーブルのデータ、指定ジャンルのみに属するトピックも削除
     * トランザクション使用
     * @param int $id
     * @return boolean True on success
     */
    public function deleteData($genreId) {
    	$ds = $this->getTransactionManager()->begin();
    	try {
    		// Topic削除
    		$options = array(
    				'conditions' => array('GenreTopicLink.genre_id' => $genreId),
    				'fields' => 'topic_id',
    				'recursive' => -1
    		);
    		$topicList = $this->GenreTopicLink->find('list', $options);
    		debug($topicList);
    		if($topicList){ // topicがなければスキップ
	    		foreach($topicList as $key=>$value){ // $id => $topic_id
					$resultCount = $this->GenreTopicLink->find('count', array('conditions' => array('topic_id' => $value)) );
					if($resultCount == 1){
						if (!$this->getTopic()->deleteData($value)) {
							throw new Exception('Topicのデータ削除に失敗しました。');
						}
					}
	    		}
	    		// GenreTopicLink削除
	    		$resultLinkDelete = $this->GenreTopicLink->deleteAll(array('genre_id'=>$genreId), false);
	    		if (!$resultLinkDelete) {
	    			throw new Exception('GenreTopicLinkのデータ削除に失敗しました。');
	    		}
    		}
    		// Genre削除
    		$resultGenreDelete = $this->delete($genreId);
    		if (!$resultGenreDelete) {
    			throw new Exception('Genreのデータ削除に失敗しました。');
    		}
    		$this->getTransactionManager()->commit($ds);
    		// 成功時
    		return $resultGenreDelete;
    	} catch (Exception $e) {
    		$this->getTransactionManager()->rollback();
    		return false;
    	}
    }

}