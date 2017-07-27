<?php
namespace console\controllers;

use Yii;
use yii\base\Exception;
use yii\console\Controller;

/**
 * Class ReconnectController
 * @package console\controllers
 */
class TaskProcessorController extends Controller
{
    protected $runtimePath;

    /**
     * Main action
     *
     * @return int
     */
    public function actionIndex()
    {
        $this->runtimePath = Yii::getAlias('@runtime');
        $branchPath        = '/home/sites/branch2.lc';
        $branchName        = basename($branchPath);
        $logName           = $this->runtimePath . '/' . $branchName . '.log';

        $this->lockTask($branchName);

        $cmd  = "ping -c 10 127.0.0.1";
        $proc = popen($cmd, 'r');
        while (!feof($proc)) {
            file_put_contents($logName,fread($proc, 4096),FILE_APPEND);
        }

        $this->unlockTask($branchName);
    }

    protected function lockTask($branchName)
    {
        return touch($this->runtimePath . '/' . $branchName);
    }

    protected function unlockTask($branchName)
    {

    }
}
