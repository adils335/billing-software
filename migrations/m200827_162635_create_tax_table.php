<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tax}}`.
 */
class m200827_162635_create_tax_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tax}}', [
            'id' => $this->primaryKey(),
			'name' => $this->string()->notNull(),
			'tax_type' => $this->tinyInteger()->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tax}}');
    }
}
