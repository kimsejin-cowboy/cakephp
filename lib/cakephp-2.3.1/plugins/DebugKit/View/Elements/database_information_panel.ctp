<h2><?php echo __d('debug_kit', 'Database Information'); ?></h2>
<?php foreach ($content as $dbName) { ?>
    <h4><?php echo __d('debug_kit', $dbName); ?></h4>
    <?php echo $this->Toolbar->makeNeatArray(ConnectionManager::getDataSource($dbName)->config); ?>
<?php } ?>