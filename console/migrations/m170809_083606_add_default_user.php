<?php

use yii\db\Migration;
use common\models\User;

class m170809_083606_add_default_user extends Migration
{
    public function up()
    {
        $user = new User();
        $user->username = 'admin';
        $user->setPassword('admin');
        $user->email = 'admin@apptools.xloo.com';
        $user->save();
    }

    public function down()
    {
        $this->delete('user', ['username' => 'admin']);
    }
}
