<?php
/**
 * 郵便番号データベースを検索した集したり削除したり
 * @author kazunari
 * @property PostalCode $PostalCode
 * @property Prefecture $Prefecture
 * @property Region     $Region
 */
class PostalCodesController extends AppController {

    public $name = 'PostalCodes';
    // components
    public $components = array('Paginator');
    // models
    public $uses = array('Region', 'Prefecture', 'PostalCode');

    public $helpers = array('Html', 'Form');
    /**
     * すべてのアクションメソッドが呼ばれる前に実行される
     * @see AppController::beforeFilter()
     */
    public function beforeFilter() {
        parent::beforeFilter();
    }

    /**
     * 検索画面
     * この画面にはすでに都道府県で検索するロジックが実装されています
     * 6/6-7TODO
     * 下記の検索項目を追加してください
     * ・地方（region）
     * 　可能ならば地方を選択すると都道府県が絞り込まれるようにしてください
     * ・郵便番号の部分一致検索
     * ・市区町村の部分一致検索
     * 検索画面
     */
    public function search() {

        $conditions = array();
        // TODO 検索条件を構築
        // example
        if ($this->request->query('prefecture_id')) {
            // 検索条件に追加
            $conditions['prefecture_id'] = $this->request->query('prefecture_id');
            // 検索フォームに反映
            $this->request->data['PostalCode']['prefecture_id'] = $this->request->query('prefecture_id');
        }
        // ページネータの設定
        $this->Paginator->settings = array(
            'limit' => 10,
            'order' => array('PostalCode.id' => 'ASC'),
            'fields' => array(
                'Prefecture.prefecture_name', 'PostalCode.id',
                'PostalCode.postal_code', 'PostalCode.city_name',
                'PostalCode.address'
            )
        );
        // 検索
        $postalCodes = $this->Paginator->paginate($this->PostalCode, $conditions);

        // 検索結果をviewにセット
        $this->set('postalCodes', $postalCodes);

        // 検索用パラメータ
        $prefectureList = $this->Prefecture->getPrefectureList(null, true);
        $this->set('prefectureList', $prefectureList);
    }

    /**
     * 6/6-7 TODO
     * 新規レコード追加
     * ・新しい郵便番号データを追加する画面を作ってください
     * ・モデルにはバリデーションを必ず実装しましょう
     * ・バリデーションを通過できなかった場合、エラーが発生した旨表示し、各フィールドにエラー内容を表示してください
     * ・成功したら検索画面にリダイレクトしてください
     */
    public function add() {

    	//$conditions = array();
    	if (!empty($this->data)){
    		$this->PostalCode->save($this->data);
    		$this->Session->setFlash('入力完了');
    		$this->redirect(array('action'=>'add'));
    	}

    	$this->set('prefectures',$this->Prefecture->find('list',array(
    			'fields' => array( 'prefecture_name')
    	)));


    	// ページネータの設定

    	// 検索




    }

    /**
     * 6/6-7 TODO
     * 既存データ編集
     * ・既存のデータを編集する画面を作ってください
     * ・モデルにはバリデーションを必ず実装しましょう
     * ・バリデーションを通過できなかった場合、エラーが発生した旨表示し、各フィールドにエラー内容を表示してください
     * ・成功したら検索画面にリダイレクトしてください
     * @param string $postalCodeId
     */
    public function edit($postalCodeId = null) {

    }

    /**
     * 6/6-7 TODO
     * データを削除する機能装して下さい
     * 必ずPOSTメソッドで削除対象のデータのidを取得してください
     */
    public function delete() {

    }
}