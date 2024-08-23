<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2; 
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Search\VendorBill */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendor-bill-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

        <div class="row">
            <div class="col-md-3">
             <?php
                if(empty($model->session)){
                    $model->session = \app\models\Session::getCurrentSession();
                }
            ?>
	 
            <?= $form->field($model, 'session')->widget(Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy(['session'=>SORT_DESC])->asArray()->all(), 'session', 'session'),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                                    'allowClear' => true
                        ],
            ]);?>

		</div>

        <div class="col-md-3"> 
        <?//= $form->field($model, 'id') ?>

            <?= $form->field($model, 'vendor_id')->widget(Select2::classname(), [
                   'data' => \yii\helpers\ArrayHelper::map(\app\models\Vendor::find()->select(['id','CONCAT(name," ",code) as name'])->orderBy('id')->asArray()->all(), 'id', 'name'),
                   'options' => ['placeholder' => 'Select ...'],
                   'pluginOptions' => [
                         'allowClear' => true
                    ],
              ]); ?>
        </div>

        <div class="col-md-3">
     
             <?=  $form->field($model, 'from_date')->widget(DatePicker::classname(), [
                     'options' => ['placeholder' => 'Enter date ...'],
                     'pluginOptions' => [
                     'autoclose'=>true,
                        'format'=>'dd-mm-yyyy'
                     ]
             ]); ?>
   
        </div>
     
         <div class="col-md-3">
     
             <?=  $form->field($model, 'to_date')->widget(DatePicker::classname(), [
                     'options' => ['placeholder' => 'Enter date ...'],
                     'pluginOptions' => [
                     'autoclose'=>true,
                        'format'=>'dd-mm-yyyy'
                     ]
             ]); ?>
   
        </div>
    </div>

    <?//= $form->field($model, 'bill_no') ?>

    <?//= $form->field($model, 'invoice_no') ?>

    <?//= $form->field($model, 'invoice_date') ?>

    <?php // echo $form->field($model, 'base_amount') ?>

    <?php // echo $form->field($model, 'schedule') ?>

    <?php // echo $form->field($model, 'schedule_rate') ?>

    <?php // echo $form->field($model, 'schedule_amount') ?>

    <?php // echo $form->field($model, 'taxable_amount') ?>

    <?php // echo $form->field($model, 'tax_amount') ?>

    <?php // echo $form->field($model, 'payable_amount') ?>

    <?php // echo $form->field($model, 'deduction_amount') ?>

    <?php // echo $form->field($model, 'pay_amount') ?>

    <?php // echo $form->field($model, 'company_id') ?>

    <?php // echo $form->field($model, 'session') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset',['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
