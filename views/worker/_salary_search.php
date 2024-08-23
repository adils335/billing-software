<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Search\Worker */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="worker-search">

    <?php $form = ActiveForm::begin([
        'action' => ['salary-record'],
        'method' => 'get',
    ]); ?>
    
  <div class="row">

<div class="col-md-4">
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

<div class="col-md-4">
  <?=  $form->field($model, 'from_month')->label('From Month')->widget(DatePicker::classname(), [
      'options' => ['placeholder' => 'Select Month ...','autocomplete'=>"off"],
      'pluginOptions' => [
      'autoclose'=>true,
            'format'=>'mm-yyyy',
            'minViewMode'=>'months',
      ]
  ]); ?>
</div>

  <div class="col-md-4">
   <?=  $form->field($model, 'to_month')->label('To Month')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'Select Month ...','autocomplete'=>"off"],
            'pluginOptions' => [
            'autoclose'=>true,
                  'format'=>'mm-yyyy',
                  'minViewMode'=>'months',
            ]
       ]); ?>
  </div>
</div>

<div class="row">

    <div class="col-md-4">
      <?= $form->field($model, 'worker_vendor_id')->widget(Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\models\WorkerVendor::find()->select(['id','CONCAT(name," ",code) as name'])->orderBy('id')->asArray()->all(), 'id', 'name'),
    'options' => ['placeholder' => 'Select ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
    ]); ?>
    </div>
    
    <div class="col-md-4">
      <?= $form->field($model, 'worker_id')->widget(Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\models\Worker::find()->select(['id','CONCAT(name," ",code) as name'])->where(['worker_vendor_id'=>$model->worker_vendor_id])->orderBy('id')->asArray()->all(), 'id', 'name'),
    'options' => ['placeholder' => 'Select ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
    ]); ?>
    </div>

    <div class="col-md-4">
      <?= $form->field($model, 'company')->widget(Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
    'options' => ['placeholder' => 'Select ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
    ]); ?>
    </div>
  </div>

  <div class="row">
	
    <div class="col-md-12">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset',['index'], ['class' => 'btn btn-default'])?>
    </div>
</div>
    <?php ActiveForm::end(); ?>

</div>

<?php
 $select2Options = json_encode([
    'data'=>'',         
    'multiple' => false,
    'theme' => 'krajee',
    'placeholder' => 'Select',
    'language' => 'en-US',
    'width' => '100%',
     ]);
$toVendorUrl = Url::to(['ledger/ajax-account-by-vendor']);

$script = <<< JS
  $(document).on("change","#workersalary-worker_vendor_id",function(){
           
        var vendor = $(this).val();
        var worker = $("#workersalary-worker_id");
        $.ajax({
          url:'$toVendorUrl',
          data:{vendor:vendor},
          success:function(data){
              select2Options = $select2Options;
              worker.find("option").remove();
           select2Options.data = data.data;
           worker.select2(select2Options);
          }
        });
        
  });
  JS;
  $this->registerJs($script);

