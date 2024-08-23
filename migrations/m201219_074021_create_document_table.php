<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%document}}`.
 */
class m201219_074021_create_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%document}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'file' => $this->string()->notNull(),
            'status' => $this->tinyInteger()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%document}}');
    }
}
