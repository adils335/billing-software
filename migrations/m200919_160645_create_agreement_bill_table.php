<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%agreement_bill}}`.
 */
class m200919_160645_create_agreement_bill_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%agreement_bill}}', [
            'id' => $this->primaryKey(),
            'invoice_no' => $this->integer()->notNull(),
            'invoice_date' => $this->date()->notNull(),
            'order_no' => $this->string()->null(),
            'work_name' => $this->string()->null(),
            'estimate_no' => $this->string()->null(),
            'section_name' => $this->string()->null(),
            'start_date' => $this->date()->null(),
            'complete_date' => $this->date()->null(),
            'circle_name' => $this->string()->null(),
            'base_amount' => $this->decimal(10,2)->notNull(),
            'schedule' => $this->integer()->notNull(),
            'schedule_rate' => $this->decimal(10,2)->notNull(),
            'schedule_amount' => $this->decimal(10,2)->notNull(),
            'taxable_amount' => $this->decimal(10,2)->notNull(),
            'tax_amount' => $this->decimal(10,2)->notNull(),
            'after_tax_total' => $this->decimal(10,2)->defaultValue(0),
            'penality_amount' => $this->decimal(10,2)->defaultValue(0),
            'stamp' => $this->tinyInteger()->defaultValue(1),
            'penality_tax' => $this->decimal(10,2)->defaultValue(0),
            'penality_after_tax' => $this->decimal(10,2)->defaultValue(0),
            'payable_amount' => $this->decimal(10,2)->notNull(),
            'deduction_amount' => $this->decimal(10,2)->notNull(),
            'pay_amount' => $this->decimal(10,2)->notNull(),
            'tax_note' => $this->text()->null(),
            'deduction_note' => $this->text()->null(),
			'company_id' => $this->integer()->notNull(),
			'session' => $this->string(7)->notNull(),
			'created_at' => $this->integer()->notNull(),
			'created_by' => $this->integer()->notNull(),
			'updated_at' => $this->integer()->notNull(),
			'updated_by' => $this->integer()->notNull(),
        ]);
		
		// creates index for column `company_id`
        $this->createIndex(
            'idx-agreement_bill-company_id',
            'agreement_bill',
            'company_id'
        );

        // add foreign key for `company`
        $this->addForeignKey(
            'fk-agreement_bill-company_id',
            'agreement_bill',
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
        $this->dropTable('{{%agreement_bill}}');
    }
}
