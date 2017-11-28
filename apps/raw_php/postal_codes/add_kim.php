<?php
/**
 * 新規追加画面
 */
/* TODO 6/1-2 検索画面と同じデザインで表示されるようにしてください */
/* TODO 6/1-2 地方公共団体以外のフィールドの入力フィールドを入力する要素を追加してください */
/* ただし、id、old_postal_code、created、modifiedは追加しなくて良いものとします */
/* prefecture_idはprefecturesテーブルから都道府県のリストを取得し、セレクトボックスで表示してください */
/* TODO 6/1-2 データがPOSTされた場合のみ、テーブルpostal_codesに新しい郵便番号データを追加（INSERT）する機能を実装してください */
/* TODO 6/1-2 INSERTする前にバリデーションを実装してください */
/***************************************************/
/* バリデーションルール                                */
/* local_goverment_code                半角数字６けた */
/* postal_codes                        半角数字７けた */
/* city_name       マルチバイト２５６文字以内、空文字はダメ */
/* address         マルチバイト２５６文字以内、空文字はダメ */
/****************************************************/
/* 残りのフィールド */
/* old_postal_codes バリデーションを通過した場合のみ、 postal_codesの先頭５文字を切り取ったものを取得してください */
/* バリデーションを通過できなかった場合は、その理由を例にならって表示してください */
/* created, modified 現在日時を取得してください */
/* 6/1-2 TODO INSERTが成功した場合、INSERTしたデータのidを取得し、/raw_php/postal_codes/edit.php?id=取得したidにリダイレクトしてください */
/* 以下実装例 */

$conn = mysql_connect('localhost', 'tutorial', 'tutorial');

// 文字コードセット
mysql_set_charset('utf8', $conn);
// database 選択
mysql_select_db('tutorial', $conn);
// 都道府県リスト格納用変数
$prefecture_list = array();
// 都道府県リスト取得用sql
$sql = 'SELECT `id`, `prefecture_name` FROM `prefectures` ORDER BY `id` ASC';
// query 実行
$result = mysql_query($sql, $conn);
while ($row = mysql_fetch_assoc($result)) {
	$prefecture_list[] = $row;
}

// $_GETの中身
// var_dump($_GET);
// query string 取得
$search_results = array();
// 郵便番号検索用query 構築
$sql  = 'SELECT `prefectures`.`prefecture_name`, ';
$sql .= '`postal_codes`.`id`, `postal_codes`.`postal_code`, `postal_codes`.`city_name`, ';
$sql .= '`postal_codes`.`address` ';
$sql .= 'FROM `postal_codes` LEFT JOIN `prefectures` ';
$sql .= 'ON `postal_codes`.`prefecture_id` = `prefectures`.`id` ';

// $_GETの中身
// var_dump($_GET);
// query string 取得
$prefecture_id = null;// 都道府県ID
if (isset($_GET['prefecture_id'])) {
	$prefecture_id = $_GET['prefecture_id'];
}
$postal_code = null;// 郵便番号
if (isset($_GET['postal_code'])) {
	$postal_code = $_GET['postal_code'];
	$postal_code = htmlspecialchars($postal_code, ENT_QUOTES, 'UTF-8');
	var_dump($postal_code);
	echo $postal_code;
}

$city_name = null;// 郵便番号
if (isset($_GET['city_name'])) {
	$city_name= $_GET['city_name'];
}

// where句 構築
$where = '';
if (!empty($prefecture_id)) {
	if (!empty($where)) {
		$where .= ' AND';
	}
	$where .= ' `prefecture_id` = ' . $prefecture_id;
}
if (!empty($postal_code)) {
	if (!empty($where)) {
		$where .= ' AND';
	}
	$where .= ' `postal_code` LIKE "%' . $postal_code . '%"';
}

if (!empty($city_name)) {
	if (!empty($where)) {
		$where .= ' AND';
	}
	$where .= ' `city_name` LIKE "%' . $city_name . '%"';
}

if (!empty($where)) {
	$sql .= 'WHERE ' . $where . ' ';
}
$sql .= 'ORDER BY `postal_codes`.`id` ASC ';
$sql .= 'LIMIT 10';



