<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
use kartik\file\FileInput;
use kartik\color\ColorInput

/* @var $this yii\web\View */
/* @var $model app\models\Company */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="box box-primary">
    <div class="box-header with-boder">
        <div class="box-boyd">
            <div class="company-form">
                <div class="row">
                     <?php $form = ActiveForm::begin(); ?>
	                 
                    <div class="col-md-6">
                          <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'name_color')->widget(ColorInput::classname(), [
                             'options' => ['placeholder' => 'Select color ...'],
                        ]); ?>
                    </div>
                    <div class="col-md-3">
                           <?= $form->field($model, 'type')->label("Type")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\CompanyType::find()->orderBy('id')->asArray()->all(), 'id', 'type'),
                               'options' => ['placeholder' => 'Select a Type ...'],
                               'pluginOptions' => [
                                   'allowClear' => true
                               ],
                           ]); ?>
                     </div>
	
                     <div class="col-md-9">
                        <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>
                     </div>
                     
                     <div class="col-md-3">
                        <?= $form->field($model, 'address_color')->widget(ColorInput::classname(), [
                             'options' => ['placeholder' => 'Select color ...'],
                        ]); ?>
                     </div>
                     
	                 
                     <div class="col-md-6">
                        <?= $form->field($model, 'state')->label("State")->widget(Select2::classname(), [
                            'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
                            'options' => ['placeholder' => 'Select a State ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                       ]); ?>
                     </div>
	
                     <div class="col-md-6">
                        <?= $form->field($model, 'district')->label("District")->widget(Select2::classname(), [
                            'data' => \yii\helpers\ArrayHelper::map(\app\models\District::find()->where(['state_id'=>$model->state])->orderBy('id')->asArray()->all(), 'id', 'district'),
                            'options' => ['placeholder' => 'Select a Type ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                     </div>
	
                     <div class="col-md-6">
                          <?= $form->field($model, 'pincode')->textInput(['maxlength' => true]) ?>
                     </div>
	                 
                     <div class="col-md-6">
                        <?= $form->field($model, 'person')->textInput(['maxlength' => true]) ?>
                     </div>
	                 
                     <div class="col-md-6">
                         <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>
                     </div>
	                 
                     <div class="col-md-6">
                        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                     </div>
	                 
                     <div class="col-md-6">
                         <?= $form->field($model, 'pancard_no')->textInput(['maxlength' => true]) ?>
                     </div>
	                 
                     <div class="col-md-6">
                     <?= $form->field($model, 'gst_no')->textInput(['maxlength' => true]) ?>
                     </div>
                     
                      <div class="col-md-12">
                        <?= $form->field($model, 'logo')->widget(FileInput::classname(), [
                           'options' => [
                                'multiple' => false,
                                'accept' => 'image/*'
                            ],
                           'pluginOptions'=>[
                                'allowedFileExtensions'=>['jpg', 'gif', 'png', 'bmp'],
                                'initialPreview' => [
                                    $model->logo ? Html::img(Yii::getAlias("@web/upload/logo/").$model->logo) : null, // checks the models to display the preview
                                ],
                                'showPreview' => true,
                                'showCaption' => true,
                                'showRemove' => false,
                                'showUpload' => false,
                                'previewFileType' => 'image',
                                'previewSettings' => [
                                    'image' => ['max-width' => '200px !important', 'height' => 'auto']
                                ],
                            ],
                        ]); ?>

                     </div>
	                 
                     <div class="col-md-12">
                     <div class="form-group">
                         <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                     </div>
                     </div>
                    <?php ActiveForm::end(); ?>

                </div>
                <?php 
                $districtStateWiseUrl = Url::to(['district/district-state-wise']);
                $formatJs = <<< JS
                
                  $('#company-state').change(function(){
                	  
                	  var state = $(this).val();
                	  var district = $("#company-district");
                	
                	  $.ajax({
                		  url:'$districtStateWiseUrl',
                		  type:'post',
                		  data:{state:state},
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
 
                // Register the formatting script
                $this->registerJs($formatJs);
                
                ?>

        </div>
    </div>
</div>