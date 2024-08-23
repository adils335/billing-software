<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%district}}`.
 */
class m200816_131832_create_district_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%district}}', [
            'id' => $this->primaryKey(),
			'district'=>$this->string(100)->notNull(),
			'state_id'=>$this->integer()->notNull()
        ]);
		
		
        // creates index for column `state_id`
        $this->createIndex(
            'idx-district-state_id',
            'district',
            'state_id'
        );

        // add foreign key for State `user`
        $this->addForeignKey(
            'fk-district-state_id',
            'district',
            'state_id',
            'state',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%district}}');
    }
}
