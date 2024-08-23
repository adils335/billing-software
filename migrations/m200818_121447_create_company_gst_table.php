<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%company_gst}}`.
 */
class m200818_121447_create_company_gst_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%company_gst}}', [
            'id' => $this->primaryKey(),
			'company_id' => $this->integer()->notNull(),
			'state_id' => $this->integer()->notNull(),
			'gst_no' => $this->string(20)->notNull(),
			'created_at' => $this->integer()->notNull(),
			'created_by' => $this->integer()->notNull(),
			'updated_at' => $this->integer()->notNull(),
			'updated_by' => $this->integer()->notNull(),
        ]);
		
		// creates index for column `company_id`
        $this->createIndex(
            'idx-company_gst-company_id',
            'company_gst',
            'company_id'
        );

        // add foreign key for `company`
        $this->addForeignKey(
            'fk-company_gst-company_id',
            'company_gst',
            'company_id',
            'company',
            'id',
            'CASCADE'
        );
		
		// creates index for column `state_id`
        $this->createIndex(
            'idx-company_gst-state_id',
            'company_gst',
            'state_id'
        );

        // add foreign key for `company`
        $this->addForeignKey(
            'fk-company_gst-state_id',
            'company_gst',
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
        $this->dropTable('{{%company_gst}}');
    }
}
