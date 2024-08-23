<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Sites */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sites-form">

   <div>
    <?php $form = ActiveForm::begin(['id'=>'site-form']); ?>
   
	<?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', 
                'widgetBody' => '.container-items', 
                'widgetItem' => '.item', 
                'limit' => 50, 
                'min' => 1, 
                'insertButton' => '.add-item', 
                'deleteButton' => '.remove-item', 
                'model' => $model[0],
                'formId' => 'site-form',
                'formFields' => [
                    'name',
                    'state_id',
                    'district_id',
                    'status',
                    'company_id'
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($model as $i => $item): ?>
                <div class="item panel"><!-- widgetBody -->
                    
                    <div class="">
                        <?php
                            echo Html::activeHiddenInput($item, "[{$i}]company_id",['value'=>$company_id,'class'=>'company-id']);
                            // necessary for update action.
                            if (! $item->isNewRecord) {
                                echo Html::activeHiddenInput($item, "[{$i}]id");
                            }
						
                        ?>
                        
						<div class="row">
                            <div class="col-sm-4">
							<?=  $form->field($item, "[{$i}]name")->textInput(); ?>
                            </div>
							<div class="col-sm-4">
							  <?= $form->field($item,  "[{$i}]state_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->all(),'id','state' ),
                               'options' => ['placeholder' => 'Select a State ...','class'=>'state'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                            <div class="col-sm-3">
							<?= $form->field($item, "[{$i}]district_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\District::find()->where(['state_id'=>$item->state_id])->all(),'id','district'),
                               'options' => ['placeholder' => 'Select a District ...','class'=>'district'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
							<div class="col-sm-1">
							    <br>
							    <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                            </div>
                        </div><!-- .row -->
                        
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
			
						<div class="row">
						     <div class="col-md-12">
							      <button type="button" class="add-item btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i>Add</button>
							 </div>
						</div>
            <?php DynamicFormWidget::end(); ?>
	
	<div class="col-md-12">
	
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>

</div>

<?php 

$districtStateWiseUrl = Url::to(['common/ajax-district-by-state']);


$script = <<< JS
   
   $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
      $(".company-id").val($("#sites-0-company_id").val());
      $(e.target).find('.container-items').find('.item:last').find('input').not(".company-id").val('');
      $(e.target).find('.container-items').find('.item:last').find('select').val('').trigger('change');
   });
   
   $(document).on("change",".state",function(){
       var index = $(".state").index(this);
       var state_id = $(this).val();
       var district = $(".district").eq(index);
       $.ajax({
		  url:"$districtStateWiseUrl?",
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
   
JS;

$this->registerJS($script);
