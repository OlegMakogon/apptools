<?php
namespace common\models;

/**
 * BranchDirectories model
 *
 * @property integer $id
 */
class BranchDirectories
{
    public function get()
    {
        // scan directory
        $somePath = '/home/sites';
        $directories = glob($somePath . '/*' , GLOB_ONLYDIR);

        // exclude not versioned directory
        foreach ($directories as $key=>$directory) {
            if (!is_dir($directory . '/.git')) {
                unset($directories[$key]);
            }
        }

        $dirs = [];
        foreach ($directories as $directory) {
            $dirs[] = [
                'name' => 'Name of branch',
                'path' => $directory,
                'description' => 'Description of branch',
            ];
        }

        return $dirs;
    }
}
