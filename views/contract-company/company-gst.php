<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Company */

$this->title = Yii::t('app', 'Add Company GSt');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-create">
 <div class="session-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <div class="company-form">
    <?php $form = ActiveForm::begin(['id'=>'company-address']); ?>
	
	<?= $form->field($companyGst, 'company_id')->hiddenInput(['value'=>$model->id])->label(false); ?>
	
    <div class="col-md-6">
       <?= $form->field($companyGst, 'state_id')->widget(Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
    'options' => ['placeholder' => 'Select a State ...','class'=>'state_id'],
    'pluginOptions' => [
        'allowClear' => true
    ],
    ]); ?>
    </div>
	
	
    <div class="col-md-6">
         <?= $form->field($companyGst, 'gst_no')->textInput(['maxlength' => true]) ?>
    </div>
 <?php $companyGst->districts = json_decode($companyGst->districts)?>
    <div class="col-md-12">
       <?= $form->field($companyGst, 'districts')->widget(Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\models\District::find()->where(['state_id'=>$companyGst->state])->orderBy('id')->asArray()->all(), 'id', 'district'),
    'options' => ['placeholder' => 'Select a State ...'],
    'pluginOptions' => [
        'allowClear' => true,
        'multiple'=>true,
    ],
    ]); ?>
    </div>

    <?=$this->render('address\_items',['modelsAddresses'=>$modelsAddresses,'form'=>$form,'companyGst'=>$companyGst]);?>
	
    <div class="col-md-12">
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>

<?php 
$redirectUrl = Url::to(['contract-company/company-gst']);
$districtStateWiseUrl = Url::to(['district/district-state-wise']);
$formatJs = <<< JS

  $('#contractcompanygst-state_id').change(function(){
      
      var state = $(this).val();
      var district = $("#contractcompanygst-districts");
      var district_id = $(".district-id");
    
      $.ajax({
          url:'$districtStateWiseUrl',
          type:'post',
          data:{state:state,company_id:"$model->id",model:"ContractCompany",isNewRecord:"$companyGst->isNewRecord"},
          dataType:'JSON',
          success:function(res){
              
              if( res.redirect ){
                  location.href = "$redirectUrl" + "?id="+res.company_id + "&gst_id="+res.gst_id;
              }
              
              district.find("option").remove();
              district_id.find("option").remove();
              district.append("<option value=''>Select District</option>");
              district_id.append("<option value=''>Select District</option>");
              for(var key in res){
                district.append("<option value='"+key+"'>"+res[key]+"</option>");
                district_id.append("<option value='"+key+"'>"+res[key]+"</option>");
              }
              
          }
      });
      
  });

JS;
 
// Register the formatting script
$this->registerJs($formatJs);

?>