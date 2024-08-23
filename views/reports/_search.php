<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\search\Assessment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="assessment-search">
    
    
    <?php $form = ActiveForm::begin([
        'action' => [$action],
        'method' => 'get',
        'id' => 'bill-summary',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>
    <?php
        if(empty($model->session)){
            $model->session = \app\models\Session::getCurrentSession();
        }
    ?>
    <div class="row">
        
        <div class="col-md-3">
            <?= $form->field($model, 'company_id')->widget(Select2::classname(), [
                        'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                        'options' => ['placeholder' => 'Select ...'],
                        'pluginOptions' => [
                                        'allowClear' => true
                            ],
              ]); ?>
        </div>
        <!--
        <div class="col-md-2">
			<?=  $form->field($model, 'from_month')->widget(DatePicker::classname(), [
                  'options' => ['placeholder' => 'Select Month ...'],
                  'pluginOptions' => [
                               'autoclose' => true,
                               'minViewMode'=>'months',
                               'format' => 'mm-yyyy'
                          ]
            ]); ?>
        </div>
        -->
        <div class="col-md-2">
			<?=  $form->field($model, 'to_month')->widget(DatePicker::classname(), [
                  'options' => ['placeholder' => 'Enter date ...'],
                  'pluginOptions' => [
                               'autoclose' => true,
                               'minViewMode'=>'months',
                               'format' => 'mm-yyyy'
                          ]
            ]); ?>
        </div>
        
        <div class="col-md-3">
			<?=  $form->field($model, 'billing_company_state')->widget(Select2::classname(), [
                        'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
                        'options' => ['placeholder' => 'Select ...',
                        'onchange' => "getDistrict('agreementbill-billing_company_state', 'agreementbill-billing_company_district')"],
                        'pluginOptions' => [
                                        'allowClear' => true
                            ],
              ])->label('State');?>
        </div>
        
        <div class="col-md-3">
			<?=  $form->field($model, 'billing_company_district')->widget(Select2::classname(), [
                        'data' => \yii\helpers\ArrayHelper::map(\app\models\District::find()->where(['state_id'=>$model->billing_company_state])->orderBy('id')->asArray()->all(), 'id', 'district'),
                        'options' => ['placeholder' => 'Select ...',
                        'onchange' => "getCompany('agreementbill-billing_company_district', 'agreementbill-billing_company_id')"],
                        'pluginOptions' => [
                                        'allowClear' => true
                            ],
              ])->label('District');?>
        </div>
        
        <div class="col-md-3">
			<?=  $form->field($model, 'billing_company_id')->widget(Select2::classname(), [
                        'data' => \yii\helpers\ArrayHelper::map(\app\models\BillingCompany::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                        'options' => ['placeholder' => 'Select ...'],
                        'pluginOptions' => [
                                        'allowClear' => true
                            ],
              ])->label('Contract Company');?>
        </div>
        
        <?= $form->field($model, 'status')->hiddenInput(['value'=>1])->label(false);?>
        <?= $form->field($model, 'report_type')->hiddenInput(['value'=>1])->label(false);?>
    </div>
    
    
    <div class="row">
        
       <div class="col-md-12">    
        
          <div class="form-group">
              <?= Html::button(Yii::t('app', 'Filter'), ['class' => 'btn btn-primary','onclick'=>"$(form).attr('action','gst-report');$(form).submit();"]) ?>
              <div class="pull-right" style="margin-right:100px">
                    <?= $exportWidget ?>
              </div>
         </div>
          
       </div>
       
    </div>
    
    <?php ActiveForm::end(); ?>

</div>

<?php 

$districtStateWiseUrl = Url::to(['common/ship-to-district']);
$companyUrl = Url::to(['common/ship-to-company']);
$gstUrl = Url::to(['common/ship-to-gst']);

?>

<script>
      
  function getDistrict(stateId, districtId){
      
	  var state = $("#"+stateId).val();
	  var district = $("#"+districtId);
	  
	  $.ajax({
		  url:'<?= $districtStateWiseUrl?>',
		  data:{state:state},
		  dataType:'JSON',
		  success:function(res){
			  district.find("option").remove();
			  district.append("<option value=''>Select District</option>");
			  $.each(res,function(index,item){
			      district.append("<option value='"+index+"'>"+item+"</option>");
			  });
		  }
	  });
	  
  }
  
  function getCompany(districtId,companyId){
      
	  var district = $("#"+districtId).val();
	  var company = $("#"+companyId);
	  
	  $.ajax({
		  url:'<?= $companyUrl?>',
		  data:{district:district},
		  dataType:'JSON',
		  success:function(res){
			  company.find("option").remove();
			  company.append("<option value=''>Select Company</option>");
			  $.each(res,function(index,item){
			      company.append("<option value='"+index+"'>"+item+"</option>");
			  });
		  }
	  });
	  
  }
 
</script>