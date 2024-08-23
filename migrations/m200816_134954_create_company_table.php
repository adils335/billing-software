<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%company}}`.
 */
class m200816_134954_create_company_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%company}}', [
            'id' => $this->primaryKey(),
			'name'=>$this->string()->notNull(),
			'type'=>$this->integer()->notNull(),
			'address'=>$this->text()->notNull(),
			'state'=>$this->integer()->notNull(),
			'district'=>$this->integer()->notNull(),
			'pincode'=>$this->string(6)->notNull(),
			'person'=>$this->string()->notNull(),
			'number'=>$this->string(12)->notNull(),
			'email'=>$this->string()->notNull(),
			'pancard_no'=>$this->string()->notNull(),
			'gst_no'=>$this->string()->notNull(),
			'created_at'=>$this->integer()->notNull(),
			'created_by'=>$this->integer()->notNull(),
			'updated_at'=>$this->integer()->notNull(),
			'updated_by'=>$this->integer()->notNull(),
        ]);
		
		// creates index for column `type`
        $this->createIndex(
            'idx-company-type',
            'company',
            'type'
        );

        // add foreign key for State `company_type`
        $this->addForeignKey(
            'fk-company-type',
            'company',
            'type',
            'company_type',
            'id',
            'CASCADE'
        );
		
		// creates index for column `state`
        $this->createIndex(
            'idx-company-state',
            'company',
            'state'
        );

        // add foreign key for Table `state`
        $this->addForeignKey(
            'fk-company-state',
            'company',
            'state',
            'state',
            'id',
            'CASCADE'
        );
		
		// creates index for column `district`
        $this->createIndex(
            'idx-company-district',
            'company',
            'district'
        );

        // add foreign key for Table `district`
        $this->addForeignKey(
            'fk-company-district',
            'company',
            'district',
            'district',
            'id',
            'CASCADE'
        );
		
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%company}}');
    }
}
