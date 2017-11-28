<?php /* @var $this View */ ?>

<!-- Modal -->
<div id="TopicModal" class="modal-wrapper">
	<div class="modal">
		<?php echo $this->Form->create('TopicPost',
				array(
						'type'=>'post',
						'url'=>array(
								'controller'=>'topics',
								'action'=>'search'
								)
				)
			);
		?>
		<?php $this->Form->inputDefaults(array('div'=>false, 'label'=>false, 'required'=>false)); ?>
	    <table>

	        <tr>
	            <td width="160"><div class="text-label">ジャンル</div></td>
	            <td>
	            <?php echo $this->Form->input('genre_id',
	            		array('type'=>'select', 'multiple'=>'checkbox', 'options'=>$genreList, 'value'=>array_keys($genreList))); ?>
	            <div class="error-message errors-validation-genre_id"></div>
	            </td>
	        </tr>
	        <tr>
	            <td><div class="text-label">トピック名</div></td>
	            <td>
	            <?php echo $this->Form->input('topic_name', array('type'=>'text')); ?>
	            <div class="error-message errors-validation-topic_name"></div>
	            </td>
	        </tr>

	        <tr>
		        <td style="vertical-align:top; padding-top:10px;"><div class="text-label">最初のコメント</div></td>
		        <td>
		        <?php echo $this->Form->input('comment', array('type'=>'textarea')); ?>
		        <div class="error-message errors-validation-comment"></div>
		        </td>
	        </tr>
	    </table>
	    <?php echo $this->Form->hidden('parent_comment_id', array('value'=>0)); ?>
	    <?php echo $this->Form->hidden('id', array('value'=>0)); ?>
		<?php echo $this->Form->button('登録',
				array('type'=>'button', 'id'=>'TopicSaveButton', 'class'=>'link-label', 'name'=>'submit')); ?>
		<?php echo $this->Form->button('閉じる',
				array('type'=>'button', 'class'=>'closeModal link-label')); ?>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
<!-- Title -->

<div id="PageNavigator">
<?php echo $this->Html->link('INDEX', array('controller'=>'tops', 'action'=>'index')); ?>
 > Topic
 </div>
<h3>トピックス一覧・検索</h3>
<!-- Form -->
<div id="TopicSearchForm">
	<?php echo $this->Form->create('TopicGet',
			array(
					'type'=>'get',
					'url'=>array(
							'controller'=>'topics',
							'action'=>'search'
					)
			)
		);
	?>
	<?php $this->Form->inputDefaults(array('div'=>false, 'label'=>false, 'required'=>true)); ?>
    <table>
        <tr>
            <td><div class="text-label">ジャンル</div></td>
            <td>
            <?php echo $this->Form->input('genre_id',
            		array('type'=>'select', 'multiple'=>'checkbox', 'value'=>array_keys($checkedList), 'options'=>$genreList)); ?>
            </td>
        </tr>
        <tr>
            <td><div class="text-label">トピック名</div></td>
            <td>
            <?php echo $this->Form->input('topic_name',
            		array('type'=>'text', 'class'=>'small-form')); ?>
            </td>
        </tr>
    </table>
	<?php echo $this->Form->button('検索',
			array('type'=>'submit', 'class'=>'link-label', 'name'=>'search')); ?>
	<?php echo $this->Form->button('新規トピック追加',
			array('type'=>'button', 'class'=>'openModal link-label')); ?>
	<?php echo $this->Form->end(); ?>
</div>

<!-- Paginator -->
<div id="PaginationLinkHead" class="pagination-link">
    <?php echo $this->Paginator->first('<<'); ?>
    <?php echo $this->Paginator->prev('<'); ?>
    <?php echo $this->Paginator->numbers(); ?>
    <?php echo $this->Paginator->next('>'); ?>
    <?php echo $this->Paginator->last('>>'); ?>
</div>
<div id="TopicSearchResult">
    <table>
        <?php foreach ($topics as $t) { ?>
            <tr>
                <td>
                <?php echo $this->Html->link(
                		$t['Topic']['topic_name'].'('.$t['Topic']['comment_count'].')',
                		array('controller'=>'Comments', 'action'=>'index', $t['Topic']['id']),
                		array('class'=>'topic-bar', 'name'=>$t['Topic']['id'])); ?>
                </td>
                <td>
                <?php echo $this->Form->create('TopicDelete',
					    array(
					    'type'=>'post',
					    'url'=>array(
					            'controller'=>'Topics',
					            'action'    =>'deleteTopic'
					    ),
					    'onsubmit'=>'return confirmBox();'
					    )
					); ?>
				<?php echo $this->Form->hidden('id', array('value'=>$t['Topic']['id'])); ?>
				<?php echo $this->Form->submit('削除',
						array('div'=>false, 'label'=>false, 'class'=>'link-label', 'name'=>'delete')); ?>
				<?php echo $this->Form->end(); ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
<div id="PaginationLinkFoot" class="pagination-link">
    <?php echo $this->Paginator->first('<<'); ?>
    <?php echo $this->Paginator->prev('<'); ?>
    <?php echo $this->Paginator->numbers(); ?>
    <?php echo $this->Paginator->next('>'); ?>
    <?php echo $this->Paginator->last('>>'); ?>
</div>

<script type="text/javascript">
<!--
$(document).ready(function () {
	MODAL.init('Topic');
});
-->
</script>

