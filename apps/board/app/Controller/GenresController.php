<?php
App::uses('AppController', 'Controller');
/**
 * @property Genre $Genre
 * @author toshiko
 */

class GenresController extends AppController {
	public $name = 'Genres';
	public $components =array('Paginator');
	public $uses = array('Genre'); // model
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
		$genreName = $this->request->query('genre_name');
		if ($genreName) {
			$conditions['genre_name LIKE '] = '%'.$genreName.'%';
			$this->request->data['GenreGet']['genre_name'] = $genreName;
		}
		$this->Paginator->settings = array(
				'limit' => 10,
				'order' => array('Genre.id' => 'ASC'),
				'fields' => array('Genre.id', 'Genre.genre_name')
		);
		$genres = $this->Paginator->paginate($this->Genre, $conditions);
		// set 画面表示
		$this->set('genres', $genres);
	}

	/**
	 * 以下ジャンル変更機能 ページを持たない
	 */
	public function deleteGenre() {
		$this->request->onlyAllow('post');
		$deleteResult = $this->Genre->deleteData($this->request->data['GenreDelete']['id']);
		$this->redirect(array('controller' => 'genres', 'action' => 'search'));
	}
	public function saveGenreAjax(){
		$this->layout = 'ajax';
		$genreData = $this->request->data['GenrePost'];
		$json =  $this->Genre->editData($genreData);
		// set 画面表示
		$this->set('json', $json);
		$this->render('/Elements/json');
	}
	public function getGenreAjax($id=null){
		$this->layout = 'ajax';
		$json = $this->Genre->findData($id);
		// set 画面表示
		$this->set('json', $json);
		$this->render('/Elements/json');
	}
}