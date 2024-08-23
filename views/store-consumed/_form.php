<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
use wbraganca\dynamicform\DynamicFormWidget;
use app\models\StoreConsumed;
use app\models\Common;
use kartik\date\DatePicker;
use kartik\file\FileInput;

/** @var yii\web\View $this */
/** @var app\models\StoreConsumed $model */
/** @var yii\widgets\ActiveForm $form */
$common = new Common;
?>

<div class="store-consumed-form">
    <?php $form = ActiveForm::begin(['id'=>'DynamicForm']); ?>
    <div class="row">
        <?= $form->field($model, "invoice_no")->hiddenInput()->label(false); ?>
        <?php
	        $session = empty($model->session)?\app\models\Session::getCurrentSession():$model->session;
	     ?>
        <div class="col-md-3">
            <?= $form->field($model, 'session')->widget(Select2::classname(), [
               'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy('id')->asArray()->all(), 'session', 'session'),
               'options' => ['placeholder' => 'Select ...','class'=>'session','value' => $session],
               'pluginOptions' => [
                       'allowClear' => true
               ],
           ]); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'bill_no')->textInput(['maxlength' => true])->textInput(['placeholder'=>'Enter Bill No ']) ?>
        </div>
        <div class="col-md-3">
            <? if(!empty($model->invoice_date))
                    $model->invoice_date = Yii::$app->formatter->asDate($model->invoice_date,'php:d-m-Y');

            ?>
            <?= $form->field($model, 'invoice_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Enter Indent Date '],
                'pluginOptions' => [
                'autoclose'=>true,
                        'format'=>'dd-mm-yyyy'
                    ]
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
    </div>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'state_id')->widget(Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
                'options' => ['placeholder' => 'Select ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
        </div> 
        
        <div class="col-md-3">
            <?= $form->field($model, 'district_id')->widget(Select2::classname(), [
                    'data' => $model->isNewRecord?[]:\yii\helpers\ArrayHelper::map(\app\models\District::find()->orderBy('id')->asArray()->all(), 'id', 'district'),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
            ]); ?> 
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'from_head')->dropDownList(\app\models\Common::buildFromHead(), ['prompt'=>Yii::t('app', 'Select ...')])?> 
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'from_account')->widget(Select2::classname(), [
                    'data' => $model->isNewRecord?[]:$common->fromAccount($model->from_head),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
            ]); ?>
        </div>
        
           
    </div>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'to_head')->dropDownList(\app\models\Common::buildToHead(), ['prompt'=>Yii::t('app', 'Select ...')])?> 
        </div>

        <?php $wv_class = $model->worker_vendor && Common::TO_HEAD_WORKER == $model->to_head?"":"hide";?>

        <div class="col-md-3 <?= $wv_class?> worker-vendor-div">
            <?= $form->field($model, 'worker_vendor')->widget(Select2::classname(), [
                    'data' =>\yii\helpers\ArrayHelper::map(\app\models\WorkerVendor::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
            ]); ?>   
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'to_account')->widget(Select2::classname(), [
                    'data' => $model->isNewRecord?[]:$common->toAccount($model->to_head),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
            ]); ?>    
        </div> 

        <div class="col-md-3">
            <?= $form->field($model, 'billing_company_id')->widget(Select2::classname(), [
                    'data' => $model->isNewRecord?[]:$common->billingCompany( $model->district_id ),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
            ]); ?>     
        </div>
    </div>

    <div class ="row">
        <div class="col-md-3">
            <?= $form->field($model, 'agreement_id')->widget(Select2::classname(), [
                    'data' => $model->isNewRecord?[]:\yii\helpers\ArrayHelper::map(\app\models\Agreement::find()->orderBy('id')->asArray()->all(), 'id', 'agreement_no'),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
            ]); ?> 
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'site_id')->widget(Select2::classname(), [
                    'data' => $model->isNewRecord?[]:$common->siteName( $model->agreement_id),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'comment')->textarea(['rows' => '4']) ?>    
        </div>

    </div>
    <div class="row"> 
        
        <div class="col-md-6">
            <?= $form->field($model, 'attachment_file')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*'],
                'pluginOptions'=>[
                        'allowedFileExtensions'=>['jpg', 'gif', 'png', 'bmp'],
                        'showUpload' => false,
                        'overwriteInitial' => false,
                        'showPreview'=>false,
                ],
                
            ]); ?>
        </div>
    </div>


    <?=$this->render('form\_item',['items'=>$items,'form'=>$form]);?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 
