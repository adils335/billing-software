<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bill_tax}}`.
 */
class m200919_163110_create_bill_tax_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bill_tax}}', [
            'id' => $this->primaryKey(),
			'tax_id' => $this->integer()->notNull(),
			'rate' => $this->decimal(10,2)->notNull(),
			'amount' => $this->decimal(10,2)->notNull(),
			'company_id' => $this->integer()->notNull(),
			'session' => $this->string(7)->notNull(),
			'created_at' => $this->integer()->notNull(),
			'created_by' => $this->integer()->notNull(),
			'updated_at' => $this->integer()->notNull(),
			'updated_by' => $this->integer()->notNull(),
        ]);
        
        // creates index for column `company_id`
        $this->createIndex(
            'idx-bill_tax-tax_id',
            'bill_tax',
            'tax_id'
        );

        // add foreign key for `company`
        $this->addForeignKey(
            'fk-bill_tax-tax_id',
            'bill_tax',
            'tax_id',
            'tax',
            'id',
            'CASCADE'
        );
		
		// creates index for column `company_id`
        $this->createIndex(
            'idx-bill_tax-company_id',
            'bill_tax',
            'company_id'
        );

        // add foreign key for `company`
        $this->addForeignKey(
            'fk-bill_tax-company_id',
            'bill_tax',
            'company_id',
            'company',
            'id',
            'CASCADE'
        );
		
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bill_tax}}');
    }
}
