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
$local_goverment_code = '';// 地方公共団体コード

// バリデーションエラー格納用変数
$validation_errors = array();
// $_POSTが空でなければ
if (!empty($_POST)) {
    // POSTされた値を取得
    if (isset($_POST['local_goverment_code'])) {
        $local_goverment_code = $_POST['local_goverment_code'];
    }

    // バリデーション
    if (!is_numeric($local_goverment_code) || strlen($local_goverment_code) != 6) {
        $validation_errors['local_goverment_code'] = '地方公共団体コードは半角数字６桁で入力してください';
    }
    
    
    // INSERT
    
    
    // リダイレクト
    
    
}

?>
<!DOCTYPE html>
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>PostalCodes</title>
    </head>
    <body>
        <!-- CRUD操作のうち、CUDはPOSTで行うのが基本です -->
        <!-- データ更新用のフォームのmethodは、POSTを使いましょう -->
        <form method="post" action="/raw_php/postal_codes/add.php" id="PostalCodeSearchForm" accept-charset="utf-8">
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
            </table>
            <input name="add" type="submit" value="追加"/>
        </form>
    </body>
</html>
