<?php
App::uses('AppController', 'Controller');
/**
 * @author toshiko
 * @property GenreTopicLink $GenreTopicLink
 * @property Genre $Genre
 * @property Topic $Topic
 * @property Comment $Comment
 * @property TransactionManager $TransactionManager
 */

class TopicsController extends AppController {

	public $name = 'Topics';
	public $components =array('Paginator');
	public $uses = array('Genre', 'GenreTopicLink', 'Topic', 'Comment', 'TransactionManager'); // model
	public function beforeFilter() {
		parent::beforeFilter();
	}

	/**
	 * メインページ
	 */
	public function search() {
		// Paginator表示
		$genreList = $this->Genre->getGenreList(null);
		$checkedList = $genreList;
		$conditions = array();
		if($this->request->query('genre_id')) {
			$topicIdList = $this->GenreTopicLink->getTopic($this->request->query('genre_id'));
			$this->set('topicIdList', $topicIdList);
			$conditions['id'] = $topicIdList;
			$checkedList = $this->Genre->getGenreList( $this->request->query('genre_id'), null );
		}
		if($this->request->query('topic_name')) {
			$conditions['topic_name LIKE '] = '%'.$this->request->query('topic_name').'%';
			$this->request->data['TopicGet']['topic_name'] = $this->request->query('topic_name');
		}
		$this->Paginator->settings = array(
				'limit' => 10,
				'order' => array('Topic.modified' => 'DESC'),
				'fields' => array('Topic.id', 'Topic.topic_name', 'Topic.comment_count')
		);
		$this->Topic->unbindModel(array('hasMany' => array('Comment')));
		$topics = $this->Paginator->paginate($this->Topic, $conditions);
		// set 画面表示
		$this->set('topics', $topics);
		$this->set('genreList', $genreList);
		$this->set('checkedList', $checkedList);
	}

	/**
	 * 以下ユーザー変更機能 ページを持たない
	 */
	public function deleteTopic() {
		$this->request->onlyAllow('post');
		if($this->Auth->user('admin') != ADMIN_USER){
			$this->redirect(array('controller' => 'topics', 'action' => 'search'));
		}
		$deleteResult = $this->Topic->deleteData($this->request->data['TopicDelete']['id']);
		$this->redirect(array('controller' => 'topics', 'action' => 'search'));
	}
	public function saveTopicAjax(){
		// トピック保存機能 編集を行わないためリザルトなし
		$this->layout = 'ajax';
		$allData = $this->request->data['TopicPost'];
		$allData['user_id'] = $this->Auth->user('id');
		$json = $this->Topic->editData($allData);
		// set 画面表示
		$this->set('json', $json);
		$this->render('/Elements/json');
	}
	public function getTopicAjax($topicId=null){
		// トピック補完機能 編集を行わないためIDなしで戻る
		$this->layout = 'ajax';
		$json =  $this->Topic->findData($topicId);
		// set 画面表示
		$this->set('json', $json);
		$this->render('/Elements/json');
	}

}