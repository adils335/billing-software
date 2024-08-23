<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bill_item}}`.
 */
class m200919_162402_create_bill_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bill_item}}', [
            'id' => $this->primaryKey(),
            'sno' => $this->tinyInteger()->notNull(),
			'item' => $this->integer()->notNull(),
			'hsn_no' => $this->string(6)->notNull(),
			'gst' => $this->decimal(10,2)->notNull(),
			'unit' => $this->integer()->notNull(),
			'quantity' => $this->decimal(10,2)->notNull(),
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
            'idx-bill_item-company_id',
            'bill_item',
            'company_id'
        );

        // add foreign key for `company`
        $this->addForeignKey(
            'fk-bill_item-company_id',
            'bill_item',
            'company_id',
            'company',
            'id',
            'CASCADE'
        );
		
		// creates index for column `unit`
        $this->createIndex(
            'idx-bill_item-unit',
            'bill_item',
            'unit'
        );

        // add foreign key for `company`
        $this->addForeignKey(
            'fk-bill_item-unit',
            'bill_item',
            'unit',
            'uom',
            'id',
            'CASCADE'
        );
		
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bill_item}}');
    }
}
