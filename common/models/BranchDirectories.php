<?php
namespace common\models;

use Yii;

/**
 * BranchDirectories model
 *
 * @property integer $id
 */
class BranchDirectories
{
    public function scan()
    {
        // scan directory
        $somePath = '/home/sites';
        $directories = glob($somePath . '/*', GLOB_ONLYDIR);

        // exclude not versioned directory
        foreach ($directories as $key => $directory) {
            if (!is_dir($directory . '/.git')) {
                unset($directories[$key]);
            }
        }

        $savedBranches = Branch::find()->all();
        if (count($savedBranches) === 0) {
            $this->updateBranchTable($directories);
        }

        return $directories;
    }

    protected function updateBranchTable($dirs)
    {
        if (is_array($dirs)) {
            foreach ($dirs as $dir) {
                $model = new Branch();
                $model->directory = $dir;
                $model->user_id = Yii::$app->user->id;
                $yiiTool = $this->findYiiTool($dir);
                $model->yii_path = ($yiiTool) ? $yiiTool : '';
                $model->save(false);
            }
        }
    }

    protected function findYiiTool($dir)
    {
        $yiiTool = $dir . '/yii';

        return (file_exists($yiiTool)) ? $dir . '/yii' : false;
    }
}
