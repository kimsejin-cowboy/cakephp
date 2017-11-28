<?php

class DatabaseInformationPanel extends DebugPanel {

    public function beforeRender(Controller $controller) {
        if (!class_exists('ConnectionManager')) {
            return array();
        }

        $dbConfigs = ConnectionManager::sourceList();
        return $dbConfigs;
    }

}