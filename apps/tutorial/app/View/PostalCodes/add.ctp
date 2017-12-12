
<?php /* @var $this View */ ?>

<div id="postal-code-search-form">
	<!-- helper を使ってformタグを出力する -->
	<?php
//HTMLヘルパーのlinkメソッドを使用

?>
<?php
echo $this->Form->create ( 'PostalCode' ); // create('モデル名',array(その他))
echo $this->Form->input ( 'postal_code' ); // input('カラム名',array(その他))
echo $this->Form->input ( 'city_name' );
echo $this->Form->input ( 'address' );
echo $this->Form->end ( '入力' );
echo $message,$title_for_layout;
?>
</div>



