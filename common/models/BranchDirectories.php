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

        // get saved branches (directories)
        $userId = Yii::$app->user->id;
        $savedBranches = Branch::find()->select('directory')->where(['user_id' => $userId])->column();

        // exclude saved directories
        $dirs = [];
        foreach ($directories as $directory) {
            if (!in_array($directory,$savedBranches)) {
                $dirs[] = $directory;
            }
        }

        // save new directories
        $this->updateBranchTable($dirs);

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
        $result = false;
        // default approach
        $yiiTool = $dir . '/yii';
        if (file_exists($yiiTool)) {
            $result = $yiiTool;
        } else {
            // bitool approach
            $yiiTool = $dir . '/_protected/yii';
            if (file_exists($yiiTool)) {
                $result = $yiiTool;
            }
        }

        return $result;
    }
}
