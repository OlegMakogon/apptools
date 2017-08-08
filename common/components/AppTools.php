<?php

namespace common\components;


use common\models\Branch;

class AppTools
{
    static public function updateSources(Branch $branch)
    {
        $result = [
            'output' => '',
            'success' => false,
        ];

        if (is_dir($branch->directory)) {
            $cmd = sprintf("cd %s && git pull --rebase",$branch->directory);
            $result['output'] = static::processCommand($cmd . " 2>&1");
            if (strpos($result['output'],'up-to-date') !== false) {
                $result['success'] = true;
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
            $cmd = sprintf("cd %s && %s migrate",$branch->directory,$branch->yii_path);
            $result['output'] = static::processCommand($cmd . " 2>&1");
            if (strpos($result['output'],'error') !== false) {
                $result['success'] = false;
            } elseif (strpos($result['output'],'No such file or directory') !== false) {
                $result['success'] = false;
            }
        }

        return $result;
    }

    static protected function processCommand($cmd)
    {
        $output = '';
        $proc = popen($cmd, 'r');
        while (!feof($proc))
        {
            $output .= fread($proc, 4096);
        }

        $output = str_replace("\n","<br>",$output);

        return $output;
    }
}