// 検索結果を取得
$result = mysql_query($sql, $conn);
while ($row = mysql_fetch_assoc($result)) {
	$search_results[] = $row;
}



//var
$prefecture_id='';
$old_postal_codes= '';
$local_goverment_code = '';// 地方公共団体コード
$postal_code= '';// 郵便番号
$city_name= '';// 地区長村
$address= '';// 町域
$now = date('Y/m/d H:i:s');

// バリデーションエラー格納用変数
$validation_errors = array();
// $_POSTが空でなければ
if (!empty($_POST)) {
	// POSTされた値を取得

	if (isset($_POST['prefecture_id'])) {
		$prefecture_id= $_POST['prefecture_id'];
	}

	if (isset($_POST['local_goverment_code'])) {
		$local_goverment_code = $_POST['local_goverment_code'];
		$local_goverment_code = mysql_real_escape_string($local_goverment_code);
	}

	// バリデーション
	if (!is_numeric($local_goverment_code) || strlen($local_goverment_code) != 6) {
		$validation_errors['local_goverment_code'] = '地方公共団体コードは半角数字６桁で入力してください';
	}


	if (isset($_POST['postal_code'])) {
		$postal_code= $_POST['postal_code'];
		$postal_code= mysql_real_escape_string($postal_code);
		$old_postal_codes= substr($postal_code, 0, 5);
	}

	// バリデーション
	if (!is_numeric($postal_code) || strlen($postal_code) != 7) {
		$validation_errors['postal_code'] = '郵便番号は半角数字7桁で入力してください';
	}


	if (isset($_POST['city_name'])) {
		$city_name= $_POST['city_name'];
		$city_name= mysql_real_escape_string($city_name);
	}

	// バリデーション
	//if (!is_numeric($city_name) || strlen($city_name) != 6) {
	//    $validation_errors['city_name'] = 'マルチバイト２５６文字以内に入力してください';
		//}
		//if (!preg_match("/^[０-９ぁ-んァ-ヶー一-龠]+$/u",$city_name)|| mb_strlen($city_name) > 20) {
		$cnt = mb_strlen($city_name,"utf-8");
		if (!preg_match("/^[０-９ぁ-んァ-ヶー一-龠]+$/u",$city_name)|| $cnt > 256) {

			$validation_errors['city_name'] = 'マルチバイト２５６文字以内に入力してください';
		}

		if (isset($_POST['address'])) {
			$address= $_POST['address'];
			$address= mysql_real_escape_string($address);

		}

		$cnt = mb_strlen($address,"utf-8");
		if (!preg_match("/^[０-９ぁ-んァ-ヶー一-龠]+$/u",$address)|| $cnt > 256) {
			$validation_errors['address'] = 'マルチバイト２５６文字以内に入力してください';
		}

		if (!empty($validation_errors)){

		}else{
			$conn = mysql_connect('localhost', 'tutorial', 'tutorial');
			if (!$conn) {
				die('接続失敗です。'.mysql_error());
			}
			// 文字コードセット

			//$sql = 'DELETE FROM `tutorial`.`postal_codes` WHERE `postal_codes`.`id` =8';
			$sql = "INSERT INTO `postal_codes` (`prefecture_id`, `local_goverment_code`, `postal_code`, `city_name`, `address`, `old_postal_code`, `created`, `modified`)";
			$sql .= "VALUES ('$prefecture_id','$local_goverment_code',' $postal_code','$city_name','$address','$old_postal_codes','$now','$now')";
			$result = mysql_query($sql, $conn);
			$last_id = mysql_insert_id();
			//var_dump($last_id);
			header('Location: /raw_php/postal_codes/edit.php?id='.$last_id);


			//$sql = "INSERT INTO `tutorial`.`postal_codes` (`local_goverment_code` ,`postal_code` ,`prefecture_id` ,`city_name` ,`address`)VALUES ('123123123', '8112108', '40', '糟屋郡宇美町', 'ゆりが丘')";


		}



}

