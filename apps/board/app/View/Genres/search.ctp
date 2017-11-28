<?php /* @var $this View */ ?>

<!-- Modal -->
<div id="GenreModal" class="modal-wrapper">
	<div class="modal">
		ジャンル新規追加・更新
		<?php echo $this->Form->create('GenrePost',
				array(
						'type'=>'post',
						'url'=>array('controller'=>'genres', 'action'=>'search'))); ?>
		<?php $this->Form->inputDefaults(array('div'=>false, 'label'=>false, 'required'=>false)); ?>
	    <table>
	        <tr>
	            <td><div class="text-label">ジャンル名</div></td>
	            <td>
	            <?php echo $this->Form->input('genre_name', array('type'=>'text')); ?>
	            </td>
	        </tr>
	    </table>
	    <?php echo $this->Form->hidden('id', array('value'=>0)); ?>
		<?php echo $this->Form->button('登録',
				array('type'=>'button', 'id'=>'GenreSaveButton', 'class'=>'link-label', 'name'=>'submit')); ?>
		<?php echo $this->Form->button('閉じる',
				array('type'=>'button', 'class'=>'closeModal link-label')); ?>
		<?php echo $this->Form->end(); ?>
	</div>
</div>

<!-- Title -->
<div id="PageNavigator">
<?php echo $this->Html->link('INDEX', array('controller'=>'tops', 'action'=>'index')); ?>
 > <?php echo $this->Html->link('Topic', array('controller'=>'topics', 'action'=>'search')); ?>
 > <?php echo $this->Html->link('Manager', array('controller'=>'tops', 'action'=>'manager')); ?>
 > Genre
 </div>
<h3>ジャンル一覧・検索</h3>

<!-- Form -->
<div id="GenreSearchForm">
	<?php echo $this->Form->create('GenreGet',
			array(
					'type'=>'get',
					'url'=>array('controller'=>'genres', 'action'=>'search'))); ?>
	<?php $this->Form->inputDefaults(array('div'=>false, 'label'=>false, 'required'=>true)); ?>
    <table>
        <tr>
            <td width="180"><div class="text-label">ジャンル名</div></td>
            <td>
            <?php echo $this->Form->input('genre_name',
            		array('type'=>'text', 'class'=>'small-form')); ?>
            </td>
        </tr>
    </table>
	<?php echo $this->Form->button('検索',
			array('type'=>'submit', 'class'=>'link-label', 'name'=>'search')); ?>
	<?php echo $this->Form->button('新規ジャンル追加',
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
<div id="GenreSearchResult">
    <table class="manage">
        <thead>
            <tr>
                <th width="200">詳細</th>
                <th width="400">ジャンル名</th>
                <th width="200">削除</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($genres as $g) { ?>
            <tr>
                <td><?php echo $this->Html->div('link-label openFilledModal', '詳細', array('name'=>$g['Genre']['id'])); ?></td>
                <td><?php echo $g['Genre']['genre_name']; ?></td>
                <td>
                <?php echo $this->Form->create('GenreDelete',
                		array(
                				'type'=>'post',
                				'url'=>array('controller'=>'genres', 'action'=>'deleteGenre'),
                				'onsubmit'=>'return confirmBox();')); ?>
				<?php echo $this->Form->hidden('id', array('value'=>$g['Genre']['id'])); ?>
				<?php echo $this->Form->button('削除',
						array('type'=>'submit', 'class'=>'link-label', 'div'=>false, 'label'=>false, 'name'=>'delete')); ?>
				<?php echo $this->Form->end(); ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<div id="PaginationLinkFoot" class="pagination-link">
    <?php echo $this->Paginator->first('<<'); ?>
    <?php echo $this->Paginator->prev('<'); ?>
    <?php echo $this->Paginator->numbers(); ?>
    <?php echo $this->Paginator->next('>'); ?>
    <?php echo $this->Paginator->last('>>'); ?>
</div>
<!--
<script type="text/javascript">
$(document).ready(function () {
	MODAL.init('Genre');
});
</script>
 -->

