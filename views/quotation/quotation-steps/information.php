<?php

use yii\bootstrap\Html; 
use yii\bootstrap\ActiveForm;
use app\models\Agreement;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\web\JsExpression;
use yii\helpers\Url;
use app\models\District;

/* @var $this yii\web\View */
/* @var $model app\models\Agreement */

$this->title = Yii::t('app', 'Quotation Details');
/* $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Assessments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title; */
$cities = \yii\helpers\ArrayHelper::map(app\models\District::find()->all(), 'id', 'name');
$url = \yii\helpers\Url::to(['/lead/cities']); 
?>

		<div class="box-header with-border">
			<h4 class="box-title">
				<i class="fa fa-globe"></i> <?=Yii::t('app', 'Information'); ?> 
			</h4>
		</div>
		
		<div class="box-body different_billing_address">
		    
			<div class="row">
			    <div class="col-md-5">
					    <?= $form->field($quotation, "agreement_no")->label("Quotation Name"); ?>
				</div>
				<div class="col-md-2">
					    <?= $form->field($quotation, 'session')->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy('id')->asArray()->all(), 'session', 'session'),
                               'options' => ['placeholder' => 'Select a session ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
				</div>
				<div class="col-md-2">
				        <?php $quotation->date = !empty($quotation->date)?Yii::$app->formatter->asDate($quotation->date,'php:d-m-Y'):date("d-m-Y")?>
					    <?=  $form->field($quotation, 'date')->widget(DatePicker::classname(), [
                             'options' => ['placeholder' => 'Enter date ...'],
                             'pluginOptions' => [
                                          'autoclose'=>true,
		                                  'format'=>'dd-mm-yyyy'
                                     ]
                        ]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					    <?= $form->field($quotation, 'company_id')->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select a Company ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ])->label("Company"); ?>
				</div>
				<div class="col-md-3">
					    <?= $form->field($quotation, 'state_id')->label("State")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
                               'options' => ['placeholder' => 'Select ...',
                               'onchange' => "getDistrictAndGst('agreement-state_id', null,'agreement-gst_no', 'agreement-company_id','Company')"],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
				</div>
				
				<div class="col-md-3">
					    <?= $form->field($quotation, 'district_id')->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\District::find()->orderBy('id')->asArray()->all(), 'id', 'district'),
                               'options' => ['placeholder' => 'Select ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
				</div>
				
				<div class="col-md-3">
					    <?= $form->field($quotation, "gst_no"); ?>
				</div>
				
			</div>
			
			<div class="row">
			
				<div class="col-md-4">
					    <?= $form->field($quotation, 'contract_company_id')->label("Contract Company")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\ContractCompany::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select a Company ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
				</div>
				
				<div class="col-md-4">
					    <?= $form->field($quotation, 'contract_company_state')->label("Contract Company State")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
                               'options' => ['placeholder' => 'Select ...',
                               'onchange' => "getDistrictAndGst('agreement-contract_company_state', null,'agreement-contract_company_gst', 'agreement-contract_company_id','BillingCompany')"],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
				</div>
				
				<div class="col-md-4">
					    <?= $form->field($quotation, "contract_company_gst"); ?>
				</div>
				
			</div>
			<!--
			<div class="row">
			
				<div class="col-md-4">
					    <?
					    /*= $form->field($quotation, 'bill_to_id')->label("Bill To Party")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\BillingCompany::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select  ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]);*/
                              ?>
				</div>
				
				<div class="col-md-4">
					    <?
					    /*= $form->field($quotation, 'bill_to_state')->label("State")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
                               'options' => ['placeholder' => 'Select  ...',
                               'onchange' => "getDistrictAndGst('agreement-bill_to_state', null,'agreement-bill_to_gst', 'agreement-bill_to_id','ShippingCompany')"],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); */
                              ?>
				</div>
				
				<div class="col-md-4">
					    <?//= $form->field($quotation, "bill_to_gst"); ?>
				</div>
				
			</div>
			-->
		</div>

<?php 

$districtStateWiseUrl = Url::to(['district/district-state-wise']);
$gstUrl = Url::to(['district/gst']);

?>

<script>
    
  function getDistrictAndGst(stateId, districtId, gstId, companyId, model){
      
	  var state = $("#"+stateId).val();
	  var district = $("#"+districtId);
	  
	  if(districtId)
	  $.ajax({
		  url:'<?= $districtStateWiseUrl?>',
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
	  
	  getGst(model, stateId, gstId, companyId);
	  
  }
  
  function getGst(model, stateId, gstId, companyId){
      
	  var state = $("#"+stateId).val();
	  var gst = $("#"+gstId);
	  var company = $("#"+companyId).val();
	
	  $.ajax({
		  url:'<?=$gstUrl?>',
		  type:'post',
		  data:{state:state,model:model,company:company},
		  dataType:'JSON',
		  success:function(res){
			  
			  gst.val(res);
			  
		  }
	  });
	  
  }

</script>