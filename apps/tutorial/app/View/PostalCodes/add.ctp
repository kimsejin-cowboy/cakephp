
<?php /* @var $this View */ ?>

<div id="postal-code-search-form">
	<!-- helper を使ってformタグを出力する -->
	<?php
//HTMLヘルパーのlinkメソッドを使用

?>
<?php
echo $this->Form->create ( 'PostalCode',array( 'novalidate' => true) ); // create('モデル名',array(その他))
echo $this->Form->input ( 'local_goverment_code' ); // input('カラム名',array(その他))
echo $this->Form->input ( 'postal_code' );
//echo $this->Form->input ( 'old_postal_code');
//echo $this->Form->input ( 'old_postal_code',array( 'type' => 'hidden'));
echo $this->Form->input ( 'prefecture_id' );
echo $this->Form->input ( 'city_name' );
echo $this->Form->input ( 'address' );

echo $this->Form->end ( '入力' );

?>
</div>



