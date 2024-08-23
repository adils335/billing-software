<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Search\AgreementBill */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agreement-bill-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-md-3">
             <?php
                if(empty($model->session)){
                    $model->session = \app\models\Session::getCurrentSession();
                }
            ?>
	 
            <?= $form->field($model, 'session')->widget(Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy(['session'=>SORT_DESC])->asArray()->all(), 'session', 'session'),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                                    'allowClear' => true
                        ],
            ]);?>

		 </div>
         <div class="col-md-3">
     
             <?=  $form->field($model, 'from_date')->widget(DatePicker::classname(), [
                     'options' => ['placeholder' => 'Enter date ...'],
                     'pluginOptions' => [
                     'autoclose'=>true,
                        'format'=>'dd-mm-yyyy'
                     ]
             ]); ?>
   
        </div>
     
         <div class="col-md-3">
     
             <?=  $form->field($model, 'to_date')->widget(DatePicker::classname(), [
                     'options' => ['placeholder' => 'Enter date ...'],
                     'pluginOptions' => [
                     'autoclose'=>true,
                        'format'=>'dd-mm-yyyy'
                     ]
             ]); ?>
   
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'state_id')->label('State')->widget(Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
                'options' => ['placeholder' => 'Select ...'],
                'pluginOptions' => [
                                'allowClear' => true
                    ],
            ]); ?>
        </div>

    </div>

    <div class="row">
				
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
            <?= $form->field($model, 'contract_company_id')->label('Billing Company')->widget(Select2::classname(), [
                'data' => !$model->district_id?[]:\yii\helpers\ArrayHelper::map(\app\models\ContractCompany::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => 'Select ...'],
                'pluginOptions' => [
                                'allowClear' => true
                    ],
            ]); ?>
        </div>
    </div>


    <?//= $form->field($model, 'id') ?>

    <?//= $form->field($model, 'agreement_id') ?>

    <?//= $form->field($model, 'invoice_no') ?>

    <?//= $form->field($model, 'invoice_date') ?>

    <?//= $form->field($model, 'order_no') ?>

    <?php // echo $form->field($model, 'work_name') ?>

    <?php // echo $form->field($model, 'estimate_no') ?>

    <?php // echo $form->field($model, 'section_name') ?>

    <?php // echo $form->field($model, 'start_date') ?>

    <?php // echo $form->field($model, 'complete_date') ?>

    <?php // echo $form->field($model, 'circle_name') ?>

    <?php // echo $form->field($model, 'base_amount') ?>

    <?php // echo $form->field($model, 'schedule') ?>

    <?php // echo $form->field($model, 'schedule_rate') ?>

    <?php // echo $form->field($model, 'schedule_amount') ?>

    <?php // echo $form->field($model, 'taxable_amount') ?>

    <?php // echo $form->field($model, 'tax_amount') ?>

    <?php // echo $form->field($model, 'payable_amount') ?>

    <?php // echo $form->field($model, 'deduction_amount') ?>

    <?php // echo $form->field($model, 'pay_amount') ?>

    <?php // echo $form->field($model, 'company_id') ?>

    <?php // echo $form->field($model, 'session') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset',['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php 
$getStateUrl = Url::to(['common/ajax-state']);
$getDistrictUrl = Url::to(['common/ajax-district']);

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




JS;
$this->registerJs($script);
