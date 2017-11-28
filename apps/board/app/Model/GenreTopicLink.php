<?php
/**
 * ジャンルとトピックのリンクドテーブル
 * トピックの追加・削除と同じく実行される
 * @author toshiko
 * @property Genre $Genre
 * @property Topic $Topic
 */
class GenreTopicLink extends AppModel {
	public $name = 'GenreTopicLink';
	public $useTable = 'genre_topic_links';
	public $validate = array(
		'genre_id' => array(
				'required' => array(
						'rule' => array('notEmpty'),
						'message' => 'ジャンルIDがありません。'
				)
		),
		'topic_id' => array(
				'required' => array(
						'rule' => array('notEmpty'),
						'message' => 'トピックIDがありません。'
				)
		)
	);
	/* GenreTopicLink belongs to Topic and Genre */
	public $belongsTo = array(
			'Topic' => array(
					'className' => 'Topic',
					'foreignKey' => 'topic_id'
			),
			'Genre' => array(
					'className' => 'Genre',
					'foreignKey' => 'genre_id'
			)
	);
	/**
	 * 指定のジャンルIDを含むトピックIDリストを検索
	 * @param array $genreIdList
	 * @return array $topicIdList
	 */
	public function getTopic($genreIdList) {
		$options = array(
				'conditions' =>  array('genre_id' => $genreIdList),
				'fields' => array('id','topic_id'),
				//'order' => array('topic_id' => 'ASC')
				'recursive' => -1
		);
		return $this->find('list', $options);
	}
	/**
	 * 新しく関係を追加
	 * @param array $data
	 * @return boolean True on success
	 */
	public function addData($data) {
		$this->create();
		return $this->save($data);
	}
	/**
	 * 指定のジャンルIDまたはトピックIDを含む関係を削除 同時には実行されない
	 * ジャンルを消した場合、ほかに紐付けが存在しないトピック・コメントも削除
	 * @param int $genreId
	 * @param int $topicId
	 * @return boolean True on success
	 */
	public function deleteData($genreId = null, $topicId = null) {
		if ($genreId) {
			$relateTopicList = $this->getTopic($genreId);
			foreach($relateTopicList as $list){
				if( $this->find('count',array('conditions'=>array('topic_id'=>$list['topic_id']))) == 1){
					$this->Topic->deleteData($list['topic_id']);
				}
			}
			return $this->deleteAll( array('genre_id'=>$genreId), false);
		} elseif($topicId){
			return $this->deleteAll( array('topic_id'=>$topicId), false);
		}
		return false;
	}
}