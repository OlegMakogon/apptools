<?php

namespace console\controllers;

use Yii;
use yii\base\Exception;
use yii\console\Controller;
use common\models\VagrantBox;

/**
 * Class VagrantBoxController
 * @package console\controllers
 */
class VagrantBoxController extends Controller
{
    public function actionIndex()
    {
    }

    /**
     * Update vagrant box
     *
     * @return int
     */
    public function actionUpdate()
    {
        $vagrantBox = new VagrantBox();
        $result = $vagrantBox->update(VagrantBox::CONSOLE_MODE);

        $result['output'] = str_replace('<br>', "\n", $result['output']);
        $result['output'] .= "\n";

        $this->stdout($result['output']);
    }
}
