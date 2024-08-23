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
        'action' => ['extra-salary'],
        'method' => 'get',
    ]); ?>

    <div class="col-md-3">
      <?= $form->field($model, 'employee_id')->label("Employee")->widget(Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\models\Employee::find()->select(['id','CONCAT(emp_name," ",emp_code) as name'])->orderBy('id')->asArray()->all(), 'id', 'name'),
    'options' => ['placeholder' => 'Select ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
    ]); ?>
    </div>

    <div class="col-md-3">
      <?= $form->field($model, 'company')->widget(Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
    'options' => ['placeholder' => 'Select ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
    ]); ?>
    </div>
	
    <div class="col-md-12">
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::a(Yii::t('app', 'New'), ['extra-salary-form'], ['class' => 'btn btn-success']) ?>
    </div>
</div>
    <?php ActiveForm::end(); ?>

</div>

</div>
