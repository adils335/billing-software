<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Vendor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendor-form">
 
    <?php $form = ActiveForm::begin(); ?>
     
      <div class="row">
          
	      <div class="col-sm-4"> 
	
             <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
	      </div>
	      
	      <div class="col-sm-4"> 
	
             <?= $form->field($model, 'father_name')->textInput(['maxlength' => true]) ?>
    
	      </div>
	      
	      <div class="col-sm-2"> 

             <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

	       </div>
	       
	     <div class="col-sm-2"> 

             <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

	     </div>
	     
	 </div>      
	 
     <div class="row">
         
         <div class="col-sm-3"> 

              <?= $form->field($model, 'address')->textarea(['maxlength' => true]) ?>

	     </div>
	
         <div class="col-md-3">
                     <?= $form->field($model, 'state_id')->label("State")->widget(Select2::classname(), [
                           'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
                           'options' => ['placeholder' => 'Select  ...'],
                           'pluginOptions' => [
                                    'allowClear' => true
                           ],
                    ]); ?>
         </div>
	
         <div class="col-md-3">
                     <?= $form->field($model, 'district_id')->label("District")->widget(Select2::classname(), [
                           'data' => \yii\helpers\ArrayHelper::map(\app\models\District::find()->orderBy('id')->asArray()->all(), 'id', 'district'),
                           'options' => ['placeholder' => 'Select  ...'],
                           'pluginOptions' => [
                                    'allowClear' => true
                           ],
                    ]); ?>
         </div>
	
	      <div class="col-sm-3"> 

            <?= $form->field($model, 'pincode')->textInput(['maxlength' => true]) ?>

	      </div>
	      
	 </div>    
	
	<div class="row">
	    
	    <div class="col-sm-6"> 

           <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

	    </div>
     	<div class="col-sm-6"> 

            <?= $form->field($model, 'company_type')->textInput(['maxlength' => true]) ?>

     	</div>
	    
	</div>
	
	<div class="row">
	    
	    <div class="col-sm-3"> 

           <?= $form->field($model, 'gst_no')->textInput(['maxlength' => true]) ?>

	    </div>
     	<div class="col-sm-3"> 

            <?= $form->field($model, 'pancard_no')->textInput(['maxlength' => true]) ?>

     	</div>
	    <div class="col-sm-3"> 

            <?= $form->field($model, 'last_balance')->textInput(['maxlength' => true]) ?>

	    </div>
	    
	    <div class="col-sm-3"> 

             <?= $form->field($model, 'balance_type')->widget(Select2::classname(), [
                               'data' =>\app\models\Vendor::buildBalanceType(),
                               'options' => ['placeholder' => 'Select  ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
            ]); ?>

	    </div>
	    
	</div>
	
	<div class="row">
	    
	    <div class="col-sm-4"> 

          <?= $form->field($model, 'status')->dropDownList(\app\models\Vendor::buildStatus(), ['prompt'=>Yii::t('app', 'Select ...')]) ?>

	    </div>
	    <div class="col-sm-4"> 

               <?= $form->field($model, 'company_id')->label("Company")->widget(Select2::classname(), [
                     'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                     'options' => ['placeholder' => 'Select a Company ...'],
                     'pluginOptions' => [
                         'allowClear' => true
                     ],
               ]); ?>

	    </div>
	    <div class="col-sm-4"> 

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
       	
	 <div class="col-sm-12"> 

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

  $('#vendor-state_id').change(function(){
	  
	  var state = $(this).val();
	  var district = $("#vendor-district_id");
	
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