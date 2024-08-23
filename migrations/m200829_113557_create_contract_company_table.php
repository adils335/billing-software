<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%contract_company}}`.
 */
class m200829_113557_create_contract_company_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%contract_company}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%contract_company}}');
    }
}
