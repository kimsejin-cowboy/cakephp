<?php
/**
 * 検索画面
 */
/* TODO 6/1 市区町村の入力フォームをテキストボックスで追加し、検索条件に市区町村での部分一致を追加してください */
/* TODO 6/3 各レコードに削除ボタンを追加し、データを削除する機能を追加してください */
/* 以下実装例 */
// DB 接続
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
// point 変数の中身を確認する方法例
// var_dump($prefecture_list);

// 郵便番号検索結果取得用変数
$search_results = array();
// 郵便番号検索用query 構築
$sql  = 'SELECT `prefectures`.`prefecture_name`, ';
$sql .= '`postal_codes`.`postal_code`, `postal_codes`.`city_name`, ';
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
                    <!-- point CRUD操作のうち、Rはgetで行うのが基本です -->
                    <!-- point 検索フォームのmethodはgetを指定しましょう -->
                    <form method="get" action="/raw_php/postal_codes/search.php" id="PostalCodeSearchForm" accept-charset="utf-8">
                        <table>
                            <tr>
                                <td>都道府県</td>
                                <td>
                                    <select name="prefecture_id" id="PostalCodePrefectureId">
                                    <?php for ($i = 0; $i < count($prefecture_list); $i++) { ?>
                                        <option value="<?php echo $prefecture_list[$i]['id']; ?>"<?php if ($prefecture_id == $prefecture_list[$i]['id']) { echo 'selected="selected"';} ?>><?php echo $prefecture_list[$i]['prefecture_name']; ?></option>
                                    <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>郵便番号</td>
                                <td>
                                    <input type="text" name="postal_code" value="<?php echo $postal_code; ?>" />
                                </td>
                            </tr>
                        </table>
                        <input  name="search" type="submit" value="検索"/>
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
                                <td><a href="/row_php/postal_codes/edit.php?id=<?php echo $r['id']; ?>" class="link-button">編集</a></td>
                                <td><?php echo $r['prefecture_name']; ?></td>
                                <td><?php echo $r['postal_code']; ?></td>
                                <td><?php echo $r['city_name']; ?></td>
                                <td><?php echo $r['address']; ?></td>
                                <td>削除</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>