<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%billing_company}}`.
 */
class m201018_160717_create_billing_company_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%billing_company}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%billing_company}}');
    }
}
