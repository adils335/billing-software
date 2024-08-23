<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%contract_company_gst}}`.
 */
class m200829_123452_create_contract_company_gst_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%contract_company_gst}}', [
            'id' => $this->primaryKey(),
			'company_id' => $this->integer()->notNull(),
			'state_id' => $this->integer()->notNull(),
			'gst_no' => $this->string(20)->notNull(),
        ]);
		
		// creates index for column `company_id`
        $this->createIndex(
            'idx-contract_company_gst-company_id',
            'contract_company_gst',
            'company_id'
        );

        // add foreign key for `company`
        $this->addForeignKey(
            'fk-contract_company_gst-company_id',
            'contract_company_gst',
            'company_id',
            'contract_company',
            'id',
            'CASCADE'
        );
		
		// creates index for column `state_id`
        $this->createIndex(
            'idx-contract_company_gst-state_id',
            'contract_company_gst',
            'state_id'
        );

        // add foreign key for `company`
        $this->addForeignKey(
            'fk-contract_company_gst-state_id',
            'contract_company_gst',
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
        $this->dropTable('{{%contract_company_gst}}');
    }
}
