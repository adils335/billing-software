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
        <?php $form = ActiveForm::begin(['id'=>'company-gst-form']); ?>
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
    </div>
    <?= $this->render('_addresses',['modelsAddresses'=>$modelsAddresses,'form'=>$form,'companyGst'=>$companyGst]);?>

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
$redirectUrl = Url::to(['company/company-gst']);
$id = $model->id;
$formatJs = <<< JS
    $('#companygst-state_id').change(function(){
        var state = $(this).val();
        location.href = "$redirectUrl" + "?id=$id&state_id="+state;      
    });
JS;
 
// Register the formatting script
$this->registerJs($formatJs);

?>