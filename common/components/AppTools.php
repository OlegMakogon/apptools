<?php

namespace common\components;

use Yii;
use common\models\Branch;

class AppTools
{
    static public function updateSources(Branch $branch)
    {
        $result = [
            'output' => '',
            'success' => true,
        ];

        if (is_dir($branch->directory)) {
            $cmd = sprintf("cd %s && git pull --rebase", $branch->directory);
            $result['output'] = static::processCommand($cmd . " 2>&1");
            if (strpos($result['output'], 'error') !== false) {
                $result['success'] = false;
            }
        }

        return $result;
    }

    static public function applyMigrations(Branch $branch)
    {
        $result = [
            'output' => '',
            'success' => true,
        ];

        if (is_dir($branch->directory)) {
            $cmd = sprintf("cd %s && %s migrate <<< 'yes'", $branch->directory, $branch->yii_path);
            $result['output'] = static::processCommand($cmd . " 2>&1");
            if (strpos($result['output'], 'error') !== false) {
                $result['success'] = false;
            } elseif (strpos($result['output'], 'No such file or directory') !== false) {
                $result['success'] = false;
            } elseif (strpos($result['output'], 'command not found') !== false) {
                $result['success'] = false;
            }
        }

        return $result;
    }

    static public function fetchUnversioned(Branch $branch)
    {
        $result = [
            'output' => '',
            'success' => true,
        ];

        if (is_dir($branch->directory)) {
            $unversionedZip = Yii::$app->params['unversionedZip'];
            if (!empty($unversionedZip)) {
                $cmd = sprintf("cd %s && wget -q %s && unzip -o dms9noversion-my.zip && rm dms9noversion-my.zip",
                    $branch->directory, $unversionedZip);
                $result['output'] = static::processCommand($cmd . " 2>&1");
                if (strpos($result['output'], 'error') !== false) {
                    $result['success'] = false;
                }
            } else {
                $result['success'] = false;
            }
        }

        return $result;
    }

    static public function selfUpdate()
    {
        $result = [
            'output' => '',
            'success' => true,
        ];

        $appFolder = Yii::$app->params['appToolsFolder'];

        if (!empty($appFolder)) {
            $cmd = sprintf("cd %s && git pull && php ./yii migrate <<< 'yes'", $appFolder);
            $result['output'] = static::processCommand($cmd . " 2>&1");
            if (strpos($result['output'], 'error') !== false) {
                $result['success'] = false;
            }
        }

        return $result;
    }

    static public function runUpdate()
    {
        $result = [
            'output' => '',
            'success' => true,
        ];

        $cmd = "bash /vagrant/provision/updateboxa.sh";
        $result['output'] = static::processCommand($cmd . " 2>&1");
//        if (strpos($result['output'], 'error') !== false) {
//            $result['success'] = false;
//        }

        return $result;
    }

    static protected function processCommand($cmd)
    {
        $output = '';
        $proc = popen($cmd, 'r');
        while (!feof($proc)) {
            $output .= fread($proc, 4096);
        }
        $output = str_replace("\n", "<br>", $output);

        return $output;
    }
}