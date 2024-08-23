<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\Common;
$formatter = Yii::$app->formatter;
$common = new Common;

/** @var yii\web\View $this */
/** @var app\models\Search\StoreIndents $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="store-indents-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        
        <?php
            if(empty($model->session)){
                $model->session = \app\models\Session::getCurrentSession();
            }
        ?>
        
        <div class = "col-md-3">
            <?= $form->field($model, 'session')->widget(Select2::classname(), [
               'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy(['session'=>SORT_DESC])->asArray()->all(), 'session', 'session'),
               'options' => ['placeholder' => 'Select ...','class'=>'session'],
               'pluginOptions' => [
                       'allowClear' => true
               ],
           ]); ?> 
        </div> 
        <div class = "col-md-3">
            <?=  $form->field($model, 'date_from')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'Enter Date'],
            'pluginOptions' => [
                'autoclose'=>true,
                'format'=>'dd-mm-yyyy'
            ]
        ]); ?>
        </div>
        <div class = "col-md-3">
            <?=  $form->field($model, 'date_to')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'Enter Date'],
            'pluginOptions' => [
                'autoclose'=>true,
                'format'=>'dd-mm-yyyy'
            ]
        ]); ?>
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
                    'data' =>!$model->state_id?[]:\yii\helpers\ArrayHelper::map(\app\models\District::find()->where('state_id')->orderBy('id')->asArray()->all(), 'id', 'district'),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
            ]); ?> 
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'billing_company_id')->widget(Select2::classname(), [
                    'data' =>!$model->district_id?[]:$common->billingCompany( $model->district_id ),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
            ]); ?>     
        </div> 
        <div class="col-md-3">
            <?= $form->field($model, 'agreement_id')->widget(Select2::classname(), [
                    'data' =>!$model->billing_company_id?[]:\yii\helpers\ArrayHelper::map(\app\models\Agreement::find()->orderBy('id')->asArray()->all(), 'id', 'agreement_no'),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
            ]); ?> 
        </div> 
    </div>
    <div class="row">
        
        <div class="col-md-3">
            <?= $form->field($model, 'site_id')->widget(Select2::classname(), [
                    'data' =>!$model->agreement_id?[]:$common->siteName( $model->agreement_id),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
            ]); ?>
        </div>   
    </div>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset',['index'],['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 
$getStateUrl = Url::to(['common/ajax-state']);
$getDistrictUrl = Url::to(['common/ajax-district']);
$getBillCompanyUrl = Url::to(['common/ajax-bill-company']);
$getAgreementUrl = Url::to(['common/ajax-agreement']);

$script = <<<JS
    $("#storeindents-state_id").change(function(){
        var state_id = $(this).val(); 
        var district = $("#storeindents-district_id"); 
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

    $("#storeindents-district_id").change(function(){
        var district_id = $(this).val();
        var billing_company = $("#storeindents-billing_company_id");
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

    $("#storeindents-billing_company_id").change(function(){
        var billing_company_id = $(this).val();
        var agreement = $("#storeindents-agreement_id");
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

    $("#storeindents-agreement_id").change(function(){
        var agreement_id = $(this).val();
        var site = $("#storeindents-site_id");
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