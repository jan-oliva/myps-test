<?php

function adminer_object() {
    // required to run any plugin
    include_once dirname(__FILE__) . '/plugins/plugin.php';

    // autoloader
    foreach (glob(dirname(__FILE__) . '/plugins/*.php') as $filename) {
        include_once $filename;
    }

    $plugins = array(
        new AdminerAutocomplete,
        new AdminerLoginServers(array('localhost')),
        new AdminerDumpXmlDataSet,
        new AdminerDumpFlatXmlDataSet,
        new AdminerTablesFilter,
    );

    /* It is possible to combine customization and plugins:
    class AdminerCustomization extends AdminerPlugin {
    }
    return new AdminerCustomization($plugins);
    */

    return new AdminerPlugin($plugins);
}

// include original Adminer or Adminer Editor
include dirname(__FILE__) . '/adminer.php';
