<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%company_type}}`.
 */
class m200816_134910_create_company_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%company_type}}', [
            'id' => $this->primaryKey(),
			'type'=>$this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%company_type}}');
    }
}
