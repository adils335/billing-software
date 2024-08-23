<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="row">
        
	    <div class="col-md-3">
	
             <?= $form->field($model, 'emp_name')->textInput(['maxlength' => true]) ?>

	    </div>

	    <div class="col-md-3">
	
             <?= $form->field($model, 'father_name')->textInput(['maxlength' => true]) ?>

	    </div>

	    <div class="col-md-3">
	
           <?= $form->field($model, 'emp_company')->label("Company")->widget(Select2::classname(), [
               'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
               'options' => ['placeholder' => 'Select a ...'],
               'pluginOptions' => [
                       'allowClear' => true
               ],
           ]); ?>

	    </div>
        <?php 
        if(!empty($model->leaves)){
            $model->leaves = json_decode($model->leaves,true);
        } 
        ?>
	    <div class="col-md-3">
	
           <?= $form->field($model, 'leaves')->widget(Select2::classname(), [
               'data' => [0=>'Sunday',1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday'],
               'options' => ['placeholder' => 'Select a ...'],
               'pluginOptions' => [
                       'allowClear' => true,
                       'multiple'=>true
               ],
           ]); ?>

	    </div>
    
    </div>
    
    <div class="row">
        
    	<div class="col-md-3"> 
	
             <?= $form->field($model, 'address')->textarea(['maxlength' => true]) ?>

    	</div>

	    <div class="col-md-3">
	
          <?= $form->field($model, 'state_id')->widget(Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
                    'options' => ['placeholder' => 'Select a Designation ...'],
                    'pluginOptions' => [
                          'allowClear' => true
                   ],
            ]); ?>

	    </div>

	    <div class="col-md-3">
	
          <?= $form->field($model, 'district_id')->widget(Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\models\District::find()->orderBy('id')->asArray()->all(), 'id', 'district'),
                    'options' => ['placeholder' => 'Select a Designation ...'],
                    'pluginOptions' => [
                          'allowClear' => true
                   ],
            ]); ?>

	    </div>

    	<div class="col-md-3"> 
	
             <?= $form->field($model, 'pincode')->textInput(['maxlength' => true]) ?>

    	</div>

    </div>
    
    <div class="row">
        
    	<div class="col-md-2"> 
	
             <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    	</div>

    	<div class="col-md-2"> 
	
             <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    	</div>

	    <div class="col-md-2">
	
             <?=  $form->field($model, 'dob')->widget(DatePicker::classname(), [
                  'options' => ['placeholder' => 'Enter birth date ...'],
                  'pluginOptions' => [
                  'autoclose'=>true,
		                'format'=>'dd-mm-yyyy'
                  ]
             ]); ?>

	    </div>
        <?php if( $model->isNewRecord ):?>
	    <div class="col-md-2">
             <?=  $form->field($model, 'joining_date')->widget(DatePicker::classname(), [
                  'options' => ['placeholder' => 'Enter birth date ...'],
                  'pluginOptions' => [
                  'autoclose'=>true,
		                'format'=>'dd-mm-yyyy'
                  ]
             ]); ?>
	    </div>
        <?php endif;?>

	<div class="col-md-1">
	
          <?= $form->field($model, 'is_deduction')->dropDownList(\app\models\Employee::buildDeduction(), ['prompt'=>Yii::t('app', 'Select ...')])->label("EPF") ?>

	</div>
	
	<div class="col-md-1">
	
          <?= $form->field($model, 'is_esi')->dropDownList(\app\models\Employee::buildDeduction(), ['prompt'=>Yii::t('app', 'Select ...')])->label("ESI") ?>

	</div>
	
	<div class="col-md-2">
	
          <?= $form->field($model, 'fixed_leave')->hiddenInput()->label(false);?>

	</div>
    </div>
    
    <div class="row">
        
	    <div class="col-md-3">
	
          <?= $form->field($model, 'designation')->widget(Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Designation::find()->orderBy('id')->asArray()->all(), 'id', 'designation'),
                    'options' => ['placeholder' => 'Select a Designation ...'],
                    'pluginOptions' => [
                          'allowClear' => true
                   ],
            ]); ?>

        </div>
    
	    <div class="col-md-3">
	
             <?= $form->field($model, 'refference')->textInput(['maxlength' => true]) ?>

	    </div>

	    <div class="col-md-2">
	
             <?= $form->field($model, 'salary')->textInput(['maxlength' => true]) ?>

	    </div>
	    
	    <div class="col-md-2">
	
             <?= $form->field($model, 'aadhar')->textInput(['maxlength' => true]) ?>

	    </div>

	    <div class="col-md-2">
	
             <?= $form->field($model, 'pancard')->textInput(['maxlength' => true]) ?>

	    </div>

	</div>

   <div class="row">
       
       
	<div class="col-md-2">
	
         <?= $form->field($model, 'expense_balance')->textInput(['maxlength' => true]) ?>

	</div>

	<div class="col-md-2">
	
          <?= $form->field($model, 'expense_type')->dropDownList(\app\models\Employee::buildTransactionType(), ['prompt'=>Yii::t('app', 'Select ...')]) ?>

	</div>

	<div class="col-md-2">
	
        <?= $form->field($model, 'personal_balance')->textInput(['maxlength' => true]) ?>

	</div>

	<div class="col-md-2">
	
          <?= $form->field($model, 'personal_type')->dropDownList(\app\models\Employee::buildTransactionType(), ['prompt'=>Yii::t('app', 'Select ...')]) ?>

	</div>
       
	<div class="col-md-2">
	
          <?= $form->field($model, 'status')->dropDownList(\app\models\Employee::buildStatus(), ['prompt'=>Yii::t('app', 'Select ...')]) ?>

	</div>

	<div class="col-md-2">
	
         <?= $form->field($model, 'session')->widget(Select2::classname(), [
                 'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy('id')->asArray()->all(), 'session', 'session'),
                 'options' => ['placeholder' => 'Select a Session ...'],
                 'pluginOptions' => [
                        'allowClear' => true
                 ],
         ]); ?>

	</div>

   </div>
   
   
   <div class="row">
       
	<div class="col-md-3">
	
         <?= $form->field($model, 'epf_no')->textInput(['maxlength' => true]) ?>

	</div>

	<div class="col-md-3">
	
        <?= $form->field($model, 'esi_no')->textInput(['maxlength' => true]) ?>

	</div>

   </div>

	<div class="row">
	    
	   <div class="col-md-12">
	
          <div class="form-group">
              <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
          </div>

	   </div>

    </div>
    
    <?php ActiveForm::end(); ?>
  
</div>
