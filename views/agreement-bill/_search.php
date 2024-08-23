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
        'action' => ['bill-summary'],
        'method' => 'get',
        'id' => 'bill-summary',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>
    <div class="row">
        
        <div class="col-md-2">
              <?= $form->field($model, 'invoice_no');?>
        </div>
        
        <div class="col-md-2">
              <?= $form->field($model, 'session')->widget(Select2::classname(), [
                        'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy(['session'=>SORT_DESC])->asArray()->all(), 'session', 'session'),
                        'options' => ['placeholder' => 'Select ...'],
                        'pluginOptions' => [
                                        'allowClear' => true
                            ],
              ]);?>
        </div>
        
        <div class="col-md-3">
            <?= $form->field($model, 'company_id')->widget(Select2::classname(), [
                        'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                        'options' => ['placeholder' => 'Select ...'],
                        'pluginOptions' => [
                                        'allowClear' => true
                            ],
              ]); ?>
        </div>
        
        <div class="col-md-2">
			<?=  $form->field($model, 'from_date')->widget(DatePicker::classname(), [
                  'options' => ['placeholder' => 'Enter date ...'],
                  'pluginOptions' => [
                               'autoclose'=>true,
		                             'format'=>'dd-mm-yyyy'
                          ]
            ]); ?>
        </div>
        
        <div class="col-md-2">
			<?=  $form->field($model, 'to_date')->widget(DatePicker::classname(), [
                  'options' => ['placeholder' => 'Enter date ...'],
                  'pluginOptions' => [
                               'autoclose'=>true,
		                             'format'=>'dd-mm-yyyy'
                          ]
            ]); ?>
        </div>
        <?= $form->field($model,'status')->hiddenInput()->label(false);?>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'state_id')->label('State')->widget(Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
                'options' => ['placeholder' => 'Select ...'],
                'pluginOptions' => [
                                'allowClear' => true
                    ],
            ]); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'district_id')->label('District')->widget(Select2::classname(), [
                'data' => !$model->state_id?[]:\yii\helpers\ArrayHelper::map(\app\models\District::find()->orderBy('id')->asArray()->all(), 'id', 'district'),
                'options' => ['placeholder' => 'Select ...'],
                'pluginOptions' => [
                                'allowClear' => true
                    ],
            ]); ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'contract_company_id')->label('Contract Company')->widget(Select2::classname(), [
                'data' => !$model->district_id?[]:\yii\helpers\ArrayHelper::map(\app\models\ContractCompany::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => 'Select ...'],
                'pluginOptions' => [
                                'allowClear' => true
                    ],
            ]); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'agreement_id')->label('Agreement No')->widget(Select2::classname(), [
                'data' => !$model->contract_company_id?[]:\yii\helpers\ArrayHelper::map(\app\models\Agreement::find()->orderBy('id')->asArray()->all(), 'id', 'agreement_no'),
                'options' => ['placeholder' => 'Select ...'],
                'pluginOptions' => [
                                'allowClear' => true
                    ],
            ]); ?>
        </div>
        
    </div>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'site_id')->label('Site')->widget(Select2::classname(), [
                'data' => !$model->agreement_id?[]:\yii\helpers\ArrayHelper::map(\app\models\Sites::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
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
              <?= Html::button(Yii::t('app', 'Search'), ['class' => 'btn btn-primary','onclick'=>'$("#bill-summary").attr("action","/agreement-bill/bill-summary");$("#bill-summary").submit();']) ?>
              <?= Html::button(Yii::t('app', 'Generate Pdf'), ['class' => 'btn btn-warning','onclick'=>'$("#bill-summary").attr("action","/agreement-bill/generate-pdf");$("#bill-summary").submit();']) ?>
              <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
          </div>
          
       </div>
       
    </div>
    
    <?php ActiveForm::end(); ?>

</div>

<?php 
$getStateUrl = Url::to(['common/ajax-state']);
$getDistrictUrl = Url::to(['common/ajax-district']);
$getAgreementNoUrl = Url::to(['common/ajax-agreement-no']);
$getSitesUrl = Url::to(['common/ajax-sites']);

$script = <<<JS
    $("#agreementbill-state_id").change(function(){
        var state_id = $(this).val(); 
        var district = $("#agreementbill-district_id"); 
        $.ajax({
            url:"$getStateUrl",
            type:'get',
            data:{state_id},
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

    $("#agreementbill-district_id").change(function(){
        var district_id = $(this).val();
        var billing_company = $("#agreementbill-contract_company_id");
        $.ajax({
            url:"$getDistrictUrl",
            type:'get',
            data:{district_id},
            dataType:'JSON',
            success:function(res){
                billing_company.find("option").remove();
                billing_company.append("<option value=''>Select Billing Company</option>");
                for(var key in res){
                    billing_company.append("<option value='"+key+"'>"+res[key]+"</option>");
                }
            }
        });

    });

    $("#agreementbill-contract_company_id").change(function(){
        var contract_company_id = $(this).val();
        console.log(contract_company_id);
        var agreement = $("#agreementbill-agreement_id");
        $.ajax({
            url:"$getAgreementNoUrl",
            type:'get',
            data:{contract_company_id},
            dataType:'JSON',
            success:function(res){
                agreement.find("option").remove();
                agreement.append("<option value=''>Select Agreement</option>");
                for(var key in res){
                    agreement.append("<option value='"+key+"'>"+res[key]+"</option>");
                }
            }
        });

    });

    $("#agreementbill-agreement_id").change(function(){
        var agreement_id = $(this).val();
        var site = $("#agreementbill-site_id");
        $.ajax({
            url:"$getSitesUrl",
            type:'get',
            data:{agreement_id},
            dataType:'JSON',
            success:function(res){
                site.find("option").remove();
                site.append("<option value=''>Select Site</option>");
                for(var key in res){
                    site.append("<option value='"+key+"'>"+res[key]+"</option>");
                }
            }
        });

    });

JS;
$this->registerJs($script);

