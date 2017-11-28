<?php /* @var $this View */ ?>

<!-- Modal -->
<div id="CommentModal" class="modal-wrapper">
	<div class="modal">
		<?php echo $this->Form->create('CommentPost',
				array(
						'type'=>'post',
						'url'=>array('controller'=>'Comments', 'action'=>'index'),
						'id'=>'CommentPostSearchForm',
		)); ?>
		<?php $this->Form->inputDefaults(array('div'=>false, 'label'=>false, 'required'=>false)); ?>
       <div class="text-label">コメント</div>
        <?php echo $this->Form->input('comment',
        		array('type'=>'textarea', 'placeholder'=>"コメントを入力してください")); ?>
        <div class="error-message errors-validation-comment"></div>
        <?php echo $this->Form->hidden('parent_id', array('value'=>0)); ?>
	    <?php echo $this->Form->hidden('topic_id', array('value'=>$topicInfo['id'])); ?>
		<?php echo $this->Form->button('投稿',
				array('type'=>'button', 'id'=>'CommentSaveButton', 'class'=>'link-label', 'name'=>'submit')); ?>
		<?php echo $this->Form->button('閉じる',
				array('type'=>'button', 'class'=>'closeModal link-label')); ?>
		<?php echo $this->Form->end(); ?>
	</div>
</div>

<!-- Tree -->
<nav id="CommentTreeWindow" class="window-wrapper">
	<div id="CommentTree" class="window">
	<h3 id="CommentTreeTitle">Reply to Comment　≫</h3>
	<section id="CommentTreeSwap"><!-- ツリー表示 --></section>
    <?php echo $this->Form->create('CommentTree',
    		array(
    				'type'=>'get',
    				'url'=>array('controller'=>'Comments', 'action'=>'index'))); ?>
	<?php echo $this->Form->button('閉じる', array('type'=>'button', 'class'=>'closeCommentTreeView link-label float-right')); ?>
	<?php echo $this->Form->end(); ?>
	</div>
</nav>

<!-- Title -->
<div id="PageNavigator">
<?php echo $this->Html->link('INDEX', array('controller'=>'tops', 'action'=>'index')); ?>
 > <?php echo $this->Html->link('Topic', array('controller'=>'topics', 'action'=>'search')); ?>
 > <?php echo $topicInfo['topic_name']; ?>
 </div>
<h3>コメント一覧・検索</h3>

<!-- Form -->
<div id="CommentSearchForm">
	<?php echo $this->Form->create('CommentGet',
			array(
					'type'=>'get',
					'url'=>array('controller'=>'Comments', 'action'=>'index', $topicInfo['id']))); ?>
	<?php $this->Form->inputDefaults(array('div'=>false, 'label'=>false, 'required'=>true)); ?>
    <table >
        <tr>
            <td width="180"><div class="text-label">キーワード</div></td>
            <td>
            <?php echo $this->Form->input('Comment_Keyword',
            		array('type'=>'text', 'class'=>'small-form')); ?>
            </td>
        </tr>
    </table>
	<?php echo $this->Form->button('検索',
			array('type'=>'submit', 'class'=>'link-label', 'name'=>'search')); ?>
	<?php echo $this->Form->button('新規コメント追加',
			array('type'=>'button', 'class'=>'openModal link-label')); ?>
	<?php echo $this->Form->end(); ?>
</div>

<!-- Comment -->
<div id="CommentSearchResult">
        <?php foreach ($comments as $c) { ?>
		<div class="comment-info clearfix">
			<?php echo 'No:'.$c['Comment']['sequential_number'].'　　　投稿日時:'.$c['Comment']['created'].'　　　投稿者:'.$userList[ $c['Comment']['user_id'] ]; ?>
			<!-- 削除 -->
            <div class="float-right">
			<?php echo $this->Form->create('CommentDelete',
					array(
							'type'=>'post',
							'url'=>array('controller'=>'Comments', 'action'=>'deleteComment'),
							'onsubmit'=>'return confirmBox();')
				);
			?>
			<?php echo $this->Form->hidden('topic_id', array('value'=>$topicInfo['id'])); ?>
			<?php echo $this->Form->hidden('id', array('value'=>$c['Comment']['id'])); ?>
			<?php echo $this->Form->button('削除',
					array('type'=>'submit', 'class'=>'link-label', 'name'=>'delete')); ?>
			<?php echo $this->Form->end(); ?>
			</div>
			<!-- ツリー表示 -->
            <div class="float-right">
            <?php echo $this->Form->create('CommentTree',
            		array(
					    'type'=>'get',
					    'url'=>array('controller'=>'Comments', 'action'=>'index'))); ?>
			<?php echo $this->Form->button('ツリー表示',
					array('type'=>'button', 'class'=>'openCommentTreeView link-label', 'num'=>$c['Comment']['id'], 'name'=>$c['Comment']['sequential_number'])); ?>
			<?php echo $this->Form->end(); ?>
			</div>
			<!-- 返信 -->
            <div class="float-right">
            <?php echo $this->Form->create('CommentReply',
            		array(
            				'type'=>'post',
            				'url'=>array('controller'=>'Comments', 'action'=>'index'))
				);
			?>
			<?php echo $this->Form->button('返信',
					array('type'=>'button', 'class'=>'openReplyModal link-label', 'div'=>false, 'name'=>$c['Comment']['id'])); ?>
			<?php echo $this->Form->end(); ?>
			</div>
		</div>
		<pre><?php echo h($c['Comment']['comment']); ?></pre>
        <?php } ?>
</div>
<script type="text/javascript">
<!--
$(document).ready(function () {
	MODAL.init('Comment');
});
-->
</script>