?>
<!DOCTYPE html>
<html>
     <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>PostalCodes</title>
        <link rel="stylesheet" type="text/css" href="/raw_php/css/cake.generic.css" />
        <link rel="stylesheet" type="text/css" href="/raw_php/css/jquery-ui.css" />
        <link rel="stylesheet" type="text/css" href="/raw_php/css/jquery-ui.structure.css" />
        <link rel="stylesheet" type="text/css" href="/raw_php/css/jquery-ui.theme.css" />
        <link rel="stylesheet" type="text/css" href="/raw_php/css/tutorial.css" />
        <script type="text/javascript" src="/raw_php/js/jquery.js"></script>
        <script type="text/javascript" src="/raw_php/js/jquery.ui.js"></script>
        <script type="text/javascript" src="/raw_php/js/tutorial.js"></script>
    </head>
    <body>
    <div id="container">
            <div id="header">Turorial</div>
            <div id="content">
                <div id="postal-code-search-form">
        <!-- CRUD操作のうち、CUDはPOSTで行うのが基本です -->
        <!-- データ更新用のフォームのmethodは、POSTを使いましょう -->
        <form method="post" action="/raw_php/postal_codes/add_kim.php" id="PostalCodeSearchForm" accept-charset="utf-8">
            <table>
                <tr>
                    <td>地方公共団体コード</td>
                    <td>
                        <input type="text" name="local_goverment_code" value="<?php echo $local_goverment_code; ?>">
                        <?php /* バリデーションエラー表示例 */ ?>
                        <?php if (!empty($validation_errors['local_goverment_code'])) { ?>
                        <div class="error-message"><?php echo $validation_errors['local_goverment_code']; ?></div>
                        <?php } ?>
                    </td>
                </tr>
                 <tr>
                 <td>都道府県</td>
                                <td>
                                    <select name="prefecture_id" id="PostalCodePrefectureId">
                                    <?php for ($i = 0; $i < count($prefecture_list); $i++) { ?>
                                        <option value="<?php echo $prefecture_list[$i]['id']; ?>"
                                        <?php if ($prefecture_id == $prefecture_list[$i]['id']) { echo 'selected="selected"';} ?>>
                                        <?php echo $prefecture_list[$i]['prefecture_name']; ?></option>
                                    <?php } ?>
                                    </select>
                                </td>
                                </tr>
                                <tr>

                                <td>郵便番号</td>
                                <td>
                                    <input type="text" name="postal_code" value="<?php echo $postal_code; ?>" />
                                      <?php if (!empty($validation_errors['postal_code'])) { ?>
                        <div class="error-message"><?php echo $validation_errors['postal_code']; ?></div>
                        <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <td>地区長村</td>
                                <td>
                                    <input type="text" name="city_name" value="<?php echo $city_name; ?>" />
                                      <?php if (!empty($validation_errors['city_name'])) { ?>
                        <div class="error-message"><?php echo $validation_errors['city_name']; ?></div>
                        <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <td>町域</td>
                                <td>
                                    <input type="text" name="address" value="<?php echo $address; ?>" />
                                      <?php if (!empty($validation_errors['address'])) { ?>
                        <div class="error-message"><?php echo $validation_errors['address']; ?></div>
                        <?php } ?>
                                </td>
                            </tr>


            </table>
            <input name="add" type="submit" value="追加"/>
        </form>
        </div>

        <div id="postal-code-search-result">

                    <table>
                        <thead>
                            <tr>
                                <th>編集</th>
                                <th>都道府県名</th>
                                <th>郵便番号</th>
                                <th>地区長村</th>
                                <th>町域名</th>
                                <th>削除</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php for ($i = 0; $i < count($search_results); $i++) { ?>
                            <?php $r = $search_results[$i]; ?>
                            <tr>
                                <td><a href="/raw_php/postal_code/edit.php?id=<?php echo $r['id']; ?>" class="link-button">編集</a></td>
                                <td><?php echo $r['prefecture_name']; ?></td>
                                <td><?php echo $r['postal_code']; ?></td>
                                <td><?php echo $r['city_name']; ?></td>
                                <td><?php echo $r['address']; ?></td>
                                <td>
                                <form method="post" action="/raw_php/postal_codes/delete.php" id="delete" accept-charset="utf-8">
                                 <input type="hidden" name="id"  value="<?php echo $r['id']; ?>">
                                <input  name="delete" type="submit" value="削除"/>
                                 </form>

                                </td>

                        <?php } ?>
                          </tr>

                        </tbody>
                    </table>

                </div>

    </div>
    </div>
    </body>


</html>