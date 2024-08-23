<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Search\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-search">

  <div class="row">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="col-md-3">
        <?= $form->field($model, 'emp_name') ?>
    </div>
	
    <div class="col-md-2">
       <?= $form->field($model, 'emp_code') ?>
    </div>
	
    <div class="col-md-3">
      <?= $form->field($model, 'emp_company')->label("Company")->widget(Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
    'options' => ['placeholder' => 'Select Company ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>
    </div>
	
    <div class="col-md-2">
	<?= $form->field($model, 'status')->dropDownList(\app\models\Employee::buildStatus(), ['prompt'=>Yii::t('app', 'Select Status...')]) ?>
     
    </div>
	
    <div class="col-md-2">
    <?= $form->field($model, 'designation')->widget(Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\models\Designation::find()->orderBy('id')->asArray()->all(), 'id', 'designation'),
    'options' => ['placeholder' => 'Select Designation ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>
    </div>
    <?php // echo $form->field($model, 'status') ?>
    <?php // echo $form->field($model, 'designation') ?>
	
    <?php // echo $form->field($model, 'id') ?>
    <?php // echo $form->field($model, 'email') ?>
    <?php // echo $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'dob') ?>

    <?php // echo $form->field($model, 'joining_date') ?>


    <?php // echo $form->field($model, 'refference') ?>

    <?php // echo $form->field($model, 'aadhar') ?>

    <?php // echo $form->field($model, 'pancard') ?>

    <?php // echo $form->field($model, 'expense_balance') ?>

    <?php // echo $form->field($model, 'expense_type') ?>

    <?php // echo $form->field($model, 'personal_balance') ?>

    <?php // echo $form->field($model, 'personal_type') ?>


    <?php // echo $form->field($model, 'session') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>
   
	
    <div class="col-md-12">
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset',['index'], ['class' => 'btn btn-default']) ?>
    </div>
</div>
    <?php ActiveForm::end(); ?>

</div>

</div>
