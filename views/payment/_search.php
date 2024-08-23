<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2; 
use kartik\depdrop\DepDrop;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Search\Payment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-search">

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
        <div class="col-sm-3">
            <?= $form->field($model, 'session')->widget(Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy(['session'=>SORT_DESC])->asArray()->all(), 'session', 'session'),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                                    'allowClear' => true
                        ],
            ]);?>
        </div>
        
        <div class="col-sm-3">
							
		     <?= $form->field($model, "from_head")->
		        dropDownList(\app\models\Payment::buildFromHead(), ['prompt'=>Yii::t('app', 'Select ...'),'class'=>'form-control from-head']) ?>
		
        </div>
		<div class="col-sm-3">
		    <?= $form->field($model, "from_account")->widget(Select2::classname(), [
                 'data' => $model->fromAccount() ,
                 'options' => ['placeholder' => 'Select ...','class'=>'from-account'],
                 'pluginOptions' => [
                              'allowClear' => true
                      ],
           ]); ?>
        </div>
        
        <div class="col-sm-3">
             
          <?=  $form->field($model, "from_date")->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'Enter date ...'],
            'pluginOptions' => [
                        'autoclose'=>true,
                        'format'=>'dd-mm-yyyy'
                    ]
          ]); ?>
           
        </div>
    </div>

    <div class="row">

        <div class="col-sm-3">
                
            <?=  $form->field($model, "to_date")->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Enter date ...'],
                'pluginOptions' => [
                            'autoclose'=>true,
                            'format'=>'dd-mm-yyyy'
                        ]
            ]); ?>
            
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset',['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 
    $select2Options = json_encode([
         'data'=>'',         
         'multiple' => false,
         //'theme' => 'krajee',
         'placeholder' => 'Select',
         'language' => 'en-US',
         'width' => '100%',
    ]);
     
    $fromHeadUrl = Url::to(['payment/ajax-from-account']);
    
$formatJs = <<< JS
   
         $("#payment-from_head").change(function(){
			  var from_account = $("#payment-from_account")
			  $.ajax({
				  url:'$fromHeadUrl',
				  data:{from_head:$(this).val()},
				  success:function(data){
				      select2Options = $select2Options;
					  from_account.find("option").remove();
					  select2Options.data = data.data;
					  from_account.select2(select2Options);
				  }
			  });
		  });
   
JS;
$this->registerJs($formatJs);
   
