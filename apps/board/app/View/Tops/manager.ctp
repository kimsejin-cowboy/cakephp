<?php /* @var $this View */ ?>
<div id="PageNavigator">
<?php echo $this->Html->link('INDEX', array('controller'=>'tops', 'action'=>'index')); ?>
 > <?php echo $this->Html->link('Topic', array('controller'=>'topics', 'action'=>'search')); ?>
 > Manager
 </div>
<div class="center">
<?php echo $this->Html->link('ユーザー管理',
	array('controller'=>'users', 'action'=>'search'),
	array('class'=>'link-label')
	); ?>
<?php echo $this->Html->link('ジャンル管理',
	array('controller'=>'Genres', 'action'=>'search'),
	array('class'=>'link-label')
	); ?>
</div>

