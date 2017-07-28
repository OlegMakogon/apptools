<?php

namespace common\components;


class AppTools
{
    static public function updateSources($branchPath)
    {
        $result = [
            'output' => '',
            'success' => false,
        ];

        if (is_dir($branchPath)) {
            $cmd = sprintf("cd %s && git pull --rebase",$branchPath);
            $result['output'] = static::processCommand($cmd);
            if (strpos($result['output'],'up-to-date') !== false) {
                $result['success'] = true;
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

        return $output;
    }
}