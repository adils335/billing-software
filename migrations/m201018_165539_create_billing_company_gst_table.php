<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%billing_company_gst}}`.
 */
class m201018_165539_create_billing_company_gst_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%billing_company_gst}}', [
            'id' => $this->primaryKey(),
			'company_id' => $this->integer()->notNull(),
			'state_id' => $this->integer()->notNull(),
			'gst_no' => $this->string(20)->notNull(),
        ]);
		
		// creates index for column `company_id`
        $this->createIndex(
            'idx-billing_company_gst-company_id',
            'billing_company_gst',
            'company_id'
        );

        // add foreign key for `company`
        $this->addForeignKey(
            'fk-billing_company_gst-company_id',
            'billing_company_gst',
            'company_id',
            'billing_company',
            'id',
            'CASCADE'
        );
		
		// creates index for column `state_id`
        $this->createIndex(
            'idx-billing_company_gst-state_id',
            'billing_company_gst',
            'state_id'
        );

        // add foreign key for `company`
        $this->addForeignKey(
            'fk-billing_company_gst-state_id',
            'billing_company_gst',
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
        $this->dropTable('{{%billing_company_gst}}');
    }
}
