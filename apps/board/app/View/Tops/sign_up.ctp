<div class="center">
<?php echo $this->Form->create('User'); ?>
<h3>新規ユーザー登録画面</h3>
<?php $this->Form->inputDefaults(array('div'=>false, 'label'=>false, 'required'=>false)); ?>
    <table>
        <tr>
            <td><div class="text-label">ログインID</div></td>
            <td>
            <?php echo $this->Form->input('user_name', array('type'=>'text')); ?>
            </td>
        </tr>
        <tr>
            <td><div class="text-label">パスワード</div></td>
            <td>
            <?php echo $this->Form->input('user_password', array('type'=>'password')); ?>
            </td>
        </tr>
    </table>
<?php echo $this->Form->hidden('admin', array('value'=>'0')); ?>
<?php echo $this->Form->submit('登録',
		array('id'=>'userSaveButton', 'class'=>'link-label', 'div'=>false, 'label'=>false, 'name'=>'submit')); ?>
<?php echo $this->Html->link('ログイン画面へ',
		array('controller'=>'tops', 'action'=>'login'),
		array('class'=>'link-label')); ?>
<?php echo $this->Form->end(); ?>
</div>