<?php

use yii\bootstrap\Html; 
use yii\bootstrap\ActiveForm;
use app\models\Agreement;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\web\JsExpression;
use app\models\District;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\Agreement */

$this->title = Yii::t('app', 'Agreement Details');
/* $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Assessments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title; */
$cities = \yii\helpers\ArrayHelper::map(app\models\District::find()->all(), 'id', 'name');
$url = \yii\helpers\Url::to(['/lead/cities']); 
$formatter = Yii::$app->formatter;
?>

<div class="box box-default">
		<div class="box-header with-border">
			<h4 class="box-title">
				<i class="fa fa-globe"></i> <?=Yii::t('app', 'Information'); ?> 
			</h4>
		</div>
		 <?php
        if(empty($agreement->session)){
            $agreement->session = \app\models\Session::getCurrentSession();
        }
    ?>
		<div class="box-body different_billing_address">
		    
			<div class="row">
			
				<div class="col-md-2">
					    <?= $form->field($agreement, 'session')->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy(['session'=>SORT_DESC])->asArray()->all(), 'session', 'session'),
                               'options' => ['placeholder' => 'Select a session ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
				</div>
				
				<div class="col-md-3">
					    <?= $form->field($agreement, 'company_id')->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select a Company ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ])->label("Company"); ?>
				</div>
				
				<div class="col-md-3">
					    <?= $form->field($agreement, 'state_id')->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
                               'options' => ['placeholder' => 'Select ...','onchange' => "getDistrictAndGst('agreement-state_id','agreement-district_id','agreement-gst_no', 'agreement-company_id','Company')"],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
				</div>
				
				<div class="col-md-2">
					    <?= $form->field($agreement, 'district_id')->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\District::find()->orderBy('id')->asArray()->all(), 'id', 'district'),
                               'options' => ['placeholder' => 'Select ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
				</div>
				
				<div class="col-md-2">
					    <?= $form->field($agreement, "gst_no"); ?>
				</div>
				
				
			</div>
			
			<div class="row">
				<div class="col-md-6">
					    <?= $form->field($agreement, "agreement_name"); ?>
				</div>
				<div class="col-md-6">
					    <?= $form->field($agreement, "agreement_no"); ?>
				</div>
			</div>
			
			<div class="row">
			    
				<div class="col-md-3">
					    <?= $form->field($agreement, "cost"); ?>
				</div>
				
				<div class="col-md-3">
					    <?= $form->field($agreement, "zone"); ?>
				</div>
				
			    <?php
			     if($agreement->date){
			        $agreement->date = $formatter->asDate($agreement->date,"php:d-m-Y");
			     }
			    ?>
				<div class="col-md-3">
					    <?=  $form->field($agreement, 'date')->widget(DatePicker::classname(), [
                             'options' => ['placeholder' => 'Enter date ...'],
                             'pluginOptions' => [
                                          'autoclose'=>true,
		                                  'format'=>'dd-mm-yyyy'
                                     ]
                        ]); ?>
				</div>
				<div class="col-md-3">
				    <?php
			     if($agreement->expire_date){
			        $agreement->expire_date = $formatter->asDate($agreement->expire_date,"php:d-m-Y");
			     }
			    ?>
					    <?=  $form->field($agreement, 'expire_date')->widget(DatePicker::classname(), [
                             'options' => ['placeholder' => 'Enter Expire date ...'],
                             'pluginOptions' => [
                                          'autoclose'=>true,
		                                  'format'=>'dd-mm-yyyy'
                                     ]
                        ]); ?>
				</div>
			</div>
			
			<div class="row">
			   
				<div class="col-md-3">
					    <?= $form->field($agreement, 'contract_company_id')->label("Contract Company")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\ContractCompany::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select a Company ...','onchange'=>"getState(this.value,'ContractCompany')"],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
				</div>
				
				<div class="col-md-3">
					    <?= $form->field($agreement, 'contract_company_state')->label("Contract Company State")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
                               'options' => ['placeholder' => 'Select a State ...',
                               'onchange' => "getDistrictAndGst('agreement-contract_company_state', 'agreement-contract_company_district','agreement-contract_company_gst', 'agreement-contract_company_id','ContractCompany')"],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
				</div>
				
				<div class="col-md-3">
					    <?= $form->field($agreement, 'contract_company_district')->label("Contract Company District")->widget(Select2::classname(), [
                               'data' => $agreement->contractCompanyDistricts,
                               'options' => ['placeholder' => 'Select a District ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
				</div>
				
				<div class="col-md-3">
					    <?= $form->field($agreement, "contract_company_gst"); ?>
				</div>
				
			</div>
			
			<div class="row">
			
				<div class="col-md-6">
					    <?= $form->field($agreement, 'schedule')->widget(Select2::classname(), [
                               'data' =>\app\models\Agreement::buildSchedule(),
                               'options' => ['placeholder' => 'Select a Schedule ...'],
                               'pluginOptions' => [
                                            'allowClear' => true,
                                    ],
                              ]); ?>
				</div>
				
				<div class="col-md-6">
					    <?= $form->field($agreement, "rate"); ?>
				</div>
				
			</div>
			
		</div>
</div>

<?php 

$stateUrl = Url::to(['common/state']);
$districtStateWiseUrl = Url::to(['common/district']);
$gstUrl = Url::to(['district/gst']);

?>

<script>
  
  function getState(companyId,model){
     var state = $("#agreement-contract_company_state"); 
     
     $.ajax({
		  url:'<?= $stateUrl?>',
		  type:'get',
		  data:{company_id:companyId,model:model},
		  dataType:'JSON',
		  success:function(res){
			  state.find("option").remove();
			  state.append("<option value=''>Select State</option>");
			  for(var key in res){
                state.append("<option value='"+key+"'>"+res[key]+"</option>");
              }
		  }
	  }); 
      
  }
      
  function getDistrictAndGst(stateId, districtId, gstId, companyId, model){
      
	  var companyId = $("#"+companyId).val();
	  var state = $("#"+stateId).val();
	  var district = $("#"+districtId);
	  
	  if(districtId)
	  $.ajax({
		  url:'<?= $districtStateWiseUrl?>',
		  type:'get',
		  data:{company_id:companyId,state:state,model:model},
		  dataType:'JSON',
		  success:function(res){
			  district.find("option").remove();
			  district.append("<option value=''>Select District</option>");
			  for(var key in res){
                district.append("<option value='"+key+"'>"+res[key]+"</option>");
              }
		  }
	  });
	  
	  getGst(model, stateId, gstId, companyId);
	  
  }
  
  function getGst(model, stateId, gstId, companyId){
      
	  var state = $("#"+stateId).val();
	  var gst = $("#"+gstId);
	
	  $.ajax({
		  url:'<?=$gstUrl?>',
		  type:'post',
		  data:{state:state,model:model,company:companyId},
		  dataType:'JSON',
		  success:function(res){
			  
			  gst.val(res);
			  
		  }
	  });
	  
  }

</script>