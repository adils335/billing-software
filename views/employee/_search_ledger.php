<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Search\Ledger */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ledger-search">

    <?php $form = ActiveForm::begin([
        'action' => ['ledger'],
        'method' => 'get',
    ]); ?>
     
	<div class="row">
	
	 <div class="col-md-12">
	 
	     <div class="col-md-2">
	 
            <?= $form->field($model, 'account')->label("Employee")->widget(Select2::classname(), [
                   'data' => \yii\helpers\ArrayHelper::map(\app\models\Employee::find()->orderBy('id')->asArray()->all(), 'id', 'emp_name'),
                   'options' => ['placeholder' => 'Select ...','required'=>true],
                   'pluginOptions' => [
                         'allowClear' => true
                    ],
              ]); ?>

		 </div>
		 
	     <div class="col-md-2">
		 
            <?= $form->field($model, 'session')->label("Session")->widget(Select2::classname(), [
                   'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy('id')->asArray()->all(), 'session', 'session'),
                   'options' => ['placeholder' => 'Select ...'],
                   'pluginOptions' => [
                         'allowClear' => true
                    ],
              ]); ?>

		 </div>
		 
	     <div class="col-md-2">
		 
             <?=  $form->field($model, 'fromDate')->widget(DatePicker::classname(), [
                     'options' => ['placeholder' => 'Enter date ...'],
                     'pluginOptions' => [
                     'autoclose'=>true,
		                    'format'=>'dd-mm-yyyy'
                     ]
             ]); ?>
	 
		 </div>
		 
	     <div class="col-md-2">
		 
             <?=  $form->field($model, 'toDate')->widget(DatePicker::classname(), [
                     'options' => ['placeholder' => 'Enter date ...'],
                     'pluginOptions' => [
                     'autoclose'=>true,
		                    'format'=>'dd-mm-yyyy'
                     ]
             ]); ?>
	 
		 </div>
     <div class="col-md-2">
    <?= $form->field($model, 'account_type')->dropDownList(\app\models\Employee::buildAccountType(), ['prompt'=>Yii::t('app', 'Select ...'),'required'=>true]) ?>
    </div>
		 
         <div class="col-md-2">
	 
            <div class="form-group"><br>
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
            </div>

         </div>
   
     </div>
	<?php $model->type = (new \app\models\Ledger)::TYPE_EMPLOYEE;?> 
    <?php  echo $form->field($model, 'type')->hiddenInput()->label(false); ?>

   </div>
   
    <?php ActiveForm::end(); ?>
   
</div>
