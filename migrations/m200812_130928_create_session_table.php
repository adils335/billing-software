<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%session}}`.
 */
class m200812_130928_create_session_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%session}}', [
            'id' => $this->primaryKey(),
			'session'=>$this->string(7)->notNull(),
			'status'=>$this->tinyInteger()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%session}}');
    }
}
