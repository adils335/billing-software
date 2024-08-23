<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%state}}`.
 */
class m200816_125813_create_state_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%state}}', [
            'id' => $this->primaryKey(),
			'state'=>$this->string(100)->notNull(),
			'state_tin'=>$this->tinyInteger()->notNull(),
			'state_code'=>$this->string(3)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%state}}');
    }
}
