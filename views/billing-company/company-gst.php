<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Company */

$this->title = Yii::t('app', 'Create Company');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-create">
 <div class="session-index box box-primary"> 
		
		<div class="box-header with-border"> 
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="company-form">
        <?php $form = ActiveForm::begin(['id'=>'billing-company-form']); ?>
  <div class="row">
	
	<?= $form->field($companyGst, 'company_id')->hiddenInput(['value'=>$model->id])->label(false); ?>
	
    <div class="col-md-6">
       <?= $form->field($companyGst, 'state_id')->label("State")->widget(Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
    'options' => ['placeholder' => 'Select a State ...'],
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
</div>
    <?= $this->render('_addresses',['form'=>$form,'modelsAddresses'=>$modelsAddresses,'companyGst'=>$companyGst]);?>
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
$redirectUrl = Url::to(['billing-company/company-gst']);
$districtStateWiseUrl = Url::to(['district/district-state-wise']);
$formatJs = <<< JS

  $('#billingcompanygst-state_id').change(function(){
      
      var state = $(this).val();
      var district = $("#billingcompanygst-districts");
      var district_id = $(".district-id");
    
      $.ajax({
          url:'$districtStateWiseUrl',
          type:'post',
          data:{state:state,company_id:"$model->id",model:"BillingCompany",isNewRecord:"$companyGst->isNewRecord"},
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