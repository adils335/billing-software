<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Search\Ledger;

/* @var $this yii\web\View */
/* @var $model app\models\Search\Ledger */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ledger-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id'=>'ledger-search-form',
    ]); ?>
     
	<div class="row">
	
	 <div class="col-md-12">
	     
       <?php if(!$model->ledger){?>

        <div class="col-md-3">
             <?php 
            $sessonArray = \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy(['session'=>SORT_DESC])->asArray()->all(), 'session', 'session');
            $sessonArray['all'] = "All";
            ?>
	 
            <?= $form->field($model, 'session')->widget(Select2::classname(), [
                        'data' => $sessonArray,
                        'options' => ['placeholder' => 'Select ...'],
                        'pluginOptions' => [
                                        'allowClear' => true
                            ],
              ]);?>

		 </div>

	     <div class="col-md-3">
	 
            <?= $form->field($model, 'company_id')->label("Company")->widget(Select2::classname(), [
                   'data' => \app\models\Common::getCompanies(),
                   'options' => ['placeholder' => 'Select ...'],
                   'pluginOptions' => [
                         'allowClear' => true
                    ],
              ]); ?>

		 </div>
		 
	     <div class="col-md-3">
	 
           <?= $form->field($model, 'type')->dropDownList(\app\models\Ledger::buildType(), ['prompt'=>Yii::t('app', 'Select ...')]) ?>

		 </div>
	     
         <?php $class=""; 
          if($model->type != Ledger::TYPE_WORKER){
             $class="hide"; 
          }
         ?>
     <div class="col-md-3 <?= $class?>">
   
            <?= $form->field($model, 'vendor')->label("Vendor")->widget(Select2::classname(), [
                   'data' => \yii\helpers\ArrayHelper::map(\app\models\WorkerVendor::find()->select(['id','CONCAT(code," ",name) as name'])->orderBy('id')->asArray()->all(), 'id', 'name'),
                   'options' => ['placeholder' => 'Select ...'],
                   'pluginOptions' => [
                         'allowClear' => true
                    ],
              ]); ?>

     </div>

	   <div class="col-md-3">
	 
            <?= $form->field($model, 'account')->label("Account")->widget(Select2::classname(), [
                   'data' => $model->account(),
                   'options' => ['placeholder' => 'Select ...'],
                   'pluginOptions' => [
                         'allowClear' => true
                    ],
              ]); ?>

		 </div>
		 <?php }else{
           echo $form->field($model,"type")->hiddenInput()->label(false);
           echo $form->field($model,"account")->hiddenInput()->label(false);
           echo $form->field($model,"ledger")->hiddenInput()->label(false);
      }?> 

     </div>
     
     <div class="col-md-12">
         
     <div class="col-md-3">
     
             <?=  $form->field($model, 'fromDate')->widget(DatePicker::classname(), [
                     'options' => ['placeholder' => 'Enter date ...'],
                     'pluginOptions' => [
                     'autoclose'=>true,
                        'format'=>'dd-mm-yyyy'
                     ]
             ]); ?>
   
     </div>
     
     <div class="col-md-3">
     
             <?=  $form->field($model, 'toDate')->widget(DatePicker::classname(), [
                     'options' => ['placeholder' => 'Enter date ...'],
                     'pluginOptions' => [
                     'autoclose'=>true,
                        'format'=>'dd-mm-yyyy'
                     ]
             ]); ?>
   
     </div>

         <?php $class=""; 
          if(empty($model->account_type) && empty($model->ledger)){
             $class="hide"; 
          }
         ?>
         
         <div class="col-md-2 account-type <?= $class?>">
               <?= $form->field($model, 'account_type')->dropDownList(\app\models\Employee::buildAccountType(), ['prompt'=>Yii::t('app', 'Select ...')]) ?>
         </div>
         <div class="col-md-2"><br>
               <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
               <?= Html::a('Reset',['index'], ['class' => 'btn btn-default']) ?>
         </div>
                   
          
      </div>     
      
   </div>
   
   
    <?php ActiveForm::end(); ?>
   
</div>

<?php
     $employee = Ledger::TYPE_EMPLOYEE; 
     $worker = Ledger::TYPE_WORKER; 
     $select2Options = json_encode([
    'data'=>'',         
    'multiple' => false,
    //'theme' => 'krajee',
    'placeholder' => 'Select',
    'language' => 'en-US',
    'width' => '100%',
     ]);
     
     $toHeadUrl = Url::to(['ledger/ajax-account-type']);
     $toVendorUrl = Url::to(['ledger/ajax-account-by-vendor']);
     
$formatJs = <<< JS
       
       $("#ledger-type,#ledger-company_id").change(function(){
           
			  var type = $("#ledger-type").val();
			  var company_id = $("#ledger-company_id").val();
			  var account = $("#ledger-account");
			  
        if(type == $worker){
            $(".field-ledger-vendor").parent().removeClass('hide');
        }else{
            $(".field-ledger-vendor").parent().addClass('hide');
        }

        if(type == $employee){
            $(".account-type").removeClass('hide');
            $("#ledger-account_type").attr("required",true);
        }else{
            $(".account-type").addClass('hide');
            $("#ledger-account_type").val("");
            $("#ledger-account_type").attr("required",false);
        }

        if(type != "" && type != $worker){
			       $.ajax({
				       url:'$toHeadUrl',
				       data:{type:type,company_id:company_id},
				       success:function(data){
				           select2Options = $select2Options;
				           account.find("option").remove();
				     	  select2Options.data = data.data;
				     	  account.select2(select2Options);
				       }
			       });
        } 
			  
		  });
   
       $("#ledger-vendor").change(function(){
           
        var vendor = $("#ledger-vendor").val();
        var account = $("#ledger-account");

             $.ajax({
               url:'$toVendorUrl',
               data:{vendor:vendor},
               success:function(data){
                   select2Options = $select2Options;
                   account.find("option").remove();
                select2Options.data = data.data;
                account.select2(select2Options);
               }
             });
        
      });

      $("#ledger-search-form").submit(function(){
          
          var type = $("#ledger-type").val();
          var company_id = $("#ledger-company_id").val();
          var account = $("#ledger-account").val();
          
          if(company_id == ""){
             $("#error-modal .message").html("Please select company");
             $("#error-modal").modal("show");
             return false;
          }

          if(type == ""){
             $("#error-modal .message").html("Please select type");
             $("#error-modal").modal("show");
             return false;
          }

          if(account == ""){
             $("#error-modal .message").html("Please select account");
             $("#error-modal").modal("show");
             return false;
          }

      });
          
JS;
$this->registerJs($formatJs);