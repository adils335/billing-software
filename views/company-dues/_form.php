<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Worker */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-dues-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="row">
        
        <div class="col-md-6">
            
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            
        </div>
        
        <div class="col-md-6">
            
            <?= $form->field($model, 'father_name')->textInput(['maxlength' => true]) ?>
            
        </div>
        
    </div>

    <div class="row">
        
        <div class="col-md-4">
            
            <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>
            
        </div>
        
        <div class="col-md-4">
            
            <?= $form->field($model, 'pancard_no')->textInput(['maxlength' => true]) ?>
            
        </div>
        
        <div class="col-md-4">
            
            <?= $form->field($model, 'aadhar_no')->textInput(['maxlength' => true]) ?>
            
        </div>
        
    </div>

    <div class="row">
        
        <div class="col-md-6">
            
            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
            
        </div>
        
        <div class="col-md-2">
            
          <?= $form->field($model, 'state_id')->widget(Select2::classname(), [
                     'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
                     'options' => ['placeholder' => 'Select ...'],
                     'pluginOptions' => [
                              'allowClear' => true
                      ],
          ]); ?>
          
        </div>
        
        <div class="col-md-2">
             
          <?= $form->field($model, 'district_id')->widget(Select2::classname(), [
                     'data' => \yii\helpers\ArrayHelper::map(\app\models\District::find()->orderBy('id')->asArray()->all(), 'id', 'district'),
                     'options' => ['placeholder' => 'Select ...'],
                     'pluginOptions' => [
                              'allowClear' => true
                      ],
          ]); ?>
          
        </div>
        
        <div class="col-md-2">
            
              <?= $form->field($model, 'pincode')->textInput(['maxlength' => true]) ?>

        </div>
        
    </div>

    <div class="row">
        
        <div class="col-md-3">
            
             <?=  $form->field($model, 'joining_date')->widget(DatePicker::classname(), [
                             'options' => ['placeholder' => 'Enter date ...'],
                             'pluginOptions' => [
                                          'autoclose'=>true,
		                                  'format'=>'dd-mm-yyyy'
                                     ]
            ]); ?>

        </div>
        
        <div class="col-md-3">
            
              <?= $form->field($model, 'last_balance')->textInput(['maxlength' => true]) ?>

        </div>
        
        <div class="col-md-2">
            
          <?= $form->field($model, 'inout_type')->widget(Select2::classname(), [
                     'data' => \app\models\CompanyDues::buildBalanceType(),
                     'options' => ['placeholder' => 'Select ...'],
                     'pluginOptions' => [
                              'allowClear' => true
                      ],
          ]); ?>
          
        </div>
        
        <div class="col-md-2">
            
          <?= $form->field($model, 'status')->widget(Select2::classname(), [
                     'data' => \app\models\CompanyDues::buildStatus(),
                     'options' => ['placeholder' => 'Select ...'],
                     'pluginOptions' => [
                              'allowClear' => true
                      ],
          ]); ?>
          
        </div>
        
    </div>
    
    <div class="row">
        
        
        <div class="col-md-6">
            
          <?= $form->field($model, 'company_id')->widget(Select2::classname(), [
                     'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                     'options' => ['placeholder' => 'Select ...'],
                     'pluginOptions' => [
                              'allowClear' => true
                      ],
          ]); ?>
          
        </div>
        
        <div class="col-md-6">
            
          <?= $form->field($model, 'session')->widget(Select2::classname(), [
                     'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy('id')->asArray()->all(), 'session', 'session'),
                     'options' => ['placeholder' => 'Select ...'],
                     'pluginOptions' => [
                              'allowClear' => true
                      ],
          ]); ?>
          
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
<?php 
$districtStateWiseUrl = Url::to(['district/district-state-wise']);
$formatJs = <<< JS

  $('#companydues-state_id').change(function(){
	  
	  var state = $(this).val();
	  var district = $("#companydues-district_id");
	
	  $.ajax({
		  url:'$districtStateWiseUrl',
		  type:'post',
		  data:{state:state},
		  dataType:'JSON',
		  success:function(res){
			  
			  district.find("option").remove();
			  district.append("<option value=''>Select District</option>");
			  for(var key in res){
                district.append("<option value='"+key+"'>"+res[key]+"</option>");
              }
			  
		  }
	  });
	  
  });

JS;
 
// Register the formatting script
$this->registerJs($formatJs);

?>