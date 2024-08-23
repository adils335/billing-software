<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Search\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-search">

    <?php $form = ActiveForm::begin([
        'action' => ['salary-record'],
        'method' => 'get',
    ]); ?>

    <div class="row">

      <div class="col-md-4">
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

      <div class="col-md-4">
        <?=  $form->field($model, 'from_month')->label('From Month')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'Select Month ...','autocomplete'=>"off"],
            'pluginOptions' => [
            'autoclose'=>true,
                  'format'=>'mm-yyyy',
                  'minViewMode'=>'months',
            ]
        ]); ?>
      </div>

        <div class="col-md-4">
         <?=  $form->field($model, 'to_month')->label('To Month')->widget(DatePicker::classname(), [
                  'options' => ['placeholder' => 'Select Month ...','autocomplete'=>"off"],
                  'pluginOptions' => [
                  'autoclose'=>true,
                        'format'=>'mm-yyyy',
                        'minViewMode'=>'months',
                  ]
             ]); ?>
        </div>
    </div>

    <div class="row">

        <div class="col-md-4">
          <?= $form->field($model, 'employee_id')->label("Employee")->widget(Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\models\Employee::find()->select(['id','CONCAT(emp_name," ",emp_code) as name'])->orderBy('id')->asArray()->all(), 'id', 'name'),
        'options' => ['placeholder' => 'Select ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); ?>
      </div>

      <div class="col-md-4">
          <?= $form->field($model, 'company')->widget(Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
        'options' => ['placeholder' => 'Select ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); ?>
        </div>
    </div>
	
    <div class="row">
      <div class="col-md-12">
          <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
          <?= Html::a('Reset',['salary-record'], ['class' => 'btn btn-default']) ?>
      </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
