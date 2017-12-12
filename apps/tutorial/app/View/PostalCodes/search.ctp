<?php /* @var $this View */ ?>

<div id="postal-code-search-form">
<!-- helper を使ってformタグを出力する -->
<?php echo $this->Form->create('PostalCode',
    array('type' => 'get',
        'url' => array(
            'controller' => 'postal_codes',
            'action'     => 'search'
        )
    )
);
?>
<?php $this->Form->inputDefaults(array('div' => false, 'label' => false, 'required' => false, 'required' => 'addRecord')); ?>

    <table>
        <tr>
            <td>都道府県!!</td>
            <td>
            <?php echo $this->Form->input('prefecture_id',
                array(
                    'type' => 'select',
                    'options' => $prefectureList
                )
            ); ?>
            </td>
        </tr>
    </table>

<?php echo $this->Form->submit('追加', array('div' => false, 'label' => false,'name' => 'search')); ?>
<?php echo $this->Form->end(); ?>

</div>

<div id="search-result-pagination-link-head" class="pagination-link">
    <?php echo $this->Paginator->first('<<'); ?>
    <?php echo $this->Paginator->prev('<'); ?>
    <?php echo $this->Paginator->numbers(); ?>
    <?php echo $this->Paginator->next('>'); ?>
    <?php echo $this->Paginator->last('>>'); ?>
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
        <?php foreach ($postalCodes as $p) { ?>
            <tr>
                <td><?php echo $this->Html->link('編集',
                    array('controller' => 'postal_codes', 'action' => 'edit', $p['PostalCode']['id']),
                    array('class' => 'link-button')
                ); ?></td>
                <td><?php echo $p['Prefecture']['prefecture_name']; ?></td>
                <td><?php echo $p['PostalCode']['postal_code']; ?></td>
                <td><?php echo $p['PostalCode']['city_name']; ?></td>
                <td><?php echo $p['PostalCode']['address']; ?></td>
                <td>削除</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<div id="search-result-pagination-link-head" class="pagination-link">
    <?php echo $this->Paginator->first('<<'); ?>
    <?php echo $this->Paginator->prev('<'); ?>
    <?php echo $this->Paginator->numbers(); ?>
    <?php echo $this->Paginator->next('>'); ?>
    <?php echo $this->Paginator->last('>>'); ?>
</div>
