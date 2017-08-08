<?php

use yii\db\Migration;

/**
 * Handles the creation of table `branch`.
 */
class m170808_082831_create_branch_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('branch', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'name' => $this->string(100),
            'description' => $this->string(255),
            'directory' => $this->string(255),
            'yii_path' => $this->string(255),
            'state' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('branch');
    }
}
