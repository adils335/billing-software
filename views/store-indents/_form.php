<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Url;
use wbraganca\dynamicform\DynamicFormWidget;
use app\models\StoreIndents;
use kartik\file\FileInput;
/** @var yii\web\View $this */
/** @var app\models\StoreIndents $model */
/** @var yii\widgets\ActiveForm $form */
use app\models\Common;
$formatter = Yii::$app->formatter;
$common = new Common;

?>

<script>
</script>    

<div class="store-indents-form">

    <?php $form = ActiveForm::begin(['id'=>'DynamicForm']); ?>
    <?= $form->field($model, "indent_no")->hiddenInput()->label(false); ?>

    <div class="row">
        <div class="col-md-3">
            
            <?php
                $session = empty($model->session)?\app\models\Session::getCurrentSession():$model->session;
            ?>
            <?= $form->field($model, 'session')->widget(Select2::classname(), [
               'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy('id')->asArray()->all(), 'session', 'session'),
               'options' => ['placeholder' => 'Select ...','class'=>'session','value' => $session],
               'pluginOptions' => [
                       'allowClear' => true
               ],
           ]); ?>
        </div>
        <div class="col-md-3">
            <? if(!empty($model->indent_date))
                    $model->indent_date = Yii::$app->formatter->asDate($model->indent_date,'php:d-m-Y');

            ?>
            <?= $form->field($model, 'indent_date')->widget(DatePicker::classname(), [
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
        <div class="col-md-3">
            <?= $form->field($model, 'state_id')->widget(Select2::classname(), [
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
            <?= $form->field($model, 'district_id')->widget(Select2::classname(), [
                    'data' => $model->isNewRecord?[]:\yii\helpers\ArrayHelper::map(\app\models\District::find()->orderBy('id')->asArray()->all(), 'id', 'district'),
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
    </div>

    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'comment')->textarea(['rows' => '4']) ?>    
        </div>
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

    <?=$this->render('form\_items',['modelsStoreIndentsItems'=>$modelsStoreIndentsItems,'form'=>$form]);?>
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

    $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
        $(e.target).find('.container-items').find('.item:last').find('input').val('');
        $(e.target).find('.container-items').find('.item:last').find('select').val('').trigger('change');
    });
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

?>

