<?php
App::uses('AppController', 'Controller');
/**
 * @property GenreTopicLink $GenreTopicLink
 * @property Genre $Genre
 * @property Topic $Topic
 * @property Comment $Comment
 * @property TransactionManager $TransactionManager
 * @author toshiko
 */

class CommentsController extends AppController {

	public $name = 'Comments';
	public $components =array('Paginator');
	public $uses = array('Genre', 'GenreTopicLink', 'Topic', 'Comment', 'User', 'TransactionManager'); // model
	public function beforeFilter() {
		parent::beforeFilter();
	}

	/**
	 * メインページ
	 */
	public function index($topicId=null) {
		$comments = $this->Comment->getCommentList($topicId, $this->request->query('Comment_Keyword'));
		$this->request->data['CommentGet']['Comment_Keyword'] = $this->request->query('Comment_Keyword');
		$topicInfo = $this->Topic->findData($topicId);
		$this->set('comments', $comments);
		$this->set('topicInfo', $topicInfo['results']['Topic']);
		$userList = $this->User->getUserList();
		$this->set('userList', $userList);
	}

	/**
	 * 以下ユーザー変更機能 ページを持たない
	 */
	public function deleteComment() {
		$this->request->onlyAllow('post');
		if($this->Auth->user('admin') != ADMIN_USER){
			$this->redirect(array('controller' => 'Comments', 'action' => 'index', $this->request->data['CommentDelete']['topic_id']));
		}
		$deleteResult = $this->Comment->deleteData($this->request->data['CommentDelete']['id']);
		$this->redirect(array('controller' => 'Comments', 'action' => 'index', $this->request->data['CommentDelete']['topic_id']));

	}
	public function saveCommentAjax(){
		// コメント保存機能 編集を行わないためリザルトにはTopic_idのみで戻る
		$this->layout = 'ajax';
		$commentData = $this->request->data['CommentPost'];
		$commentData['user_id'] = $this->Auth->user('id');
		$json = $this->Comment->editData($commentData);
		// set 画面表示
		$this->set('json', $json);
		$this->render('/Elements/json');
	}
	public function getCommentAjax($commentId=null){
		// コメント補完機能 編集を行わないためIDなしで戻る
		$this->layout = 'ajax';
		$json =  $this->Comment->findData($commentId);
		// set 画面表示
		$this->set('json', $json);
		$this->render('/Elements/json');
	}
	public function getCommentTreeAjax($commentId=null){
		// コメントツリー作成機能
		$this->layout = 'ajax';
		$json =  $this->Comment->getTree($commentId);
		// set 画面表示
		$this->set('json', $json);
		$this->render('/Elements/json');
	}
}