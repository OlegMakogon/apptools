<?php

error_reporting(0);
ini_set('display_errors', 'off');

if (!empty($argv[1])) {
    $crmBaseFolder = $argv[1];
    $branchName = basename($crmBaseFolder);
} else {
    echo "You need use call php fixcrm.php <crmbasefolder>";
}

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}
global $installing;
global $sugar_config, $sugar_version, $dictionary, $current_user, $db, $log, $beanList;
$installing = true;
chdir(realpath($crmBaseFolder . '/www/crmupgrade/'));
if (session_id() == "") {
    session_start();
}
$_SERVER['HTTP_HOST'] = $branchName;
require_once('bootstrap.php');
require_once('wx/config/config.php');
require_once('wx/mysql/connect.php');
require_once 'include/entryPoint.php';
$db = DBManagerFactory::getInstance('mysqli', $sugar_config);
$sugar_config['logger_level'] = 'debug';
$log = LoggerManager::getLogger();
require_once 'jssource/minify_utils.php';
require_once 'jssource/jsmin.php';
$_REQUEST['root_directory'] = $from = getcwd();
// Rebuild JS Compressed Files
$_REQUEST['js_admin_repair'] = 'replace';
reverseScripts("$from/jssource/src_files", "$from");
// Rebuild JS Grouping Files
$_REQUEST['js_admin_repair'] = 'concat';
//concatenate mode, call the files that will concatenate javascript group files
$_REQUEST['js_rebuild_concat'] = 'rebuild';
require_once('jssource/minify.php');