<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2; 
use kartik\depdrop\DepDrop;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Search\Payment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['sitewise-report'],
        'method' => 'get',
    ]); ?>
    
    <div class="row">
        
        <div class="col-sm-3">
							
		     <?= $form->field($model, "from_head")->
		        dropDownList(\app\models\Payment::buildFromHead(), ['prompt'=>Yii::t('app', 'Select ...'),'class'=>'form-control from-head'])->label("Head") ?>
		
        </div>
		<div class="col-sm-3">
		    <?= $form->field($model, "from_account")->widget(Select2::classname(), [
                 'data' => $model->fromAccount() ,
                 'options' => ['placeholder' => 'Select ...','class'=>'from-account'],
                 'pluginOptions' => [
                              'allowClear' => true
                      ],
           ])->label("Account"); ?>
        </div>
        
        <div class="col-sm-3">
		    <?= $form->field($model, "district_id")->widget(Select2::classname(), [
                 'data' => \app\models\District::buildDistrict(),
                 'options' => ['placeholder' => 'Select ...','class'=>'district'],
                 'pluginOptions' => [
                              'allowClear' => true
                      ],
           ]); ?>
        </div>
        
        <div class="col-sm-3">
		    <?= $form->field($model, "site_id")->widget(Select2::classname(), [
                 'data' => \yii\helpers\ArrayHelper::map(\app\models\Sites::find()->where(['district_id'=>$model->district_id,'status'=>\app\models\Sites::ACTIVE_STATUS])->orderBy(['name'=>SORT_ASC])->asArray()->all(), 'id', 'name'),
                 'options' => ['placeholder' => 'Select ...','class'=>'sites'],
                 'pluginOptions' => [
                              'allowClear' => true
                      ],
           ]); ?>
        </div>
        
    </div>
    <div class="row">
        
        <div class="col-sm-2">
             
          <?=  $form->field($model, "from_date")->widget(DatePicker::classname(), [
                             'options' => ['placeholder' => 'Enter date ...'],
                             'pluginOptions' => [
                                          'autoclose'=>true,
		                                  'format'=>'dd-mm-yyyy'
                                     ]
          ]); ?>
           
        </div>
        
        
        <div class="col-sm-2">
             
          <?=  $form->field($model, "to_date")->widget(DatePicker::classname(), [
                             'options' => ['placeholder' => 'Enter date ...'],
                             'pluginOptions' => [
                                          'autoclose'=>true,
		                                  'format'=>'dd-mm-yyyy'
                                     ]
          ]); ?>
           
        </div>
        
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Show'), ['class' => 'btn btn-primary','id'=>'sitewise-report-btn']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 
    $select2Options = json_encode([
         'data'=>'',         
         'multiple' => false,
         'theme' => 'krajee',
         'placeholder' => 'Select',
         'language' => 'en-US',
         'width' => '100%',
    ]);
     $sitesUrl = Url::to(['sites/ajax-sites']);
    $fromHeadUrl = Url::to(['payment/ajax-from-account']);
    
$formatJs = <<< JS

         $("#sitewise-report-btn").click(function(){
            var from_head = $("#payment-from_head").val(); 
            if(from_head == ""){
                $("#error-modal .message").html("Please select Head");
                $("#error-modal").modal("show");
                return false;
            }
            var from_account = $("#payment-from_account").val(); 
            if(from_account == ""){
                $("#error-modal .message").html("Please select From Account");
                $("#error-modal").modal("show");
                return false;
            }
         });
   
         $("#payment-from_head").change(function(){
			  var from_account = $("#payment-from_account")
			  $.ajax({
				  url:'$fromHeadUrl',
				  data:{from_head:$(this).val()},
				  success:function(data){
				      select2Options = $select2Options;
					  from_account.find("option").remove();
					  select2Options.data = data.data;
					  from_account.select2(select2Options);
				  }
			  });
		  });
		  
		$(document).on("change",".district",function(){
        var site = $(this).closest(".row").find(".sites");
        $.ajax({
          url:'$sitesUrl',
          data:{id:$(this).val()},
          success:function(data){
              select2Options = $select2Options;
            site.find("option").remove();
            select2Options.data = data.data;
            site.select2(select2Options);
          }
        });
      });
   
JS;
$this->registerJs($formatJs);
   
