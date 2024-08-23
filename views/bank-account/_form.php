<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\BankAccount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bank-account-form">
   
   <div class="row">
    <?php $form = ActiveForm::begin(); ?>
    
	<div class="col-sm-6">
           <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true]) ?>
    </div>
	<div class="col-sm-6">
          <?= $form->field($model, 'branch_name')->textInput(['maxlength' => true]) ?>
    </div>

	<div class="col-sm-3">
          <?= $form->field($model, 'account_no')->textInput(['maxlength' => true]) ?>
    </div>
	<div class="col-sm-3">

          <?= $form->field($model, 'ifsc_code')->textInput(['maxlength' => true]) ?>
    </div>
	<div class="col-sm-6">

          <?= $form->field($model, 'customer_name')->textInput(['maxlength' => true]) ?>
    </div>
	<div class="col-sm-4">

          <?= $form->field($model, 'openning_balance')->textInput(['maxlength' => true]) ?>
    </div>
	<div class="col-sm-2">
               <?= $form->field($model, 'balance_type')->widget(Select2::classname(), [
                               'data' =>\app\models\BankAccount::buildBalanceType(),
                               'options' => ['placeholder' => 'Select  ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>

    </div>
	<div class="col-sm-3">

          <?= $form->field($model, 'company_id')->label("Company")->widget(Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
    'options' => ['placeholder' => 'Select a Company ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>
    </div>
	<div class="col-sm-3">

          <?= $form->field($model, 'session')->widget(Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy('id')->asArray()->all(), 'session', 'session'),
    'options' => ['placeholder' => 'Select a Session ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>
    </div>
	<div class="col-sm-12">
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

</div>
