<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2; 
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model app\models\VendorWorkRate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendor-work-rate-form">

    <?php $form = ActiveForm::begin([
	   'id'=>'workRateForm',
	]); ?>
    
	                    <div class="panel panel-default">
        <div class="panel-body">
             <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', 
                'widgetBody' => '.container-items', 
                'widgetItem' => '.item', 
                'limit' => 10, 
                'min' => 1, 
                'insertButton' => '.add-item', 
                'deleteButton' => '.remove-item', 
                'model' => $model[0],
                'formId' => 'workRateForm',
                'formFields' => [
                    'vendor_id',
                    'work_type',
                    'work_name',
                    'rate',
                    'company_id',
                    'session',
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($model as $i => $rate): ?>
			
                <div class="item panel panel-default"><!-- widgetBody -->
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $rate->isNewRecord) {
                                echo Html::activeHiddenInput($rate, "[{$i}]id");
                            }
                        ?>
                        <div class="row">
                            <div class="col-sm-4">
              <?= $form->field($rate, "[{$i}]company_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...','class'=>'company'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                            
                            <div class="col-sm-4">
              <?= $form->field($rate, "[{$i}]vendor_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Vendor::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...','class'=>'vendor'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                            <div class="col-sm-4">
              <?= $form->field($rate, "[{$i}]work_type")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\WorkType::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...','class'=>'work_type'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                        </div><!-- .row -->

                        <div class="row">
                            <div class="col-sm-6">
							<?= $form->field($rate, "[{$i}]work_name")->widget(Select2::classname(), [
                               'data' => $rate->isNewRecord ?"":\yii\helpers\ArrayHelper::map(\app\models\Work::find()->where(['work_type'=>$rate->work_type])->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...','class'=>'work'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                            <div class="col-sm-5">
                                <?= $form->field($rate, "[{$i}]rate")->textInput(['maxlength' => true]) ?>
                            </div>
							
                            <div class="col-sm-1"><br>
                                <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                            </div>
							
                        </div><!-- .row -->
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
			
	<div class="row">
	
	   <div class="col-sm-12">
              
			  
			<div class="form-group">
                 <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
             <button type="button" class="add-item btn btn-info pull-right"><i class="glyphicon glyphicon-plus"></i>Add</button>
             </div>
                 
	   </div>
	
	</div>
            <?php DynamicFormWidget::end(); ?>
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

     $ajaxUrl = Url::to(['vendor-work-rate/ajax-work']);
     $this->registerJs('
	      var work_options = "";

        $("#vendorworkrate-0-work_type").change(function(){
         
            var work = $(".work");
            $.ajax({
              url:"'.$ajaxUrl.'",
              data:{work_type:$(this).val()},
              success:function(data){
                work_options = data.data;
                select2Options = '.$select2Options.';
                work.find("option").remove();
                select2Options.data = data.data;
                work.select2(select2Options);
              }
            });

        });

	      $(".dynamicform_wrapper").on("beforeInsert", function(e, item) {
              if($("#vendorworkrate-0-work_type").val() == ""){
				      alert("Fill the all field");
				      return false;
			  }
          });

          $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
               var work = $(".work:last");
               $(".vendor").not(":first").parent().hide();
               $(".vendor").not(":first").val($("#vendorworkrate-0-vendor_id").val()).trigger("change");;
               $(".work_type").not(":first").parent().hide();
               $(".work_type").not(":first").val($("#vendorworkrate-0-work_type").val()).trigger("change");;
               $(".company").not(":first").parent().hide();
               $(".company").not(":first").val($("#vendorworkrate-0-company_id").val()).trigger("change");;
                select2Options = '.$select2Options.';
                work.find("option").remove();
                select2Options.data = work_options;
                work.select2(select2Options);
          });

          $(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
                if (! confirm("Are you sure you want to delete this item?")) {
                       return false;
                }
                return true;
          });

          $(".dynamicform_wrapper").on("limitReached", function(e, item) {
               alert("Limit reached");
          });

	 ');

?>