$getStateUrl = Url::to(['common/ajax-state']);
$getDistrictUrl = Url::to(['common/ajax-district']);
$getBillCompanyUrl = Url::to(['common/ajax-bill-company']);
$getAgreementUrl = Url::to(['common/ajax-agreement']);

$script = <<<JS
    $("#storeconsumed-state_id").change(function(){
        var state_id = $(this).val(); 
        var district = $("#storeconsumed-district_id"); 
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

    $("#storeconsumed-district_id").change(function(){
        var district_id = $(this).val();
        var billing_company = $("#storeconsumed-billing_company_id");
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

    $("#storeconsumed-billing_company_id").change(function(){
        var billing_company_id = $(this).val();
        var agreement = $("#storeconsumed-agreement_id");
        $.ajax({
            url:"$getBillCompanyUrl",
            type:'get',
            data:{billing_company_id},
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

    $("#storeconsumed-agreement_id").change(function(){
        var agreement_id = $(this).val();
        var site = $("#storeconsumed-site_id");
        $.ajax({
            url:"$getAgreementUrl",
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

?>

<!-- To Head  and To Accounts -->

<?php
$worker = Common::TO_HEAD_WORKER;
$getFromHeadUrl = Url::to(['common/ajax-from-head']);
$getWorkerVendorUrl = Url::to(['common/ajax-account-by-vendor']);
$getToHeadUrl = Url::to(['common/ajax-to-head']);
$getVendorToAccountUrl = Url::to(['common/ajax-vendor-to-account']);
$script = <<<JS

$(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    $(e.target).find('.container-items').find('.item:last').find('input').val('');
    $(e.target).find('.container-items').find('.item:last').find('select').val('').trigger('change');
});


$("#storeconsumed-from_head").change(function(){
    var from_head = $(this).val();
    var from_account = $("#storeconsumed-from_account"); 
    $.ajax({
        url:"$getFromHeadUrl",
        type:'get',
        data:{from_head:$(this).val()},
        dataType:'JSON',
        success:function(res){
            from_account.find("option").remove();
            from_account.append("<option value=''>Select From Account</option>");
            for(var key in res){
                from_account.append("<option value='"+key+"'>"+res[key]+"</option>");
            }
        }
    });

});

$("#storeconsumed-worker_vendor").change(function(){
    var worker_vendor = $(this).val();
    var to_account = $("#storeconsumed-to_account"); 
    $.ajax({
        url:"$getVendorToAccountUrl",
        type:'get',
        data:{worker_vendor},
        dataType:'JSON',
        success:function(res){
            to_account.find("option").remove();
            to_account.append("<option value=''>Select To Account</option>");
            for(var key in res){
                to_account.append("<option value='"+key+"'>"+res[key]+"</option>");
            }
        }
    });
});

$("#storeconsumed-to_head").change(function(){
    var to_head = $(this).val();
    var to_account = $("#storeconsumed-to_account"); 
        if($(this).val() == $worker){
            $(".worker-vendor-div").removeClass("hide");
            return true;
        }else{
            $(".worker-vendor-div").addClass("hide");
        }
    $.ajax({
        url:"$getToHeadUrl",
        type:'get',
        data:{to_head:$(this).val()},
        dataType:'JSON',
        success:function(res){
            to_account.find("option").remove();
            to_account.append("<option value=''>Select To Account</option>");
            for(var key in res){
                to_account.append("<option value='"+key+"'>"+res[key]+"</option>");
            }
        }
    });
});

JS;
$this->registerJs($script);

?>

