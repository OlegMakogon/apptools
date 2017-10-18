<?php
namespace common\models;

use common\components\AppTools;
use Yii;

/**
 * BranchDirectories model
 *
 * @property integer $id
 */
class VagrantBox
{
    protected $localRegistryName;
    protected $remoteRegistryName;
    protected $rootScripts = [];
    protected $apacheScripts = [];
    protected $tmpFolder;
    protected $updateFolder;
    protected $baseUrl;

    public function __construct()
    {
        $this->updateFolder = rtrim(Yii::$app->params['vagrantUpdateFolder'], '/') . '/';
        $this->baseUrl = rtrim(Yii::$app->params['v9UpdateBaseUrl'], '/') . '/';

        $this->tmpFolder = rtrim(Yii::$app->params['vagrantTmpFolder'], '/') . '/';
        if (!file_exists($this->tmpFolder)) {
            mkdir($this->tmpFolder);
        }

        $this->localRegistryName = $this->getRegistryName('local');
        $this->remoteRegistryName = $this->getRegistryName('remote');
    }

    public function version()
    {
        $version = '0.0.1';

        $registry = $this->getRegistry('local');
        if (count($registry)) {
            $lastUpdate = end($registry);
            if (!empty($lastUpdate['version'])) {
                $version = $lastUpdate['version'];
            }
        }
        return $version;
    }

    public function update()
    {
        $result = [
            'output' => '',
            'success' => true,
        ];

        $localRegistry = $this->getRegistry('local');
        $remoteRegistry = $this->getRegistry('remote');

        $diffRegistry = $this->getDiffRegistry($remoteRegistry, $localRegistry);

        foreach ($diffRegistry as $diff) {

            $result['output'] .= $this->loadUpdate($diff);
        }

        if (count($diffRegistry)) {

            $resUpdate = AppTools::runUpdate();
            $result['output'] .= $resUpdate['output'];

            $result['output'] .= $this->updateLocalRegistry();

            if (count($this->rootScripts)) {
                $result['output'] .= "<font color='red'><b>Run \"vagrant reload\" to update vagrant box.</b></font>";
            }

        } else {

            $result['output'] .= 'Nothing to update';
        }

        return $result;
    }

    protected function getRegistryName($type = 'local')
    {
        $fullRegistryName = '';

        if ($type == 'local') {

            $updateFolder = Yii::$app->params['vagrantUpdateFolder'];
            $registryName = Yii::$app->params['vagrantUpdateRegistry'];
            $fullRegistryName = rtrim($updateFolder, '/') . '/' . $registryName;

        } elseif ($type == 'remote') {

            $baseUrl = Yii::$app->params['v9UpdateBaseUrl'];
            $registryName = Yii::$app->params['vagrantUpdateRegistry'];
            $fullRegistryName = rtrim($baseUrl, '/') . '/' . $registryName;
        }

        return $fullRegistryName;
    }

    protected function getRegistry($type = 'local')
    {
        $registry = [];

        if ($type == 'local') {
            if (file_exists($this->localRegistryName)) {
                $registry = json_decode(file_get_contents($this->localRegistryName), true);
            }
        } elseif ($type == 'remote') {
            $registry = json_decode(@file_get_contents($this->remoteRegistryName), true);
        }

        return $registry;
    }

    protected function getDiffRegistry($remoteRegistry, $localRegistry)
    {
        $diff = [];
        if (is_array($remoteRegistry)) {

            foreach ($remoteRegistry as $key => $item) {

                if (!array_key_exists($key, $localRegistry)) {
                    $diff[$key] = $item;
                }
            }
        }

        return $diff;
    }

    protected function loadUpdate($regItem)
    {
        $output = '';

        if (is_array($regItem['updates'])) {

            foreach ($regItem['updates'] as $update) {

                if ($update['access'] == 'apache') {

                    if (!empty($update['file'])) {

                        $script = @file_get_contents($this->baseUrl . $update['file']);
                        @file_put_contents($this->tmpFolder . $update['file'], $script);
                        $output .= sprintf("Load %s to %s<br>", $this->baseUrl . $update['file'],
                            $this->tmpFolder . $update['file']);

                        $this->apacheScripts[] = $update['file'];
                    }

                } elseif ($update['access'] == 'root') {

                    $script = @file_get_contents($this->baseUrl . $update['file']);
                    @file_put_contents($this->updateFolder . $update['file'], $script);
                    $output .= sprintf("Load %s to %s<br>", $this->baseUrl . $update['file'],
                        $this->updateFolder . $update['file']);

                    $this->rootScripts[] = $update['file'];
                }
            }
        }

        return $output;
    }

    protected function updateLocalRegistry()
    {
        $output = '';

        $registry = @file_get_contents($this->remoteRegistryName);

        if (!empty($registry)) {

            if (@file_put_contents($this->localRegistryName, $registry)) {

                $output = "Update local registry<br>";
            }
        }

        return $output;
    }

}
