<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Search\PurchaseBill */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="purchase-bill-search">

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

    <?//= $form->field($model, 'id') ?>

    <?//= $form->field($model, 'name') ?>

    <?//= $form->field($model, 'gstin') ?>

    <?//= $form->field($model, 'invoice_no') ?>

    <?//= $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'tax') ?>

    <?php // echo $form->field($model, 'total') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?//Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::a('Reset',['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
