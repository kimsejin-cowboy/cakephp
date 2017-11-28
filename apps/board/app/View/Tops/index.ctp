<?php /* @var $this View */ ?>
<div class="center">
<?php echo $this->Html->link('ログイン画面へ',
	array('controller'=>'tops', 'action'=>'login'),
	array('class'=>'link-label')
	); ?>
<?php echo $this->Html->link('新規登録へ',
	array('controller'=>'tops', 'action'=>'signUp'),
	array('class'=>'link-label')
	); ?>
</div>