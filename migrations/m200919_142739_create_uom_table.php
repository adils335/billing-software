<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%uom}}`.
 */
class m200919_142739_create_uom_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%uom}}', [
            'id' => $this->primaryKey(),
			'name' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%uom}}');
    }
}
